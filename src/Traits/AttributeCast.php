<?php

namespace Basanta\ArrayModel\Traits;

trait AttributeCast
{
    /**
     * Get a casted attribute value using reflection and callable logic.
     *
     * This method attempts to retrieve and invoke a casted attribute method based on the provided key.
     * It uses reflection to verify the method exists and returns an Illuminate Attribute instance.
     * If a callable getter is defined, it executes the getter with the value and model attributes.
     *
     * @param string $key The attribute key to cast (will be converted to camelCase for method lookup)
     * @param mixed $value The raw attribute value to cast
     * @return mixed The casted attribute value, or the original value if no cast method exists or is callable
     *
     * @throws \ReflectionException If the reflection method cannot be created
     */
    protected function getCastedAttribute($key, $value): mixed
    {
        $castMethod = str($key)->camel()->toString();

        if(!method_exists($this, $castMethod)) {
            return $value;
        }

        $reflectionMethod = new \ReflectionMethod($this, $castMethod);

        $returnType = $reflectionMethod->getReturnType();

        if($returnType?->getName() !== \Illuminate\Database\Eloquent\Casts\Attribute::class) {
            return $value;
        }

        $get = $reflectionMethod->invoke($this)->get;
        
        return is_callable($get) ? $get($value, (array) $this) : $value;
    }

    /**
     * Set a casted attribute value using a custom cast method.
     *
     * This method attempts to find and invoke a camelCase cast method based on the attribute key.
     * If the method exists and returns an Illuminate Attribute instance with a "set" callback,
     * the callback is invoked to transform the value.
     *
     * @param string $key The attribute key to cast
     * @param mixed $value The value to be cast
     * @return mixed The casted value, or the original value if no cast method exists or callback is not callable
     */
    protected function setCastedAttribute($key, $value): mixed
    {
        $castMethod = str($key)->camel()->toString();

        if(!method_exists($this, $castMethod)) {
            return $value;
        }

        $reflectionMethod = new \ReflectionMethod($this, $castMethod);

        $returnType = $reflectionMethod->getReturnType();

        if($returnType?->getName() !== \Illuminate\Database\Eloquent\Casts\Attribute::class) {
            return $value;
        }

        $set = $reflectionMethod->invoke($this)->set;
        
        return is_callable($set) ? $set($value, (array) $this) : $value;
    }
}