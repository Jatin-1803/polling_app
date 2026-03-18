<?php
namespace App\Repositories;

use App\Models\Vote;

class VoteRepository implements VoteRepositoryInterface
{
    public function create(array $data): Vote
    {
        return Vote::create($data);
    }

    public function existsForVoterKey(int $pollId, string $voterKey): bool
    {
        return Vote::where('poll_id', $pollId)
            ->where('voter_key', $voterKey)
            ->exists();
    }
}