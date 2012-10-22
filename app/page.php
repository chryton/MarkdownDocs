<?php

class Page {

	var $dm = NULL;
	var $folder = NULL;
	var $page = NULL;
	
	public function __construct($folder, $page = NULL) {
		$this->folder = $folder;
		$this->page = $page;
		$this->dm = new Data_manager($this->folder);
		$this->dm->check_for_changes_and_update_db();
		$this->load_page();
	}

	/* Loads login page */
	public function load_login() {
		if (!empty($_POST)) {
			$password = $_POST['password'];
			$this->dm->check_password_and_redirect($password);
		}
		App::render('login', array('path' => WEB_ROOT . '/' . $this->folder . '/login'));	
	}	

	/* Loads regular pages */
	public function load_page() {
		$this->dm->load_config($this->page);
		if ($this->page == 'login') {
			return $this->load_login();	
		}
		/* Set the folder and items needed for render */
		App::set('folder', $this->folder);
		App::set('nav_items', $this->dm->get_nav_items());
		App::set('page', $this->dm->get_page($this->page));
		App::render('index');
	}	
}