<?php

namespace App\Models;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Pet
 * @package App\Models
 */
class Pet implements Arrayable
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string|null */
    private $tag;

    /**
     * Pet constructor.
     * @param int $id
     * @param string $name
     * @param string|null $tag
     */
    public function __construct(int $id, string $name, ?string $tag = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tag = $tag;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge([
            'id' => $this->id,
            'name' => $this->name,
        ], empty($this->tag) ? [] : ['tag' => $this->tag]);
    }
}
