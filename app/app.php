<?php


class App {
	public static $inst; # Holds the string instance
	public $data = NULL; # Holds generic data
	
	/**
	 * App Constructor. Should only be called by inst() function
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	private function __construct() {
		$this->data = array();
	}
	
	/**
	 * Creates a new instance (or returns a created one) of App
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return object
	 */
	public static function inst() {
		if ( ! self::$inst ) self::$inst = new self;
		return self::$inst;
	}
	
	/**
	 * Renders a given file, with the option
	 * to return data as string
	 * @author Scott Dover <sdover102@me.com>
	 * @param $file: name of extensionless file (assumes .php)
	 *        $as_string: Whether or not to return as string
	 * @return str | void
	 */
	public static function render($file, $data = array(), $as_string = FALSE) {
		$obj = self::inst();
		$data = array_merge($data, $obj->data);
		/* Make data object visible to view files */
		if (is_array($data) && count($data))
			foreach ($data as $k=>$v) 
				$$k = $v;
		
		/* Include --> String */
		ob_start();
		include 'views/' . $file . '.php';
		$yield = ob_get_clean();

		include 'views/layout.php';

		// if ($as_string) return $yield;
		// echo $yield;
	}

	/**
	 * Sets a new variable to be included in the render
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	public static function set($key, $value) {
		$obj = self::inst();
		$obj->data[$key] = $value;
	}
	
	/**
	 * Simple function to jumpstart app
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	public static function run() {
		$obj = self::inst();
		Router::interpret_paths();
	}
}