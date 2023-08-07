<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'start' => $this->start,
            'end' => Carbon::parse($this->end)->addDay()->format('Y-m-d'),
            'url' => route('events.show', $this->id),
        ];
    }
}
