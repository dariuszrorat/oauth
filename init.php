<?php

Route::set('facebook/callback', 'facebook/callback')
->defaults(array(
	'directory' => 'facebook',
	'controller' => 'callback',
	'action' => 'index'
));

/**
 * Optionally set other route or comment this if not needed
 */

Route::set('steam/callback', 'steam/callback')
->defaults(array(
	'directory' => 'steam',
	'controller' => 'callback',
	'action' => 'index'
));
