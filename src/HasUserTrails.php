<?php

namespace Insense\LaravelUserAuditTrails;

use Illuminate\Support\Facades\Auth;

trait HasUserTrails
{
    /**
     * Indicates if the model should be audit trailed.
     *
     * @var bool
     */
    public $userTrails = true;

    /**
     * The name of the "created by" column.
     *
     * @var string
     */
    public static $CREATED_BY = 'created_by';

    /**
     * The name of the "updated by" column.
     *
     * @var string
     */
    public static $UPDATED_BY = 'updated_by';

    /**
     * Register the model events for updating the user audit trails.
     */
    public static function bootHasUserTrails()
    {
        static::saving(function ($model) {
            if ($model->usesUserTrails()) {
                $model->updateUserTrails();
            }
        });
    }

    /*
     * Update the model's user audit trails.
     *
     * @return bool
     */
    public function touchUserTrails()
    {
        if (!$this->usesUserTrails()) {
            return false;
        }

        $this->updateUserTrails();

        return $this->save();
    }

    /**
     * Update the created by and updated by user audit trails.
     */
    protected function updateUserTrails()
    {
        $user = Auth::user();

        if (!is_null(static::$UPDATED_BY) && !$this->isDirty(static::$UPDATED_BY)) {
            $this->setUpdatedBy($user ? $user->getKey() : null);
        }

        if (!is_null(static::$CREATED_BY) && !$this->exists && !$this->isDirty(static::$CREATED_BY)) {
            $this->setCreatedBy($user ? $user->getKey() : null);
        }
    }

    /**
     * Set the value of the "created by" attribute.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setCreatedBy($value)
    {
        $this->{static::$CREATED_BY} = $value;

        return $this;
    }

    /**
     * Set the value of the "updated by" attribute.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setUpdatedBy($value)
    {
        $this->{static::$UPDATED_BY} = $value;

        return $this;
    }

    /**
     * Determine if the model uses user audit trails.
     *
     * @return bool
     */
    public function usesUserTrails()
    {
        return $this->userTrails;
    }

    /**
     * Get the name of the "created by" column.
     *
     * @return string
     */
    public function getCreatedByColumn()
    {
        return static::$CREATED_BY;
    }

    /**
     * Get the name of the "updated by" column.
     *
     * @return string
     */
    public function getUpdatedByColumn()
    {
        return static::$UPDATED_BY;
    }
}
