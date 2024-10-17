<?php

namespace Modules\Core\Entities\Traits;

/*
 * This trait is to be used with the default $table->uuid('id') schema definition
 * @author Aram Zahedi <badi.ifaoui@zixdev.com>
 * @license MIT
 */
use Ramsey\Uuid\Uuid;

trait UuidModelTrait
{
    /*
     * This function is used internally by Eloquent models to test if the model has auto increment value
     * @returns bool Always false
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * This function overwrites the default boot static method of Eloquent models. It will hook
     * the creation event with a simple closure to insert the UUID
     */
    public static function bootUuidModelTrait()
    {
        static::creating(function ($model) {
            // Only generate UUID if it wasn't set by already.
            if (!isset($model->attributes[$model->getKeyName()])) {
                // This is necessary because on \Illuminate\Database\Eloquent\Model::performInsert
                // will not check for $this->getIncrementing() but directly for $this->incrementing
                $model->incrementing = false;
                $model->keyType = 'string';
                $model->primaryKey = 'id';
                $model->attributes[$model->getKeyName()] = (string) Uuid::uuid4();
            }
        }, 0);
    }
}