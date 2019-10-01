<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class Index
 * @package App\Http\Controllers\Pet
 */
class Index extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function __invoke()
    {
        $pets = collect([
            new Pet(1, 'Ace', 'dog'),
            new Pet(2, 'Apollo', 'dog'),
            new Pet(3, 'Bailey'),
        ]);

        return PetResource::collection($pets);
    }
}
