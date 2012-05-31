<?php


class App {
	public static $inst;
	private function __construct() {
		
	}
	
	public function view($page = NULL) {
		echo "The index page";
	}
	
	public function process_page($file_name) {
		$media = str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_DIR.'media/');
		$file = file_get_contents($file_name);
		#Convert {MEDIA_DIR} to point to media directory
		$file = str_replace('{MEDIA_DIR}', $media, $file);
		#Convert the document to HTML
		$file = Markdown($file); 
		#Traverse the document finding the doc title <h1> and all child titles
		$html = str_get_html($file);
		$page['title'] = $html->find('h1', 0)->plaintext;
		$page['content'] = $file;
		$nav_items = array();
		$curr_item = NULL;
		# Traverse the dom tree and pull in each nested category
		$base_item = 'h2';
		$elems = array('h2','h3','h4','h5','h6');
		foreach ($html->find('h2,h3,h4,h5,h6') as $item) {
			# Traverse through directory and find each child and associate it with a parent
			// Insert the item
			// set current_tag and parent_id
			// With each when switching from h2 to h3...maintain same parent id...but when going from h3 to h4...the new parent is h3...keep following the same parent until you see the base tag again. Then, start all over again
		}
		echo $title;
		die();
	}
	
	public function rebuild_pages() {
		$dir = ROOT_DIR.'docs/';
		
		$handle = opendir($dir); 
		while (false !== ($file = readdir($handle))){ 
			$ext = end(explode('.',$file));
			if ($ext == 'md' || $ext == 'mdown')
				$this->process_page($dir.$file);
		}
	}
	
	public function interpret_paths() {
		if (isset($_SERVER['argv'][0])) {
			$paths = explode("/", $_SERVER['argv'][0]);
			array_shift($paths);
			$arg = $paths[0];
			if ($arg == 'rebuild-pages') {
				$this->rebuild_pages();
			}
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