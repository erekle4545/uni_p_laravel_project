<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NumberBaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);

//        return [
//            'phone'=>$this->phone,
//            'category_id'=>$this->category_id,
//            'subCategory'=>$this->subCategory,
//            'project_id'=>$this->project_id,
//            'username'=>$this->username,
//            'area_id'=>$this->area_id,
//            'comment'=>$this->comment,
//            'call_date'=>$this->call_date,
//            'user_id'=>$this->users->username,
//            'liddy_status'=>$this->liddy_status,
//            'created_at'=>$this->created_at,
//        ];
    }
}
