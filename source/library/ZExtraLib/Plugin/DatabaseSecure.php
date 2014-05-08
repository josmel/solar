<?php

class ZExtraLib_Plugin_DatabaseSecure
{

    private $_data;
    protected $_options;
    protected $_debug;
//    public function __destruct()
//    {
//	@$this->_data['default']->closeConnection();
//	@$this->_data['query']->closeConnection();
//	@$this->_data['process']->closeConnection();
//	@$this->_data['auth']->closeConnection();
//
//    } 
    
    function __construct($options)
    {
        $this->_data = array();
        $this->_options = $options;
        
        $this->_data['default'] = $options['defaultDb'];
        $this->assignDebug($this->_data['default']);
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->_data)) {
            $db = $this->_options['defaultDb'];
            $adapter = substr(get_class($db), 16);
            $optionsDb = $db->getConfig();
            $optionsDb['username'] = $this->_options['user'][$name]['username'];
            $optionsDb['password'] = $this->_options['user'][$name]['password'];
            $config = new Zend_Config($optionsDb);
            $this->_data[$name] = Zend_Db::factory($adapter, $config);
            $this->assignDebug($this->_data[$name]);
        }
        return $this->_data[$name];
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    function assignDebug($db)
    {
         if (in_array(APPLICATION_ENV, array('development', 'local'))){ //development
              if (!isset($this->_debug)) {
                  $this->_debug = new Zend_Db_Profiler_Firebug();
                  $this->_debug->setEnabled(true);
              }
              $db->setProfiler($this->_debug);
         }
    }

}