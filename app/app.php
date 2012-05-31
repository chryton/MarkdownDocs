<?php

class App {
	public static $inst;
	public $db = NULL;
	public $data = NULL;
	
	private function __construct() {
		ORM::configure('sqlite:'.APP_DIR.'database.sqlite');
		$this->db = ORM::get_db();
	}
	
	public function render($file, $as_string = FALSE) {
		foreach ($this->data as $k=>$v) 
			$$k = $v;
		ob_start();
		include 'views/' . $file . '.php';
		$yield = ob_get_clean();
		if ($as_string) return $yield;
		echo $yield;
	}
	
	public function index($page = NULL) {	
		# Load up the navigation items
		# Load up the starting page
		$this->data['nav_items'] = ORM::for_table('navigation')->find_many();
		$this->data['page'] = ORM::for_table('page')->find_one();
		$this->data['sidebar'] = $this->render('sidebar', TRUE);
		$this->data['index'] = $this->render('index', TRUE);
		$this->render('layout');
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
		$p['title'] = $html->find('h1', 0)->plaintext;
		$p['content'] = $file;
		#Save the first nav item
		$first_nav = ORM::for_table('navigation')->create();
		$first_nav->name = $p['title'];
		$first_nav->parent_id = 0;
		$first_nav->level = 1;
		$first_nav->save();
		# Save the page
		$page = ORM::for_table('page')->create();
		$page->title = $p['title'];
		$page->content = $p['content'];
		$page->save();
		
		$nav_items = array();
		$curr_item = NULL;
		# Traverse the dom tree and pull in each nested category
		$parent_ids = array();
		$parent_ids[1] = $first_nav->id;

		foreach ($html->find('h2,h3,h4,h5,h6') as $item) {
			# Traverse through directory and find each child and associate it with a parent
			$number = intval(str_replace('h','',$item->tag));
			$nav = ORM::for_table('navigation')->create();
			$nav->name = $item->plaintext;
			$pindex = $number - 1;
			$nav->parent_id = isset($parent_ids[$pindex]) ? $parent_ids[$pindex] : 0;
			$nav->level = $number;
			$nav->save();
			$parent_ids[$number] = $nav->id;
			# Insert item
		}
	}
	
	public function rebuild_pages() {
		# Delete everything from the database
		$this->db->exec("DELETE FROM navigation");
		$this->db->exec("DELETE FROM page");
		
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
			} else {
				$this->index($arg);
			}
		} else { $this->index(); }
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