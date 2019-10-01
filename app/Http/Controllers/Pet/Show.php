<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;
use App\Models\Pet;

/**
 * Class Index
 * @package App\Http\Controllers\Pet
 */
class Show extends Controller
{
    /**
     * @param int $petId
     * @return PetResource
     */
    public function __invoke(int $petId)
    {
        $pets = collect([
            new Pet(1, 'Ace', 'dog'),
            new Pet(2, 'Apollo', 'dog'),
            new Pet(3, 'Bailey'),
        ]);

        return new PetResource($pets->shuffle()->first());
    }
}
