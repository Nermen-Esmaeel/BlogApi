<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'title' => $this->title,
            'content' => $this->content,
            'slug' => $this->slug,
            'type'=> $this->type,
            'category'=> $this->category_id,
            'writer by'=> new UserResource($this->users),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'images' =>  ImageResource::collection($this->whenLoaded('images')),
            ];
    }
}
