<?php

namespace App\Nova\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * @method static beforeSave(NovaRequest $request, Model $model): void
 * @method static afterSave(NovaRequest $request, Model $model): void
 * @method static beforeCreate(NovaRequest $request, Model $model): void
 * @method static afterCreate(NovaRequest $request, Model $model): void
 * @method static beforeUpdate(NovaRequest $request, Model $model): void
 * @method static afterUpdate(NovaRequest $request, Model $model): void
 * @method static beforePivotSave(NovaRequest $request, Model $model, Pivot $pivot): void
 * @method static afterPivotSave(NovaRequest $request, Model $model, Pivot $pivot): void
 * @method static beforePivotCreate(NovaRequest $request, Model $model, Pivot $pivot): void
 * @method static afterPivotCreate(NovaRequest $request, Model $model, Pivot $pivot): void
 * @method static beforePivotUpdate(NovaRequest $request, Model $model, Pivot $pivot): void
 * @method static afterPivotUpdate(NovaRequest $request, Model $model, Pivot $pivot): void
 */
trait HasCallbacks
{
    public static function fillPivot(NovaRequest $request, $model, $pivot)
    {
        if (method_exists(static::class, 'beforePivotSave')) {
            static::beforePivotSave($request, $model, $pivot);
        }

        if (method_exists(static::class, 'beforePivotCreate')) {
            static::beforePivotCreate($request, $model, $pivot);
        }

        if (method_exists(static::class, 'afterPivotSave')) {
            $pivot::saved(function ($model) use ($request, $pivot) {
                static::afterPivotSave($request, $model, $pivot);
            });
        }

        if (method_exists(static::class, 'afterPivotCreate')) {
            $pivot::created(function ($model) use ($request, $pivot) {
                static::afterPivotCreate($request, $model, $pivot);
            });
        }

        return parent::fillPivot($request, $model, $pivot);
    }

    public static function fillPivotForUpdate(NovaRequest $request, $model, $pivot)
    {
        if (method_exists(static::class, 'beforePivotSave')) {
            static::beforePivotSave($request, $model, $pivot);
        }

        if (method_exists(static::class, 'beforePivotUpdate')) {
            static::beforePivotUpdate($request, $model, $pivot);
        }

        if (method_exists(static::class, 'afterPivotSave')) {
            $model::saved(function ($model) use ($request, $pivot) {
                static::afterPivotSave($request, $model, $pivot);
            });
        }

        if (method_exists(static::class, 'afterPivotUpdate')) {
            $model::saved(function ($model) use ($request, $pivot) {
                static::afterPivotUpdate($request, $model, $pivot);
            });
        }

        return parent::fillPivotForUpdate($request, $model, $pivot);
    }

    public static function fill(NovaRequest $request, $model)
    {
        if (method_exists(static::class, 'beforeSave')) {
            static::beforeSave($request, $model);
        }

        if (method_exists(static::class, 'beforeCreate')) {
            static::beforeCreate($request, $model);
        }

        if (method_exists(static::class, 'afterSave')) {
            $model::saved(function ($model) use ($request) {
                static::afterSave($request, $model);
            });
        }

        if (method_exists(static::class, 'afterCreate')) {
            $model::created(function ($model) use ($request) {
                static::afterCreate($request, $model);
            });
        }

        return parent::fill($request, $model);
    }

    public static function fillForUpdate(NovaRequest $request, $model)
    {
        if (method_exists(static::class, 'beforeSave')) {
            static::beforeSave($request, $model);
        }

        if (method_exists(static::class, 'beforeUpdate')) {
            static::beforeUpdate($request, $model);
        }

        if (method_exists(static::class, 'afterSave')) {
            $model::saved(function ($model) use ($request) {
                static::afterSave($request, $model);
            });
        }

        if (method_exists(static::class, 'afterUpdate')) {
            $model::saved(function ($model) use ($request) {
                static::afterUpdate($request, $model);
            });
        }

        return parent::fillForUpdate($request, $model);
    }
}
