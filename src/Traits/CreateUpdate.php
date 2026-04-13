<?php

namespace Basanta\ArrayModel\Traits;

trait CreateUpdate
{
    public static function create(array $attributes): static
    {
        static::factory()->push($item = new static($attributes));
        return $item;
    }

    public static function update(array $where, array $attributes): bool
    {
        return static::factory()->where($where)->update($attributes);
    }

    public function delete(array $where): bool
    {
        return static::factory()->where($where)->delete();
    }
}