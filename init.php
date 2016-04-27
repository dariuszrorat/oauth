<?php

Route::set('facebook/callback', 'facebook/callback')
->defaults(array(
	'directory' => 'facebook',
	'controller' => 'callback',
	'action' => 'index'
));
