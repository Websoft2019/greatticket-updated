<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketUserResource extends JsonResource
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
            'order_package_id' => $this->order_package_id,
            'package_name' => $this->orderPackage->package->title ?? 'Unknown Package',
            'name' => $this->name,
            // 'gender' => $this->gender,
            'qr_code' => $this->qr_code,
            'qr_image' => $this->qr_image ? (config('app.url') .'/storage/' . $this->qr_image) : null,
            'checkedin' => $this->checkedin ? $this->checkedin : null,
            // 'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            // 'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
