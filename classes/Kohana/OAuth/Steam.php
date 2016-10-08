<?php

defined('SYSPATH') OR die('No direct access allowed.');

include Kohana::find_file('vendor', 'steamauth/openid');

class Kohana_OAuth_Steam extends OAuth
{
    protected $_client;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->_client = new LightOpenID($config['domain_name']);
        $this->_client->realm = $config['callback_url'];
        $this->_client->returnUrl = $config['callback_url'];
    }

    public function logged_in()
    {
        return ($this->get_user() !== null);
    }

    public function get_user()
    {
        if (($user = $this->_session->get($this->_session_key, null)) === null)
        {
            if($this->_client->validate())
            {
	        $id = $this->_client->identity;
	        $regex = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
	        preg_match($regex, $id, $matches);

                if (!empty($matches))
                {
                    $user = array('steamid' => $matches[1]);
                    $this->_session->set($this->_session_key, $user);
                }
	    }
        }

        return $user;
    }

    public function login_url()
    {
        if(!$openid->mode)
        {
            $this->_client->identity = 'http://steamcommunity.com/openid';
            return $this->_client->authUrl();
        }
        return '';
    }

    public function logout_url()
    {
        return '';
    }

    public function logout()
    {
        $this->_session->delete($this->_session_key);
    }

}
