<?php

namespace Basantashubhu\ArrayModel;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class ArrayModel extends \ArrayObject implements Arrayable
{
    use Traits\CreateUpdate;

    public static $instance = null;

    public static function factory()
    {
        if (static::$instance === null) {
            static::$instance = Collection::make();
        }

        return static::$instance;
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func([static::factory(), $method], ...$args);
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value)
    {
        return $this->offsetSet($name, $value);
    }

    public function offsetGet($name)
    {
        return parent::offsetExists($name) ? parent::offsetGet($name) : null;
    }

    public function toArray()
    {
        return (array) $this;
    }

    public static function array()
    {
        return static::factory()->toArray();
    }
}
