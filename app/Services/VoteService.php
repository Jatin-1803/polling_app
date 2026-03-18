<?php
namespace App\Services;

use App\Repositories\VoteRepositoryInterface;
use App\Models\PollOption;
use App\Models\Poll;
use App\Events\VoteCast;
use Illuminate\Support\Facades\DB;

class VoteService
{
    protected $voteRepo;
    public function __construct(VoteRepositoryInterface $voteRepo)
    {
        $this->voteRepo = $voteRepo;
    }

    /**
     * Record a vote. Throws \Exception on duplicate.
     */
    public function recordVote(int $pollId, int $optionId, ?int $userId, ?string $ip)
    {
        $voterKey = $userId ? ('user:' . $userId) : ('ip:' . ($ip ?: 'unknown'));

        if ($this->voteRepo->existsForVoterKey($pollId, $voterKey)) {
            throw new \Exception('User/IP already voted for this poll');
        }

        return DB::transaction(function() use ($pollId, $optionId, $userId, $ip) {
            $voterKey = $userId ? ('user:' . $userId) : ('ip:' . ($ip ?: 'unknown'));

            $vote = $this->voteRepo->create([
                'poll_id' => $pollId,
                'poll_option_id' => $optionId,
                'user_id' => $userId,
                'ip_address' => $ip,
                'voter_key' => $voterKey,
            ]);

            // increment cached counter quickly (atomic)
            PollOption::where('id', $optionId)->increment('votes_count', 1);

            // broadcast new counts
            $option = PollOption::where('id', $optionId)->first();
            // fetch full counts for the poll
            $counts = $option->poll->options()->get(['id','option_text','votes_count'])->map(function($o){
                return ['id' => $o->id, 'option_text' => $o->option_text, 'votes_count' => (int)$o->votes_count];
            });

            // broadcast to everyone (including the voter) so their UI updates instantly
            broadcast(new VoteCast($option->poll->uuid, $counts));

            return ['vote' => $vote, 'counts' => $counts];
        });
    }
}