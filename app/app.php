<?php

/**
 * Adopted from http://stackoverflow.com/questions/5845732/clean-urls-and-database
 * @author Scott Dover <sdover102@me.com>
 * @param str
 * @return str
 */
function slug($string) {
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}

class App {
	public static $inst; # Holds the string instance
	public $db = NULL; # Holds DB instance
	public $data = NULL; # Holds generic data
	
	/**
	 * App Constructor. Should only be called by inst() function
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	private function __construct() {
		ORM::configure('sqlite:'.APP_DIR.'database.sqlite');
		$this->db = ORM::get_db();
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
	public function render($file, $as_string = FALSE) {
		/* Make data object visible to view files */
		if (is_array($this->data) && count($this->data))
			foreach ($this->data as $k=>$v) 
				$$k = $v;
		
		/* Include --> String */
		ob_start();
		include 'views/' . $file . '.php';
		$yield = ob_get_clean();
		
		if ($as_string) return $yield;
		echo $yield;
	}
	
	/**
	 * Responsible for displaying page data
	 * @author Scott Dover <sdover102@me.com>
	 * @param str
	 * @return void
	 */
	public function index($page = NULL) {	
		/* Load up navigation items */
		$this->data['nav_items'] = ORM::for_table('navigation')->find_many();
		
		/* Load up specified page...or front page */
		$this->data['page'] = $page ? 
			ORM::for_table('page')->where('url_title', $page)->find_one() : 
			ORM::for_table('page')->find_one();
		
		$this->data['sidebar'] = $this->render('sidebar', TRUE);
		$this->data['yield'] = $this->render('index', TRUE);
		$this->render('layout');
	}
	
	/**
	 * This processes a single page, turning markdown documents
	 * to pages and navigation items
	 * @author Scott Dover <sdover102@me.com>
	 * @param str
	 * @return void
	 */
	public function process_page($file_name) {
		$file = file_get_contents($file_name);
		/* 
		  Create root relative media path & Convert {MEDIA_DIR} to 
		  point to media directory 
		*/
		$media = str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_DIR.'media/');
		$file = str_replace('{MEDIA_DIR}', $media, $file);
		
		/* Convert file to markdown */
		$file = Markdown($file); 
		
		
		/* Find the document title */
		$html = str_get_html($file);
		$p['title'] = $html->find('h1', 0)->plaintext;
		$p['content'] = $file;
		
		/* Create the new page */
		$page = ORM::for_table('page')->create();
		$page->title = $p['title'];
		$page->content = $p['content'];
		$page->url_title = slug($p['title']);
		$page->save();
		
		/* Create the new navigation item */
		$first_nav = ORM::for_table('navigation')->create();
		$first_nav->name = $p['title'];
		$first_nav->parent_id = 0;
		$first_nav->level = 1;
		$first_nav->href = '/'.$page->url_title;
		$first_nav->save();

		
		$nav_items = array();
		$curr_item = NULL;
		
		$parent_ids = array();
		$parent_ids[1] = $first_nav->id;
		/* Traverse the DOM and find everything under h1 */
		foreach ($html->find('h2,h3,h4,h5,h6') as $item) {
			/* Convert h2 --> 2 */
			$number = intval(str_replace('h','',$item->tag));
			$nav = ORM::for_table('navigation')->create();
			$nav->name = $item->plaintext;
			
			/* Associate h# with parent h# */
			$pindex = $number - 1;
			$nav->parent_id = isset($parent_ids[$pindex]) ? $parent_ids[$pindex] : 0;
			$nav->level = $number;
			
			/* Use hash tags for anything that isn't an h1 */
			$nav->href = '/'.$page->url_title.'#'.slug($nav->name);
			$nav->save();
			
			/* Save the last created parent */
			$parent_ids[$number] = $nav->id;
		}
	}
	
	/**
	 * This re-indexes the site by destroying all pages, navigation and
	 * rebuilding them
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	public function rebuild_pages() {
		
		/* Include what is needed to get the pages "rebuilt" */
		include 'markdown.php'; # Used for doc conversion
		include 'simple_html_dom.php'; # Used for doc manipulation / nav extraction
		
		/* Delete everything from the database */
		$this->db->exec("DELETE FROM navigation");
		$this->db->exec("DELETE FROM page");
		
		$dir = ROOT_DIR.'docs/';
		
		/* Build all pages in the docs directory */
		$handle = opendir($dir); 
		while (false !== ($file = readdir($handle))){ 
			$ext = end(explode('.',$file));
			/* Obviously only process markdown files */
			if ($ext == 'md' || $ext == 'mdown')
				$this->process_page($dir.$file);
		}
		$this->index();
	}
	
	/**
	 * Responsible for login
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	public function login() {
		
		/* If the user has entered the password, check it */
		if (!empty($_POST)) {
			if ($_POST['password'] == PASSWORD)
				$_SESSION['logged'] = PASSWORD;
			else
				die('Password incorrect.');
			$this->index();
			return;
		}
		
		/* Load up the login screen */
		$this->data['yield'] = $this->render('login', TRUE);
		$this->render('layout');
	}
	
	
	/**
	 * Simply interprets the path
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	public function interpret_paths() {
		/* If password is needed, ask user for it before continuing */
		session_start();
		if (USE_PASSWORD_FOR_ACCESS) {
			if (! (isset($_SESSION['logged']) && $_SESSION['logged'] === PASSWORD)) {
				$this->login();
				return;
			}
		}
		
		if (isset($_SERVER['argv'][0])) {
			/* Simplifying this to "cure" an issue...Real fix coming */
			$arg = end( explode("/", $_SERVER['argv'][0]) );						
			/* Check if pages need to be rebuilt, or display specified page */
			if ($arg == 'rebuild-pages') {
				$this->rebuild_pages();
			} else {
				$this->index($arg);
			}
		} else { $this->index(); }
	}
	

	/**
	 * Simple function to jumpstart app
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	public static function run() {
		$obj = self::inst();
		$obj->interpret_paths();
	}
}