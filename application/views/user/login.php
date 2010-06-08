<?php
	echo Form::open();
	echo Form::label( 'username', 'Username' );
	echo Form::input( 'username' );
	echo @Form::error( 'username', $errors );
	echo '<br/>';
	echo Form::label( 'password', 'Password' );
	echo Form::password( 'password' );
	echo @Form::error( 'password', $errors );
	echo '<br/>';
	echo Form::submit( 'submit', 'Log In' );
	echo Form::close();

