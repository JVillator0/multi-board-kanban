<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'content' => $this->content,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => [
                'ago' => $this->created_at->diffForHumans(),
                'datetime' => $this->created_at->format('F j, Y, H:i'),
                'timestamp' => $this->created_at->timestamp,
            ],
            'edited' => $this->updated_at > $this->created_at,
        ];
    }
}
