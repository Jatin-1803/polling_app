<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PollRepositoryInterface;
use App\Services\VoteService;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    protected $pollRepo;
    protected $voteService;

    public function __construct(PollRepositoryInterface $pollRepo, VoteService $voteService)
    {
        $this->pollRepo = $pollRepo;
        $this->voteService = $voteService;
    }

    public function index()
    {
        $polls = \App\Models\Poll::query()
            ->where('is_active', true)
            ->latest()
            ->get(['uuid', 'question', 'created_at']);

        return view('polls.index', compact('polls'));
    }

    public function show($uuid)
    {
        $poll = $this->pollRepo->findByUuid($uuid);
        if (!$poll || !$poll->is_active) abort(404);
        return view('polls.show', compact('poll'));
    }

    public function vote(Request $r, $uuid)
    {
        $poll = $this->pollRepo->findByUuid($uuid);
        if (!$poll) return response()->json(['message' => 'Poll not found'], 404);

        $r->validate([
            'option_id' => ['required', 'integer'],
        ]);

        if (!$poll->options->firstWhere('id', (int) $r->option_id)) {
            return response()->json(['message' => 'Invalid option for this poll'], 422);
        }

        $userId = Auth::check() ? Auth::id() : null;
        $ip = $r->ip();

        try {
            $res = $this->voteService->recordVote($poll->id, (int) $r->option_id, $userId, $ip);
            return response()->json([
                'message' => 'Vote recorded',
                'counts' => $res['counts'] ?? null,
            ]);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $status = str_contains($msg, 'already voted') ? 409 : 422;
            return response()->json(['message' => $msg], $status);
        }
    }
}