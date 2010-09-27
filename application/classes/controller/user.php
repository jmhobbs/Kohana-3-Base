<?php
	defined('SYSPATH') or die('No direct script access.');

	class Controller_User extends Controller_Site {

		public function action_index () {}

		/**
		 * Log a user into the system.
		 **/
		public function action_login () {
			if( Auth::instance()->logged_in() != 0 ) { Request::instance()->redirect( 'user/' ); }

			if( $_POST ) {
				$user = ORM::factory('user');
				$status = $user->login( $_POST );
				if( $status ) {
					Request::instance()->redirect( 'user/' );
				}
				else {
					$this->template->body->errors = $_POST->errors( 'login' );
				}
			}
			
		} // Controller_User::action_login

		/**
		 * Log out the current user.
		 **/
		public function action_logout () {
			Auth::instance()->logout();
			Request::instance()->redirect( 'login' );
		} // Controller_User::action_logout

		/**
		 * Create a new user.
		 **/
		public function action_signup () {
			if( Auth::instance()->logged_in() != 0 ) { Request::instance()->redirect( 'user/' ); }
		}

		/**
		 * Add a new admin user to the system via the command line.
		 * 
		 * Example Usage:
		 * php5 index.php --uri="/user/add_admin/jmhobbs/password"
		 *
		 * @param username The new admin username.
		 * @param password The new admin password.
		 */
		public function action_add_admin ( $username, $password ) {
			if( 'cli' != PHP_SAPI ) {
				$this->template->body = View::factory( 'error/404' );
				$this->request->status = 404;
				return;
			}
			
			$user = ORM::factory( 'user' );
			$user->email = 'stub@example.com';
			$user->username = $username;
			$user->password = $password;
			$user->save();

			$login_role = new Model_Role( array( 'name' => 'login' ) );
			$user->add( 'roles', $login_role );
			
			$admin_role = new Model_Role( array( 'name' => 'admin' ) );
			$user->add( 'roles', $admin_role );
 
			die( "Created Admin User $username\n\n" );
		} // Controller_User::action_add_admin

	}