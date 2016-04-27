<?php

class Kohana_OAuth_Facebook extends OAuth
{

    private $_fb;
    private $_ignore_errors = false;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->_ignore_errors = $config['ignore_errors'];

        include Kohana::find_file('vendor', 'Facebook/autoload');
        // Only for Facebook SDK
        if (!session_id())
        {
            session_start();
        }

        $this->_fb = new Facebook\Facebook(array(
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => $config['default_graph_version'],
            'persistent_data_handler' => $config['persistent_data_handler']
        ));
    }

    public function access_token()
    {
        try
        {
            $helper = $this->_fb->getRedirectLoginHelper();
            $accessToken = $helper->getAccessToken();
            return $accessToken->getValue();
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
                    $response = $this->_fb->get('/me?fields=' . $this->_config['fields'], $access_token);
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
            $helper = $this->_fb->getRedirectLoginHelper();
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
            $helper = $this->_fb->getRedirectLoginHelper();
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
