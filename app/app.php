<?php


class App {
	public static $inst;
	private function __construct() {
		
	}
	
	public function view($page = NULL) {
		echo "The index page";
	}
	
	public function interpret_paths() {
		if (isset($_SERVER['argv'][0])) {
			$paths = explode("/", $_SERVER['argv'][0]);
			array_shift($paths);
			print_r($paths);
		} else { $this->view(); }
	}
	
	public static function inst() {
		if ( ! self::$inst ) self::$inst = new self;
		return self::$inst;
	}	
	
	public static function run() {
		$obj = self::inst();
		$obj->interpret_paths();
	}
}