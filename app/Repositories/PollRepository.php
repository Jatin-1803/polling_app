<?php
namespace App\Repositories;

use App\Models\Poll;

class PollRepository implements PollRepositoryInterface
{
    public function create(array $data): Poll
    {
        return Poll::create($data);
    }

    public function findByUuid(string $uuid): ?Poll
    {
        return Poll::with('options')->where('uuid', $uuid)->first();
    }

    public function listByAdmin($adminId)
    {
        return Poll::where('admin_id', $adminId)->withCount('votes')->latest()->get();
    }
}