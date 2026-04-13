<?php

namespace Basanta\ArrayModel;

use Illuminate\Support\Arr;
/**
 * @template TModel of ArrayModel
 * @extends \Illuminate\Support\Collection<TModel>
 */
class Collection extends \Illuminate\Support\Collection
{
    /**
     * Filter the collection, supporting array-based multi-condition queries.
     */
    public function where($key, $operator = null, $value = null): static
    {
        if (is_array($key)) {
            return $this->whereMany($key);
        }

        return parent::where(...func_get_args());
    }

    /**
     * Filter items by many conditions.
     */
    public function whereMany(array $conditions): static
    {
        $results = [];

        foreach ($this->items as $item) {
            $match = true;

            foreach ($conditions as $key => $value) {

                $operator = '==';

                if (Arr::isAssoc($conditions) === false) {

                    [$key, $operator, $value] = match (count($value)) {
                        2 => [$value[0], '==', $value[1]],
                        3 => $value,
                        default => throw new \Exception("Invalid condition format for whereMany. Expected [key, operator, value] or [key, value]."),
                    };
                }

                if(is_array($value)) {
                    $operator = str_contains($operator, 'not') ? 'not in' : 'in';
                }

                $match = match ($operator) {
                    '=', '==' => $item->$key == $value,
                    '===' => $item->$key === $value,
                    '!=', '<>', 'not' => $item->$key != $value,
                    '!==' => $item->$key !== $value,
                    '>' => $item->$key > $value,
                    '>=' => $item->$key >= $value,
                    '<' => $item->$key < $value,
                    '<=' => $item->$key <= $value,
                    'in' => in_array($item->$key, $value),
                    'not in' => !in_array($item->$key, $value),
                    default => throw new \Exception("Unsupported operator '$operator' in whereMany condition."),
                };

                if ($match === false) {
                    break;
                }
            }

            if ($match) {
                $results[] = $item;
            }
        }

        return new static($results);
    }

    /**
     * Update all matched model items with the given attributes.
     */
    public function update(array $attributes): bool
    {
        foreach ($this->items as $item) {
            foreach ($attributes as $key => $value) {
                $item->$key = $value;
            }
        }

        return true;
    }

    /**
     * Delete all matched model items from the source store.
     */
    public function delete(): bool
    {
        $items = $this->items;
        foreach ($this->items as $item) {
            $item::factory()->items = $item::factory()->reject(fn($fi) => in_array($fi, $items, true))->all();
            break;
        }

        return true;
    }
}
