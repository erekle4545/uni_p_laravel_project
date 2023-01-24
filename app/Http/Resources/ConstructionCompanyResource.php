<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConstructionCompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [
            'id'=>$this->id,
            'companyName'=>$this->companyName,
            'ceo_id'=>$this->ceo_id,
            'accountant_id'=>$this->accountant_id,
            'created_at'=>$this->created_at,
            'info'=>[
                'employees'=>$this->CompanyEmployees,
            ]
        ];
    }
}
