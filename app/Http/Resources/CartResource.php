<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'package' => new PackageResource($this->whenLoaded('package')),
            'quantity' => $this->quantity,
            'cost' =>  number_format($this->cost,2, '.', ''),
            // 'event' => new EventResource($this->whenLoaded('event')),
            'event_id' => $this->event_id,
        ];
    }
}
