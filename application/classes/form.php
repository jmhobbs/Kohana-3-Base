<?php defined('SYSPATH') or die('No direct script access.');

class Form extends Kohana_Form {

	public static function error ( $name, $errors ) {
		if( ! isset( $errors[$name] ) ) { return ''; }
		return '<div class="form-error">' . $errors[$name] . '</div>';
	}

}
