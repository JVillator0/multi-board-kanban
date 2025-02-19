<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
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
            'email' => $this->email,
            'status' => $this->status,
            'board' => new BoardResource($this->whenLoaded('board')),
            $this->when($this->created_at, fn () => [
                'created_at' => [
                    'ago' => $this->created_at->diffForHumans(),
                    'datetime' => $this->created_at->format('F j, Y, H:i'),
                    'timestamp' => $this->created_at->timestamp,
                ],
            ]),
            $this->when($this->updated_at, fn () => [
                'updated_at' => [
                    'ago' => $this->updated_at->diffForHumans(),
                    'datetime' => $this->updated_at->format('F j, Y, H:i'),
                    'timestamp' => $this->updated_at->timestamp,
                ],
            ]),
        ];
    }
}
