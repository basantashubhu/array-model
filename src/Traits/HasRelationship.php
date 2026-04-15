<?php

namespace Basanta\ArrayModel\Traits;

use Basanta\ArrayModel\ArrayModel;
use Basanta\ArrayModel\Collection;

trait HasRelationship
{
    public function hasMany($relatedClass, $foreignKey, $localKey): Collection
    {
        return $relatedClass::factory()->where($foreignKey, $this->$localKey);
    }

    public function hasOne($relatedClass, $foreignKey, $localKey): ?ArrayModel
    {
        return $relatedClass::factory()->where($foreignKey, $this->$localKey)->first();
    }

    public function belongsTo($relatedClass, $foreignKey, $ownerKey): ?ArrayModel
    {
        return $relatedClass::factory()->where($ownerKey, $this->$foreignKey)->first();
    }

    public static function load(...$relationship): Collection 
    {
        $relationships = is_array($relationship[0]) ? $relationship[0] : $relationship;

        foreach ($relationships as $relation) {
            if (method_exists(static::class, $relation)) {
                static::factory()->each->$relation;
            } else {
                throw new \LogicException("Relationship method " . $relation . " does not exist on " . static::class);
            }
        }

        return static::factory();
    }
}