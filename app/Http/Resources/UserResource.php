<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "id"=> $this->id,
            "name" => $this->name,
            "email"=> $this->email,
            "contact" => $this->contact,
            "gender" => $this->gender,
            "status" => $this->session_id ? false : true,
            "dob" => $this->dob,
            "address" => $this->address,
            "country" => $this->country,
            "state" => $this->state,
            "city" => $this->city,
            "postcode" => $this->postcode,
            "icnumber" => $this->icnumber,
        ];
    }
}
