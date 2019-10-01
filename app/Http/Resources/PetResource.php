<?php

namespace App\Http\Resources;

use App\Models\Pet;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PetResource
 * @package App\Http\Resources
 *
 * @property-read Pet $resource
 */
class PetResource extends JsonResource
{
    public static $wrap = null;

    /**
     * {@inheritDoc}
     */
    public static function collection($resource)
    {
        $collection = parent::collection($resource);
        $collection::withoutWrapping();
        return $collection;
    }
}
