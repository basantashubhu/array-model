<?php

namespace Basanta\ArrayModel;

use Illuminate\Contracts\Support\Arrayable;

class ArrayModel extends \ArrayObject implements Arrayable
{
    use Traits\CreateUpdate;
    use Traits\HasRelationship;

    public static $instance = [];

    public static function factory()
    {
        if (is_subclass_of(get_called_class(), self::class)) {
            return static::$instance[get_called_class()] ??= Collection::make();
        } else {
            throw new \Exception("Class " . get_called_class() . " must extend " . self::class);
        }
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
        if (parent::offsetExists($name)) {
            return parent::offsetGet($name);
        } elseif (method_exists($this, $name)) {
            return $this->$name();
        }
        return null;
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
