<?php

defined('SYSPATH') OR die('No direct access allowed.');

abstract class Kohana_OAuth
{
    // OAuth default
    public static $default = 'facebook';

    /**
     * @var  array  OAuth instances
     */
    public static $instances = array();

    protected $_session_type = 'native';
    protected $_session_key = 'oauth_user';    
    protected $_session;

    protected $_client;
    
    /**
     * Singleton pattern
     *
     * @return OAuth
     */
    public static function instance($group = NULL)
    {
        // If there is no group supplied
        if ($group === NULL)
        {
            // Use the default setting
            $group = OAuth::$default;
        }

        if (isset(OAuth::$instances[$group]))
        {
            // Return the current group if initiated already
            return OAuth::$instances[$group];
        }

        $config = Kohana::$config->load('oauth');

        if (!$config->offsetExists($group))
        {
            throw new Kohana_Exception(
            'Failed to load Kohana OAuth group: :group', array(':group' => $group)
            );
        }

        $config = $config->get($group);

        // Create a new OAuth type instance
        $class = 'OAuth_' . ucfirst($config['driver']);
        OAuth::$instances[$group] = new $class($config);

        // Return the instance
        return OAuth::$instances[$group];
    }

    protected $_config = array();

    /**
     * Ensures singleton pattern is observed
     *
     * @param  array  $config  configuration
     */
    protected function __construct(array $config)
    {
        $this->config($config);

        $this->_session_type = $config['session_type'];
        $this->_session_key = $config['session_key'];
        $this->_session = Session::instance($this->_session_type);
    }

    /**
     * Getter and setter for the configuration. If no argument provided, the
     * current configuration is returned. Otherwise the configuration is set
     * to this class.
     *
     *     // Overwrite all configuration
     *     $oauth->config(array('driver' => 'facebook', '...'));
     *
     *     // Set a new configuration setting
     *     $oauth->config('extauth', array(
     *          'foo' => 'bar',
     *          '...'
     *          ));
     *
     *     // Get a configuration setting
     *     $oauth = $cache->config('servers);
     *
     * @param   mixed    key to set to array, either array or config path
     * @param   mixed    value to associate with key
     * @return  mixed
     */
    public function config($key = NULL, $value = NULL)
    {
        if ($key === NULL)
            return $this->_config;

        if (is_array($key))
        {
            $this->_config = $key;
        } else
        {
            if ($value === NULL)
                return Arr::get($this->_config, $key);

            $this->_config[$key] = $value;
        }

        return $this;
    }

    /**
     * Overload the __clone() method to prevent cloning
     *
     * @return  void

     */
    final public function __clone()
    {
        throw new Kohana_Exception('Cloning of Kohana_OAuth objects is forbidden');
    }

    abstract public function logged_in();

    abstract public function get_user();
    
    abstract public function login_url();
    
    abstract public function logout_url();

    abstract public function logout();
}

// End OAuth
