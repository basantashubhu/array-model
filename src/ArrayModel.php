<?php

namespace Basanta\ArrayModel;

use Illuminate\Contracts\Support\Arrayable;

class ArrayModel extends \ArrayObject implements Arrayable
{
    use Traits\CreateUpdate;

    /**
     * Per-model-class in-memory collection instances.
     *
     * @var array<class-string,Collection>
     */
    public static $instance = [];

    /**
     * Get or create the in-memory collection for the called model class.
     */
    public static function factory(): Collection
    {
        if(is_subclass_of(get_called_class(), self::class)) {
            return static::$instance[get_called_class()] ??= Collection::make();
        } else {
            throw new \Exception("Class " . get_called_class() . " must extend " . self::class);
        }
    }

    /**
     * Proxy static method calls to the class collection instance.
     */
    public static function __callStatic($method, $args): mixed
    {
        return call_user_func([static::factory(), $method], ...$args);
    }

    /**
     * Read an attribute from the model.
     */
    public function __get($name): mixed
    {
        return $this->offsetGet($name);
    }

    /**
     * Set an attribute on the model.
     */
    public function __set($name, $value): void
    {
        $this->offsetSet($name, $value);
    }

    /**
     * Read a key from the underlying array and return null if missing.
     */
    public function offsetGet($name): mixed
    {
        return parent::offsetExists($name) ? parent::offsetGet($name) : null;
    }

    /**
     * Convert the current model instance to array.
     */
    public function toArray(): array
    {
        return (array) $this;
    }

    /**
     * Get all records for the called model class as array.
     */
    public static function array(): array
    {
        return static::factory()->toArray();
    }
}
