<?php

namespace Ganyicz\NovaCallbacks;

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
 * @method static beforeAttach(NovaRequest $request, Model $model, Pivot $pivot): void
 * @method static afterAttach(NovaRequest $request, Model $model, Pivot $pivot): void
 */
trait HasCallbacks
{
    public static function fillPivot(NovaRequest $request, $model, $pivot)
    {
        if (method_exists(static::class, 'beforeAttach')) {
            static::beforeAttach($request, $model, $pivot);
        }

        $response = parent::fillPivot($request, $model, $pivot);

        if (method_exists(static::class, 'afterAttach')) {
            static::afterAttach($request, $model, $pivot);
        }

        return $response;
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
        
        return static::fillFields(
            $request, $model,
            (new static($model))->creationFieldsWithoutReadonly($request)
        );
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

        return static::fillFields(
            $request, $model,
            (new static($model))->updateFieldsWithoutReadonly($request)
        );
    }
}
