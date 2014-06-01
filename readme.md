RenamedClassLoader
==================

[![Build Status](https://travis-ci.org/icaine/RenamedClassLoader.svg?branch=master)](https://travis-ci.org/icaine/RenamedClassLoader)

Simple loader for loading old/renamed classes with `onClassLoaded` event useful e.g. for logging or noticing that old class name is used. The loader uses `class_alias()` function to alias new class name with the old one.

Composer
--------
require: `"icaine/renamed-class-loader": "~1.0"`

Usage
-----
```php
//registering classes
$loader = new iCaine\RenamedClassLoader([
    'Old\\Class\\Name' => 'New\\Class\\Name'
]);

//or like this
$loader->registerClass('Old\\Class\\Name', 'New\\Class\\Name');

//or this way
$loader->registerClasses([
    'Old\\Class\\Name' => 'New\\Class\\Name'
]);

//we can register a callback(s) when the loader successfully loads old class
$loader->onClassLoaded[] = function($oldName, $newName) {
    trigger_error("Old class name used: '$oldName'. Use new name '$newName' instead.", E_USER_DEPRECATED);
};

//now lets register the loader (uses spl_autoload_register function)
$loader->register();
```

> **Note:** `$loader->register()` should be called after your loader is registered because *RenamedClassLoader* uses `class_alias()` function that will try to load new class name first and then alias it with the old name.
