<?php
namespace App\Repositories;

use App\Models\Poll;

interface PollRepositoryInterface {
    public function create(array $data): Poll;
    public function findByUuid(string $uuid): ?Poll;
    public function listByAdmin($adminId);
}