# Missing Nova resource callback functions

This package adds support for defining callback functions directly inside your resource class.

```php

use Illuminate\Http\Request;
use Ganyicz\NovaCallbacks\HasCallbacks

class User extends Resource
{
    use HasCallbacks;
    
    public function fields(Request $request)
    {
        return [];
    }

    public static function afterSave(Request $request, $model)
    {
      // Do something after the model is created or updated
    }
    
    public static function afterCreate(Request $request, $model)
    {
      // Do something after the model is created
    }
    
    public static function afterUpdate(Request $request, $model)
    {
      // Do something after the model is updated
    }
}
```

## Why?

Currently, if you want to do anything after your resource is saved, you have to define a model observer outside of the resource class. This just makes the code harder to find. This package is especially useful if you only need to do a simple logic after your resource is saved, as everything can be kept in one file.

## Installation

You can install the package via composer:

```bash
composer require ganyicz/nova-callbacks
```

## Usage

1. Apply `HasCallbacks` trait on your resource. 
2. Define one of the callback functions.

## Available callbacks

`public static function afterSave(Request $request, $model)`

Called both after creating and updating the resource

`public static function afterCreate(Request $request, $model)`

Called after creating a new resource

`public static function afterUpdate(Request $request, $model)`

Called after updating an existing resource

## How does it work?

The implementation is really simple. 
Before Nova fills the model, we overwrite `fill` and `fillForUpdate` methods exposed by `Laravel\Nova\FillsFields` trait to add a closure based model event listener. After the model is saved, we simply call the callback function defined inside your resource.

## Keep in mind

As the implementation works on `saved` model event, saving the model itself inside the callback function will throw you into infinite loop. If you really need to save your model inside the callback, use `saveQuietly()` so that the event is not dispatched.

As mentioned above, the trait provided by this package overrides `fill` and `fillForUpdate` methods on your resource.
