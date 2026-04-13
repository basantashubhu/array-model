<?php

namespace Basantashubhu\ArrayModel\Traits;

trait CreateUpdate
{
    public static function create(array $attributes): static
    {
        static::factory()->push($item = new static($attributes));
        return $item;
    }

    public static function update(array $where, array $attributes): void
    {
        foreach (static::factory()->where($where) as $item) {
            foreach ($attributes as $key => $value) {
                $item->$key = $value;
            }
        }
    }

    public function delete(array $where): bool
    {
        return static::factory()->where($where)->delete();
    }
}