<?php

defined('SYSPATH') OR die('No direct access allowed.');

include Kohana::find_file('vendor', 'Facebook/autoload');

class Kohana_OAuth_Facebook extends OAuth
{
    protected $_ignore_errors = false;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->_ignore_errors = $config['ignore_errors'];

        // Only for Facebook SDK
        if (!session_id())
        {
            session_start();
        }

        $this->_client = new Facebook\Facebook(array(
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => $config['default_graph_version'],
            'enable_beta_mode' => $config['enable_beta_mode'],
            'http_client_handler' => $config['http_client_handler'],
            'persistent_data_handler' => $config['persistent_data_handler'],
            'pseudo_random_string_generator' => $config['pseudo_random_string_generator'],
            'url_detection_handler' => $config['url_detection_handler']
        ));
    }

    public function access_token()
    {
        try
        {
            $helper = $this->_client->getRedirectLoginHelper();
            $accessToken = $helper->getAccessToken();
            return $accessToken ? $accessToken->getValue() : NULL;
        } catch (Facebook\Exceptions\FacebookResponseException $e)
        {
            if ($this->_ignore_errors === true)
            {
                return null;
            }
            throw $e;
        } catch (Facebook\Exceptions\FacebookSDKException $e)
        {
            if ($this->_ignore_errors === true)
            {
                return null;
            }
            throw $e;
        }
    }

    public function logged_in()
    {
        return ($this->get_user() !== null);
    }

    public function get_user()
    {
        try
        {
            if (($user = $this->_session->get($this->_session_key, null)) === null)
            {
                $access_token = $this->access_token();
                if ($access_token !== NULL)
                {
                    $fields = $this->_config['fields'];
                    $response = $this->_client->get('/me?fields=' . $fields, $access_token);
                    $body = $response->getBody();
                    $user = json_decode($body);
                    $this->_session->set($this->_session_key, $user);
                }
            }
            return $user;
        } catch (Facebook\Exceptions\FacebookResponseException $e)
        {
            if ($this->_ignore_errors === true)
            {
                return null;
            }
            throw $e;
        } catch (Facebook\Exceptions\FacebookSDKException $e)
        {
            if ($this->_ignore_errors === true)
            {
                return null;
            }
            throw $e;
        }
    }

    public function login_url()
    {
        try
        {
            $helper = $this->_client->getRedirectLoginHelper();
            $permissions = $this->_config['permissions'];
            return $helper->getLoginUrl($this->_config['callback_url'], $permissions);
        } catch (Facebook\Exceptions\FacebookResponseException $e)
        {
            if ($this->_ignore_errors === true)
            {
                return null;
            }
            throw $e;
        } catch (Facebook\Exceptions\FacebookSDKException $e)
        {
            if ($this->_ignore_errors === true)
            {
                return null;
            }
            throw $e;
        }
    }

    public function logout_url()
    {
        try
        {
            $access_token = $this->access_token();
            $next = $this->_config['logout_redirect_url'];
            $helper = $this->_client->getRedirectLoginHelper();
            return $helper->getLogoutUrl($access_token, $next);
        } catch (Facebook\Exceptions\FacebookResponseException $e)
        {
            if ($this->_ignore_errors === true)
            {
                return null;
            }
            throw $e;
        } catch (Facebook\Exceptions\FacebookSDKException $e)
        {
            if ($this->_ignore_errors === true)
            {
                return null;
            }
            throw $e;
        }
    }
    
    public function logout()
    {
        $this->_session->delete($this->_session_key);
    }

}
