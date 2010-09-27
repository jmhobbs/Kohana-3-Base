<?php
	defined( 'SYSPATH' ) or die( 'No direct script access.' );
	
	/**
	 * Adds flash message capabilities to your Kohana app.
	 */
	class Flash {
		
		/**
		 * Set a flash message.
		 * Note that TTL > 1 is not yet supported. Flash::get needs to fix that.
		 *
		 * @param message The message.
		 * @param key Optional. Allows you to partition different message types.
		 * @param ttl Optional. Time to live, i.e. page views it will show up.
		 */
		public static function set ( $message, $key='flash', $ttl=1 ) {
			++$ttl; // Silently bump ttl up so we don't discard it on before actually using it.
			$messages = Session::instance()->get( 'ko3_flash', false );
			if( false === $messages ) { $messages = array(); }
			if( ! isset( $messages[$key] ) ) { $messages[$key] = array(); }
			$messages[$key][] = array( 'message' => $message, 'ttl' => $ttl );
			Session::instance()->set( 'ko3_flash', $messages );
		}
		
		/**
		 * Get a flash message from the system.
		 *
		 * @param key Optional. What message type to pull from.
		 */
		public static function get ( $key='flash' ) {
			$messages = Session::instance()->get( 'ko3_flash', false );
			if( isset( $messages[$key] ) and 0 != count( $messages[$key] ) ) {
				// Get the message
				$message = $messages[$key][0]['message'];
				// See if that message needs to die
				if( --$messages[$key][0]['ttl'] <= 0 )
					array_shift( $messages[$key] );
				// Store it all back
				Session::instance()->set( 'ko3_flash', $messages );
				return $message;
			}
			return false;
		}
		
		/**
		 * Update the flash message data, pruning dead entries.
		 *
		 * This MUST be placed correctly into your bootstrap.php
		 *
		 * @code
		 *   $request = Request::instance();
		 *   $request->execute();
		 *   $request->send_headers();
		 *   Flash::update();
		 *   echo $request->response;
		 */
		public static function update () {
			$messages = Session::instance()->get( 'ko3_flash', false );
			if( false === $messages ) { return; }
			// Decrement ttl and clear out any dead messages
			foreach( $messages as $key => $queue ) {
				for( $i = 0; $i < count( $queue ); ++$i ) {
					if( --$messages[$key][$i]['ttl'] <= 0 )
						unset( $messages[$key][$i] );
				}
				// Fix any index errors we have
				$messages[$key] = array_values( $messages[$key] );
			}
			Session::instance()->set( 'ko3_flash', $messages );
		}

	}