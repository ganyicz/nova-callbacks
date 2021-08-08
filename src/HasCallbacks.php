<?php

namespace Ganyicz\NovaCallbacks;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * @method static beforeSave(NovaRequest $request, Model $model): void
 * @method static afterSave(NovaRequest $request, Model $model): void
 * @method static beforeCreate(NovaRequest $request, Model $model): void
 * @method static afterCreate(NovaRequest $request, Model $model): void
 * @method static beforeUpdate(NovaRequest $request, Model $model): void
 * @method static afterUpdate(NovaRequest $request, Model $model): void
 */
trait HasCallbacks
{
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
