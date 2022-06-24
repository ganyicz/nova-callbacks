![Banner](https://banners.beyondco.de/Nova%20callback%20functions.png?theme=light&packageManager=composer+require&packageName=ganyicz%2Fnova-callbacks&pattern=charlieBrown&style=style_1&description=Define+after-save+callbacks+directly+inside+your+Nova+Resource.&md=1&showWatermark=0&fontSize=100px&images=reply)

# Missing Nova resource callback functions

This package adds support for defining callback functions directly inside your resource class.

```php

use Illuminate\Http\Request;
use Ganyicz\NovaCallbacks\HasCallbacks

class User extends Resource
{
    use HasCallbacks;

    public function fields(Request $request): array
    {
        return [];
    }

    public static function beforeSave(Request $request, Model $model): void
    {
      // Do something before the model is created or updated
    }

    public static function afterSave(Request $request, Model $model): void
    {
      // Do something after the model is created or updated
    }

    public static function beforeCreate(Request $request, Model $model): void
    {
      // Do something before the model is created
    }

    public static function afterCreate(Request $request, Model $model): void
    {
      // Do something after the model is created
    }

    public static function beforeUpdate(Request $request, Model $model): void
    {
      // Do something before the model is updated
    }

    public static function afterUpdate(Request $request, Model $model): void
    {
      // Do something after the model is updated
    }

    public static function beforeAttach(Request $request, Model $model, Pivot $pivot): void
    {
      // Do something before pivot field is attached to model
    }

    public static function afterAttach(Request $request, Model $model, Pivot $pivot): void
    {
      // Do something after pivot field is attached to model
    }
}
```

## Why?

Currently, if you want to do anything after your resource is saved, you have to define a model observer outside of the resource class. This just makes the code harder to find. This package is especially useful if you only need to do a simple logic after your resource is saved, as everything can be kept in one file.

To expand the possibilities, check out my other package [Nova Temporary Fields](https://github.com/ganyicz/nova-temporary-fields) which allows you to create custom fields that won't be persisted in your model and will only be available inside the callbacks.

## Installation

You can install the package via composer:

```bash
composer require ganyicz/nova-callbacks
```

## Usage

1. Apply `HasCallbacks` trait on your resource. 
2. Define one of the callback functions.

TIP: Apply the trait on your base Resource class inside your Nova folder so that the callback functions are available for you in every new resource.

## Available callbacks

`public static function beforeSave(Request $request, Model $model): void`

Called both before creating and updating the resource

`public static function afterSave(Request $request, Model $model): void`

Called both after creating and updating the resource

`public static function beforeCreate(Request $request, Model $model): void`

Called before creating a new resource

`public static function afterCreate(Request $request, Model $model): void`

Called after creating a new resource

`public static function beforeUpdate(Request $request, Model $model): void`

Called before updating an existing resource

`public static function afterUpdate(Request $request, Model $model): void`

Called after updating an existing resource

`public static function beforeAttach(NovaRequest $request, Model $model, Pivot $pivot): void`

Called before attaching resource via `BelongsToMany` field

`public static function afterAttach(NovaRequest $request, Model $model, Pivot $pivot): void`

Called after attaching resource via `BelongsToMany` field

## How does it work?

The implementation is really simple. 
Before Nova fills the model, we overwrite `fill` and `fillForUpdate` methods exposed by `Laravel\Nova\FillsFields` trait to add a closure based model event listener. After the model is saved, we simply call the callback function defined inside your resource.

## Keep in mind

As the implementation works on `saved` model event, saving the model itself inside the callback function will throw you into infinite loop. If you really need to save your model inside the callback, use `saveQuietly()` so that the event is not dispatched.

As mentioned above, the trait provided by this package overrides `fill` and `fillForUpdate` methods on your resource.
