<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Task extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            '_link' => url('/api/tasks/' . $this->id),
            'id' => $this->id,
            'domain' => $this->url,
            'status' => $this->status,
            'phising_estimate' => $this->score,
        ];
    }
}
