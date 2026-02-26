<?php

namespace Yourname\LaravelGlobe\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->iso2,
            'flag' => $this->emoji,
            'phone_code' => '+' . $this->phone_code,
            'stats' => $this->whenCounted('states', function () {
                return [
                    'states' => $this->states_count,
                    'cities' => $this->cities_count ?? 0,
                ];
            }),
            'links' => [
                'states' => url(config('laravelglobe.routes_prefix', 'api/globe') . "/countries/{$this->iso2}/states"),
            ]
        ];
    }
}
