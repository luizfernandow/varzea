<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Race extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date_start' => $this->date_start,
            'time_start' => $this->time_start,
            'laps' => $this->laps,
            'best_lap' => $this->whenLoaded('lap', function () {
                return new Lap($this->lap->sortBy('time')->first());
            }),
            'rank' => $this->whenLoaded('lap', $this->generateRank()),
            'time_laps' => $this->whenLoaded('lap', function () {
                return $this->lap->mapToGroups(function ($item, $key) {
                    return [$item['racer_id'] => $item['time']];
                });
            })
        ];
    }

    private function generateRank()
    {
        $rank = $this->getRank()->load('racer')->map(function ($item, $key) {
            $item->points = $this->laps == $item->laps ? $this->getBasePoint($key) : 0;
            return $item;
        });
        return Lap::collection($rank);
    }
}
