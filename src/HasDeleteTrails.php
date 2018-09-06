<?php

namespace Insense\LaravelUserAuditTrails;

use Illuminate\Support\Facades\Auth;

trait HasDeleteTrails
{
    /**
     * Indicates if the model should be audit trailed.
     *
     * @var bool
     */
    public $deleteTrails = true;

    /**
     * The name of the "created by" column.
     *
     * @var string
     */
    public static $DELETED_BY = 'deleted_by';

    /**
     * Register the model events for updating the user audit trails.
     */
    public static function bootHasDeleteTrails()
    {
        static::deleting(function ($model) {
            if($model->usesDeleteTrails() && $model->isSoftDeleting()) {
                $model->updateDeleteTrails();
            }
        });
    }
    
    
    /**
     * To check if model is soft deleted or not
     * @param type $model
     */
    public function isSoftDeleting() {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this)) && !$this->isForceDeleting();
    }

    /*
     * Update the model's user audit trails.
     *
     * @return bool
     */
    public function touchDeleteTrails()
    {
        if (!$this->usesDeleteTrails()) {
            return false;
        }

        $this->updateDeleteTrails();

        return $this->save();
    }

    /**
     * Update the deleted by user audit trails.
     */
    protected function updateDeleteTrails()
    {
        $user = Auth::user();

        if (!is_null(static::$DELETED_BY) && !$this->isDirty(static::$DELETED_BY)) {
            $this->setDeletedBy($user ? $user->getKey() : null);
        }
    }

    /**
     * Set the value of the "created by" attribute.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setDeletedBy($value)
    {
        $this->{static::$DELETED_BY} = $value;

        return $this;
    }

    /**
     * Determine if the model uses user audit trails.
     *
     * @return bool
     */
    public function usesDeleteTrails()
    {
        return $this->deleteTrails;
    }

    /**
     * Get the name of the "created by" column.
     *
     * @return string
     */
    public function getDeletedByColumn()
    {
        return static::$DELETED_BY;
    }

}
