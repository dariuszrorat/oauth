<?php

Route::set('facebook/callback', 'facebook/callback')
->defaults(array(
	'controller' => 'facebook',
	'action' => 'callback'
));

/**
 * Optionally set other route or comment this if not needed
 */

Route::set('steam/callback', 'steam/callback')
->defaults(array(
	'controller' => 'steam',
	'action' => 'callback'
));
