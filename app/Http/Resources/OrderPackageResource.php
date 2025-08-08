<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPackageResource extends JsonResource
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
            'package_title' => $this->package->title, // Title from the related package
            'user_names' => $this->ticketUsers->pluck('name'), // List of names from ticketUsers
        ];
    }
}
