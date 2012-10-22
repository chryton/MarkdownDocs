<?php

class Data_manager {

	var $folder = null;
	var $doc_title = null;
	var $password = null;


	public function __construct($folder) {
		$this->folder = $folder;
		ORM::configure('sqlite:'.APP_DIR.'database.sqlite');
		$this->db = ORM::get_db();
	}

	/**
	 * Checks if a user's password is correct and redirects to document
	 * set front page
	 * @author Scott Dover <sdover102@me.com>
	 * @param str
	 * @return void
	 */
	public function check_password_and_redirect($password) {
		if ($password == $this->password) {
			if (session_id() == '') session_start();
			$session_var = 'user_logged_' . $this->folder;
			$_SESSION[$session_var] = TRUE;	
			safe_redirect($this->folder == 'default' ? WEB_ROOT : WEB_ROOT . '/' . $this->folder);
		}
	}

	/**
	 * This processes a markdown document and associates
	 * it with a specified folder
	 * @author Scott Dover <sdover102@me.com>
	 * @param str, int
	 * @return void
	 */
	public function process_page($file_name, $folder_id) {
		$file = file_get_contents($file_name);
		/* 
		  Create root relative media path & Convert {MEDIA_DIR} to 
		  point to media directory 
		*/
		$media = str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_DIR .  'docs/' . $this->folder . '/media/');
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
		$page->folder_id = $folder_id;
		$page->save();
		
		/* Create the new navigation item */
		$first_nav = ORM::for_table('navigation')->create();
		$first_nav->name = $p['title'];
		$first_nav->parent_id = 0;
		$first_nav->folder_id = $folder_id;
		$first_nav->level = 1;
		$first_nav->href = '/' . $this->folder . '/page/'.$page->url_title;
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
			
			$nav->folder_id = $folder_id;
			/* Use hash tags for anything that isn't an h1 */
			$nav->href = '/' . $this->folder . '/page/' .$page->url_title.'#'.slug($nav->name);
			$nav->save();
			
			/* Save the last created parent */
			$parent_ids[$number] = $nav->id;
		}
	}
	
	/**
	 * This re-indexes the site by destroying all pages, navigation and
	 * rebuilding them (only for a specific folder)
	 * @author Scott Dover <sdover102@me.com>
	 * @param int
	 * @return void
	 */
	public function rebuild_pages($folder_id) {
		/* Include what is needed to get the pages "rebuilt" */
		
		/* Delete everything from the database */
		$this->db->exec("DELETE FROM navigation WHERE folder_id=$folder_id");
		$this->db->exec("DELETE FROM page WHERE folder_id=$folder_id");
		
		$dir = ROOT_DIR.'docs/'.$this->folder.'/';
		
		/* Build all pages in the docs directory */
		$handle = opendir($dir); 
		while (false !== ($file = readdir($handle))){ 
			if (!is_file($dir.$file)) continue;
			$file_parts = explode('.',$file);
			$ext = end($file_parts);
			/* Obviously only process markdown files */
			if ($ext == 'md' || $ext == 'mdown')
				$this->process_page($dir.$file, $folder_id);
		}
	}

	/**
	 * This checks for changes within a folder and determines
	 * if the pages / navigation need to be re-generated
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	public function check_for_changes_and_update_db() {
		/* Get directory encoding file */
		$directory = ROOT_DIR . 'docs/' . $this->folder;
		$files = @scandir($directory);
		if (!$files) die("Directory Not Found");

		/* Generate checksum of directory */
		$check_string = '';
		foreach ($files as $file) {
			if (is_file($directory . '/' .$file))
				$check_string .= md5_file($directory . '/' .$file);
		}

		/* Find the associated folder */
		$folder = ORM::for_table('folder')->where('title', $this->folder)->find_one();
		$this->folder_id = $folder->id;
		if (!$folder) {
			/* If no folder, create it and generate pages */
			$folder = ORM::for_table('folder')->create();
			$folder->title = $this->folder;
			$folder->checksum = $check_string;
			$folder->save();
			$this->rebuild_pages($folder->id);
		} else {
			/* If folder exists but checksum has changed, update folder contents and checksum */
			if ($folder->checksum != $check_string) {
				$this->rebuild_pages($folder->id);
				$folder->checksum = $check_string;
				$folder->save();
			}
		}
	}

	/**
	 * Gets a page 
	 * @author Scott Dover <sdover102@me.com>
	 * @param url_title
	 * @return void
	 */
	public function get_page($page = NULL) {
		return $page ? 
			ORM::for_table('page')->where('url_title', $page)->where('folder_id', $this->folder_id)->find_one() : 
			ORM::for_table('page')->where('folder_id', $this->folder_id)->find_one();
	}

	/**
	 * Gets the nav items in the current folder
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	public function get_nav_items() {
		return ORM::for_table('navigation')->where('folder_id', $this->folder_id)->find_many();
	}

	/**
	 * Loads up the configuration for a given folder and determines
	 * if the user needs to be asked for password
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	public function load_config($page = NULL) {
		$encoded_config = file_get_contents(ROOT_DIR . 'docs/' . $this->folder . '/config.json');
		$config = json_decode($encoded_config);
		if (session_id() == '') session_start();
		if (isset($config->password)) {
			$session_var = 'user_logged_' . $this->folder;

			$this->password = $config->password;
			if ((!isset($page) || (isset($page) && $page != 'login')) && (!isset($_SESSION[$session_var]) || !$_SESSION[$session_var])) {
				safe_redirect(WEB_ROOT . '/'. $this->folder .'/login');	
			} 
		}	
		/* Set the document title */
		App::set('doc_title', $config->title);
	}
}	