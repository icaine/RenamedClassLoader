<?php

/**
 * Test: iCaine\RenamedClassLoader
 */
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

spl_autoload_register(function ($class) {
	$filename = __DIR__ . "/$class.php";
	if (is_file($filename)) {
		include $filename;
	}
}, true);

$loader = new iCaine\RenamedClassLoader(array('OldTestClass' => 'TestClass'));
$loader->register();

$eventFired = false;
$loader->onClassLoaded[] = function ($oldName, $newName) use (&$eventFired) {
	$eventFired = true;
	Assert::equal('OldTestClass', $oldName);
	Assert::equal('TestClass', $newName);
};

Assert::true(class_exists('OldTestClass'));
Assert::true($eventFired);
