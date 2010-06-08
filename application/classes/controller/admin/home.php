<?php
	defined('SYSPATH') or die('No direct script access.');

	class Controller_Admin_Home extends Controller_Site {

		public $auth = array( '*' => 'admin' );

		public function action_index() {
			$this->template->message = View::factory( 'welcome' );
			$this->request->status = 200;
		}
	
	}
