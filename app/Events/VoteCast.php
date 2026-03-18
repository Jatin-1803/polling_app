<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class VoteCast implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $pollUuid;
    public $counts;

    public function __construct(string $pollUuid, $counts)
    {
        $this->pollUuid = $pollUuid;
        $this->counts = $counts;
    }

    public function broadcastOn()
    {
        return new Channel('poll.' . $this->pollUuid);
    }

    public function broadcastAs()
    {
        return 'vote.cast';
    }

    public function broadcastWith()
    {
        return ['counts' => $this->counts];
    }
}