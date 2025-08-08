<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentHistoryResource extends JsonResource
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
            'title' => $this->orderPackages->first()->package->event->title ?? "",
            'total' => number_format($this->grandtotal, 2, '.', ''),
            'details' => "Total Quantity " . ($this->orderPackage->quantity ?? ""),
            'date' => $this->created_at->toDateTimeString(),
            // 'qr_image' => $this->qr_image ? config('app.url') . '/storage/' . $this->qr_image : '',
        ];
    }
}
