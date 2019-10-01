<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\StoreRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;

/**
 * Class Store
 * @package App\Http\Controllers\Pet
 */
class Store extends Controller
{
    /**
     * @param StoreRequest $request
     * @return PetResource
     */
    public function __invoke(StoreRequest $request)
    {
        $validated = $request->validated();
        $pet = new Pet(rand(4, 9), $validated['name'], $validated['tag'] ?? null);
        return new PetResource($pet);
    }
}
