# OAuth Kohana module

This module is used to perform Facebook and Steam authentication.

WARNING: The steam driver is not tested yet.

## Requirements

You must install Facebook SDK on vendor directory to auth with Facebook.
Install steamauth to auth with Steam

## Example Auth Controller:

```php
// application/classes/Controller/Facebook/Auth.php

class Controller_Facebook_Auth extends Controller
{
    public function action_login()
    {
        $login_url = OAuth::instance('facebook')->login_url();
        HTTP::redirect($login_url);
    }
}
```

## Callback Controller:

```php
// application/classes/Controller/Facebook/Callback.php

class Controller_Facebook_Callback extends Controller
{

    public function action_index()
    {
        $oauth = OAuth::instance('facebook');
        $fb_user = $oauth->get_user();
        // do something
    }
}
```

## Config

oauth.php

```php
return array(
    'facebook' => array(
        'driver' => 'facebook',
        'app_id' => 'xxxxxxxxxxx',
        'app_secret' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'default_graph_version' => 'v2.7',
        'enable_beta_mode' => false,
        'http_client_handler' => NULL,
        'persistent_data_handler' => 'session',
        'pseudo_random_string_generator' => null,
        'url_detection_handler' => null,
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
    'steam' => array(
        'driver' => 'steam',
        'api_key' => '',
        'domain_name' => 'my-host.example.org',
        'callback_url' => URL::site('steam/callback', true),
        'login_redirect_url' => URL::site('/', true),
        'logout_redirect_url' => URL::site('/', true),
        'session_key' => 'oauth_user',
        'session_type' => Session::$default,
        'lifetime'     => 0,
    ),
);
```

