<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;

/**
 * Class Destroy
 * @package App\Http\Controllers\Pet
 */
class Destroy extends Controller
{
    /**
     * @param int $petId
     * @return PetResource
     */
    public function __invoke(int $petId)
    {
        return response('', 204);
    }
}
