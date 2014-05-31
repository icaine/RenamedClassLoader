<?php

namespace iCaine;

/**
 * Simple loader for loading renamed classes with onClassLoaded event
 * useful e.g. for logging or noticing that old class name is used.
 */
class RenamedClassLoader {

	/** @var array of callbacks($oldName, $newName) */
	public $onClassLoaded = array();

	/** @var array */
	protected $classes = array();

	/**
	 * @param array $classes [oldName => newName]
	 */
	function __construct(array $classes = array()) {
		if ($classes) {
			$this->registerClasses($classes);
		}
	}

	/**
	 * Register via spl_autoload_register
	 */
	public function register() {
		spl_autoload_register(array($this, 'tryLoad'), true);
	}

	/**
	 * Register a class
	 * @param string $oldName
	 * @param string $newName
	 */
	public function registerClass($oldName, $newName) {
		$this->classes[$oldName] = $newName;
	}

	/**
	 * @param array $classes [oldName => newName]
	 */
	public function registerClasses($classes) {
		$this->classes += $classes;
	}

	protected function tryLoad($class) {
		if (array_key_exists($class, $this->classes)) {
			class_alias($this->classes[$class], $class, true);
			$this->fireOnClassLoaded($class, $this->classes[$class]);
		}
	}

	protected function fireOnClassLoaded($oldName, $newName) {
		foreach ($this->onClassLoaded as $callback) {
			if (is_callable($callback)) {
				$callback($oldName, $newName);
			} else {
				throw new \Exception('One of callbacks is not callable');
			}
		}
	}

}
