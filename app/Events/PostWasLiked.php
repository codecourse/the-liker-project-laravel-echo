<?php

namespace App\Events;

use App\Post;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostWasLiked implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $post;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('likes'),
            new PrivateChannel('App.User.' . $this->post->user->id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'post' => array_merge($this->post->toArray(), [
                'user' => $this->post->user
            ]),
            'user' => $this->user,
        ];
    }
}
