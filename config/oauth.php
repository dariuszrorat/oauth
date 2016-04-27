<?php

defined('SYSPATH') or die('No direct access allowed.');

/* Facebook
  Permissions list: https://developers.facebook.com/docs/reference/api/permissions/
  Facebook Query Language: http://developers.facebook.com/docs/reference/fql/
 */

return array(
    'facebook' => array(
        'driver' => 'facebook',
        'app_id' => 'xxxxxxxxxxx',
        'app_secret' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'persistent_data_handler' => 'session',
        'default_graph_version' => 'v2.3', 
        'default_access_token' => NULL,
        'cookie' => true,
        'callback_url' => URL::site('facebook/callback', true),
        'login_redirect_url' => URL::site('/', true),
        'logout_redirect_url' => URL::site('/', true),
        'permissions' => array('email'),
        'scope' => 'email',
        'display' => 'page',
        'fields' => 'id,name,email',
        'session_key' => 'oauth_user',
        'session_type' => Session::$default,
        'lifetime'     => 1209600,
        'ignore_errors' => false,
    ),
);
