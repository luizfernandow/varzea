<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class Racer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array{id: mixed, name: mixed}
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
