<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RealtorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        return [
            'id'=>$this->id,
            'companyName'=>$this->companyName,
            'id_number'=>$this->id_number,
            'phone'=>$this->phone,
            'address'=>$this->address,
            'date'=>$this->date

        ];
    }
}
