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
}