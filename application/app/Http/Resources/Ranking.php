<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class Ranking extends JsonResource
{
    public function toArray($request)
    {
        return [
            'points' => $this->points,
            'racer' => new Racer($this->whenLoaded('racer')),
        ];
    }
}
