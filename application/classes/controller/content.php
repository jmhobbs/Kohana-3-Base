<?php
	defined('SYSPATH') or die('No direct script access.');

	class Controller_Content extends Controller_Site {

		public function action_index ( $page ) {
		
			// Clean and normalize the page name
			$page = preg_replace( '/[^0-9a-z\-_]/', '', strtolower( $page ) );
		
			try {
				$this->template->body = View::factory( 'content/' . $page );
				// Call the preparation method, if it exists.
				$method = "prepare_$page";
				if( method_exists( $this, $method ) ) { $this->$method(); }
			}
			catch ( Exception $e ) {
				$this->template->body = View::factory( 'error/404' );
				$this->request->status = 404;
			}
			
		} // Controller_Content::action_index

	}