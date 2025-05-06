<?php

namespace App\Events;

use App\Models\Project\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectSavedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Project $project;

    protected array $data;
    public function __construct(Project $project, array $data = [])
    {
        $this->project = $project;
        $this->data = $data;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
