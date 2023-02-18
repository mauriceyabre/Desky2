<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Project */
class ProjectCollection extends ResourceCollection {
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request) : array {
        return [
            'data' => $this->collection,
        ];
    }
}
