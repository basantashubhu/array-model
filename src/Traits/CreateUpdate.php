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
        foreach (static::factory() as $item) {
            $match = true;
            foreach ($where as $key => $value) {
                if ($item->$key !== $value) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                foreach ($attributes as $key => $value) {
                    $item->$key = $value;
                }
            }
        }
    }
}