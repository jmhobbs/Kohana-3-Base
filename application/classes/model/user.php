<?php
	class Model_User extends Model_Auth_User {
	
		public function validate_create ( &$array ) {
			// Initialise the validation library and setup some rules
			$array = Validate::factory( $array )
							->rules( 'password', $this->_rules['password'] )
							->rules( 'username', $this->_rules['username'] )
							->rules( 'email', $this->_rules['email'] )
							->rules( 'password_confirm', $this->_rules['password_confirm'] )
							->filter( 'username', 'trim' )
							->filter( 'email', 'trim' )
							->filter( 'password', 'trim' )
							->filter( 'password_confirm', 'trim' );
	
			#Executes username callbacks defined in parent
			foreach( $this->_callbacks['username'] as $callback ) {
				$array->callback( 'username', array( $this, $callback ) );
			}
	
			#Executes email callbacks defined in parent
			foreach( $this->_callbacks['email'] as $callback ){
				$array->callback( 'email', array( $this, $callback ) );
			}
	
			return $array;
		} // Model_User::validate_create

	} // Model_User