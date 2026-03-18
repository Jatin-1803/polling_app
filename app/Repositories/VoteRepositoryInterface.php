<?php
namespace App\Repositories;

use App\Models\Vote;

interface VoteRepositoryInterface {
    public function create(array $data): Vote;
    public function existsForVoterKey(int $pollId, string $voterKey): bool;
}