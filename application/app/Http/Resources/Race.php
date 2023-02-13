<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class Race extends JsonResource
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
            'best_lap' => $this->whenLoaded('lap', fn (): \App\Http\Resources\Lap => new Lap($this->lap->sortBy('time')->first())),
            'time_laps' => $this->whenLoaded('lap', fn () => $this->lap->mapToGroups(fn ($item, $key): array => [$item['racer_id'] => $item['time']])),
            $this->mergeWhen(!$this->isTypeHours(), [
                'rank' => $this->whenLoaded('lap', $this->generateRank()),
            ]),
            $this->mergeWhen($this->isTypeHours(), [
                'rank_group' => $this->whenLoaded('lap', $this->generateRankGroup()),
                'groups' => $this->load('racersGroup')->racersGroup->mapToGroups(function ($item): array {
                    $item->load('racer');
                    return [$item['group'] => $item];
                }),
            ]),
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

    private function generateRankGroup()
    {
        $rank = $this->getRankGroup();
        return Lap::collection($rank);
    }
}
