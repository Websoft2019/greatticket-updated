<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'cost' => number_format($this->actual_cost,2, '.', ''),
            'photo' => Str::contains($this->photo, 'http') ? $this->photo : config('app.url') . '/storage/' . $this->photo,
            'description' => $this->description,
            'event_id' => (int)$this->event_id,
            'available_seat' => ($this->capacity - $this->consumed_seat),
            'maxticket' => (int)$this->maxticket,
            'seat_view' => $this->seats()->exists() ? true : false,
        ];
    }
}
