<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class Lap extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array{time: mixed, group: mixed, racer: \App\Http\Resources\Racer, laps: mixed, points: mixed}
     */
    public function toArray($request)
    {
        return [
            'time' => $this->time,
            'group' => $this->group,
            'racer' => new Racer($this->whenLoaded('racer')),
            'laps' => $this->when($this->laps, $this->laps),
            'points' => $this->when($this->laps, $this->points)
        ];
    }
}
