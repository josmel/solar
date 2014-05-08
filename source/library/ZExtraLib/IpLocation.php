<?php

//require_once  _PS_ROOT_DIR_.'/library/Zend/Http/Client.php';

//require_once  _PS_ROOT_DIR_.'/library/Zend/XmlRpc/Client.php';
//require_once  _PS_ROOT_DIR_.'/library/Zend/Json.php';


//require_once  'XmlRpc/Client.php';
//require_once  'Json/';


class ZExtraLib_IpLocation
{
    protected  $ip;
    
    protected $key='8522614bf1f82125ccc068276a07ad1b344ef2f10f41c1d45a8599de322aad1b';
    public $country;
    public $region;
    public $city;
    private $client="http://api.ipinfodb.com/v3/ip-city/";

    public function __construct($ip, $key='8522614bf1f82125ccc068276a07ad1b344ef2f10f41c1d45a8599de322aad1b')
    {
        $this->ip=$ip;
        $this->key=$key;
    }
    /**
    * Geolocation API access
    *
    * @param    string  $ip         IP address to query
    * @param    string  $format     output format of response
    *
    * @return   string  XML, JSON or CSV string
    */
        
    function get_ip_location() {
        
        /* Set allowed output formats */
        $client = new Zend_Http_Client($this->client);
        
        $client->setParameterGet(array(
                'key' => $this->key,
                'ip' => $this->ip,
            ));
        
        $response = $client->request();
        if ($response->isSuccessful() ) {//Zend_Json::decode(
                $phpNative = $response->getBody();
                $phpNative =explode(";",$phpNative);
                if ($phpNative[0]=='OK') {
                    $this->abrv = $phpNative[3];
                    $this->country= $phpNative[4];
                    $this->region=$phpNative[5];
                    return 1;
                }
            }
            return 0;
    }
}
