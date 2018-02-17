<?php
/**
 * Datamolina - Token handling Class.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2017 Vitex Software
 */

namespace Datamolino;

/**
 * Token handling Class.
 *
 * @url https://datamolino.docs.apiary.io/#reference/authorization/obtain-access-token
 */
class Token extends ApiClient
{
    /**
     * Saves obejct instace (singleton...).
     *
     * @var Shared
     */
    private static $_instance = null;

    /**
     * Toxen Expiration Timestamp
     * @var int 
     */
    public $expire = null;

    /**
     * Data Molino Username holder
     * @var string 
     */
    public $username = null;

    /**
     * Data Molino password holder
     * @var string 
     */
    public $password = null;

    /**
     * Token
     *
     * @param mixed $init
     * @param array $options
     */
    public function __construct($init = null, $options = array())
    {
        parent::__construct($init, $options);
        $this->refreshToken();
    }

    /**
     * SetUp Object to be ready for connect
     *
     * @param array $options Object Options (url,section,defaultUrlParams,
     *                                       client_id,client_secret,username,password
     *                                       defaultUrlParams,debug)
     */
    public function setUp($options = [])
    {
        parent::setUp($options);
        $this->setupProperty($options, 'client_id', 'DATAMOLINO_ID');
        $this->setupProperty($options, 'client_secret', 'DATAMOLINO_SECRET');
        $this->setupProperty($options, 'username', 'DATAMOLINO_USERNAME');
        $this->setupProperty($options, 'password', 'DATAMOLINO_PASSWORD');
    }

    public function authentication()
    {
        if (!empty($this->apikey)) {
            $this->defaultUrlParams['apikey'] = $this->apikey;
        }
    }

    /**
     * Current Token String
     *
     * @return string
     */
    public function getTokenString()
    {
        if ($this->isTokenExpired()) {
            $this->refreshToken();
        }
        return $this->getDataValue('access_token');
    }

    /**
     * Take Token data
     * 
     * @param array $data
     * 
     * @return int items taken count
     */
    public function takeData($data)
    {
        $result = null;
        if (is_array($data) && array_key_exists('expires_in', $data)) {
            $this->expire = time() + $data['expires_in'];
            $result       = parent::takeData($data);
        }
        return $result;
    }

    /**
     * Check Access Token expiration state
     *
     * @return boolean
     */
    public function isTokenExpired()
    {
        $expireTime = $this->expire - time();
        return $expireTime < 5;
    }

    /**
     * request Fresh API access token
     * 
     * @return HTTP response code
     */
    public function requestFreshToken()
    {
        $this->defaultHttpHeaders['Content-Type'] = 'application/x-www-form-urlencoded';
        $this->setPostFields(http_build_query(
                [
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'username' => $this->username,
                    'password' => $this->password,
                    'grant_type' => 'password',
                ]
        ));
        return $this->requestData('/oauth/token', 'POST');
    }

    /**
     * Refresh token if obsoleted
     */
    public function refreshToken()
    {
        $this->takeData($this->requestFreshToken());
    }

    /**
     * Pri vytvareni objektu pomoci funkce singleton (ma stejne parametry, jako konstruktor)
     * se bude v ramci behu programu pouzivat pouze jedna jeho Instance (ta prvni).
     *
     * @param string $class název třídy jenž má být zinstancována
     *
     * @link   http://docs.php.net/en/language.oop5.patterns.html Dokumentace a priklad
     *
     * @return Token
     */
    public static function singleton($init = null, $options = [])
    {
        if (!isset(self::$_instance)) {
            $class           = __CLASS__;
            self::$_instance = new $class($init, $options);
        }

        return self::$_instance;
    }

    /**
     * Vrací se.
     *
     * @return Shared
     */
    public static function &instanced($init = null, $options = [])
    {
        $tokener = self::singleton($init, $options);

        return $tokener;
    }
}
