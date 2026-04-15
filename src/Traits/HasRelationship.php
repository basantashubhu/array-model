<?php

namespace Basanta\ArrayModel\Traits;

use Basanta\ArrayModel\ArrayModel;
use Basanta\ArrayModel\Collection;

trait HasRelationship
{
    /**
     * Define a one-to-many relationship.
     * @param class-string $relatedClass
     * @param string $foreignKey
     * @param string $localKey
     * @return Collection
     */
    public function hasMany($relatedClass, $foreignKey, $localKey): Collection
    {
        return $relatedClass::factory()->where($foreignKey, $this->$localKey);
    }

    /**
     * Define a one-to-one relationship.
     * @param class-string $relatedClass
     * @param string $foreignKey
     * @param string $localKey
     * @return ArrayModel|null
     */
    public function hasOne($relatedClass, $foreignKey, $localKey): ?ArrayModel
    {
        return $relatedClass::factory()->where($foreignKey, $this->$localKey)->first();
    }

    /**
     * Define an inverse one-to-many relationship.
     * @param class-string $relatedClass
     * @param string $foreignKey
     * @param string $ownerKey
     * @return ArrayModel|null
     */
    public function belongsTo($relatedClass, $foreignKey, $ownerKey): ?ArrayModel
    {
        return $relatedClass::factory()->where($ownerKey, $this->$foreignKey)->first();
    }

    /**
     * Lazy load relationships for all models in the collection.
     * @param string|array ...$relationship
     * @return Collection
     */
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