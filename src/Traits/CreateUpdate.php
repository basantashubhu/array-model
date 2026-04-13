<?php

namespace Basanta\ArrayModel\Traits;

trait CreateUpdate
{
    /**
     * Create and store a new model record.
     */
    public static function create(array $attributes): static
    {
        static::factory()->push($item = new static($attributes));
        return $item;
    }

    /**
     * Update model records that match the given conditions.
     */
    public static function update(array $where, array $attributes): bool
    {
        return static::factory()->where($where)->update($attributes);
    }

    /**
     * Delete model records that match the given conditions.
     */
    public static function delete(array $where): bool
    {
        return static::factory()->where($where)->delete();
    }
}
