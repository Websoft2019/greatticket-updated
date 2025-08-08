<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TrendingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "slug" => $this->slug,
            "title" => $this->title,
            "date" => $this->date->toDateTimeString(),
            "time" => $this->time,
            "vennue" => $this->vennue,
            "primary_photo" => Str::contains($this->primary_photo, 'http') ? $this->primary_photo : config('app.url') . '/storage/' . $this->primary_photo,
            "seat_view" => $this->seat_view ? (Str::contains($this->seat_view, 'http') ? $this->seat_view : config('app.url') . '/storage/' . $this->seat_view) : '',
            "highlight" => $this->highlight,
            "longitude" => $this->longitude,
            "latitude" => $this->latitude,
            "description" => $this->description,
            "organizer_id" => $this->organizer_id,
            "category" => $this->category ? $this->category->name : '',
            "images" => ImageResource::collection($this->whenLoaded('images')), // Include related images if they are loaded
        ];
    }
}
