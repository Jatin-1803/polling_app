<?php
namespace App\Services;

use App\Repositories\PollRepositoryInterface;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Support\Facades\DB;

class PollService
{
    protected $pollRepo;
    public function __construct(PollRepositoryInterface $pollRepo)
    {
        $this->pollRepo = $pollRepo;
    }

    public function createPollWithOptions($adminId, string $question, array $options): Poll
    {
        return DB::transaction(function() use ($adminId, $question, $options) {
            $poll = $this->pollRepo->create([
                'question' => $question,
                'admin_id' => $adminId,
                'is_active' => true,
            ]);
            foreach ($options as $opt) {
                $poll->options()->create(['option_text' => $opt]);
            }
            return $poll->load('options');
        });
    }
}