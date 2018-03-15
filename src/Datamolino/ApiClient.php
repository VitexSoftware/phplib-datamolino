<?php
/**
 * DataMolino - Basic rest client class.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2018 Vitex Software
 */

namespace Datamolino;

/**
 * Basic class
 *
 * @url https://datamolino.docs.apiary.io
 */
class ApiClient extends \Ease\Brick
{
    /**
     * Version of phplib-datamolino library
     *
     * @var string
     */
    public static $libVersion = '0.1.1';

    /**
     * Communication protocol version used.
     *
     * @var string API version
     */
    public $protoVersion = 'v1_2';

    /**
     * URL of object data in datamolino API
     * @var string url
     */
    public $apiURL = null;

    /**
     * Datový blok v poli odpovědi.
     * Data block in response field.
     *
     * @var string
     */
    public $resultField = 'results';

    /**
     * Section used by object
     *
     * @link https://datamolino.docs.apiary.io/#reference/
     * @var string
     */
    public $section = null;

    /**
     * Curl Handle.
     *
     * @var resource
     */
    public $curl = null;

    /**
     * Server[:port]
     * @var string
     */
    public $url = null;

    /**
     * REST API Username (usually user's email)
     * @var string
     */
    public $client_id = null;

    /**
     * REST API Password
     * @var string
     */
    public $client_secret = null;

    /**
     * @var array Pole HTTP hlaviček odesílaných s každým požadavkem
     */
    public $defaultHttpHeaders = ['User-Agent' => 'php-datamolino'];

    /**
     * Default additional request url parameters after question mark
     *
     * @var array
     */
    public $defaultUrlParams = [];

    /**
     * Identifikační řetězec.
     *
     * @var string
     */
    public $init = null;

    /**
     * Informace o posledním HTTP requestu.
     *
     * @var *
     */
    public $curlInfo;

    /**
     * Informace o poslední HTTP chybě.
     *
     * @var string
     */
    public $lastCurlError = null;

    /**
     * Used codes storage.
     *
     * @var array
     */
    public $codes = null;

    /**
     * Last Inserted ID.
     *
     * @var int
     */
    public $lastInsertedID = null;

    /**
     * Raw Content of last curl response
     *
     * @var string
     */
    public $lastCurlResponse;

    /**
     * HTTP Response code of last request
     *
     * @var int
     */
    public $lastResponseCode = null;

    /**
     * Body data  for next curl POST operation
     *
     * @var string
     */
    protected $postFields = null;

    /**
     * Last operation result data or message(s)
     *
     * @var array
     */
    public $lastResult = null;

    /**
     * Nuber from  @rowCount
     * @var int
     */
    public $rowCount = null;

    /**
     * Save 404 results to log ?
     * @var boolean
     */
    protected $ignoreNotFound = false;

    /**
     * Array of errors caused by last request
     * @var array
     */
    private $errors = [];

    /**
     * Access Token Info
     * @var Token
     */
    protected $tokener = null;

    /**
     * Class for read only interaction with IPEX.
     *
     * @param mixed $init default record id or initial data
     * @param array $options Connection settings override
     */
    public function __construct($init = null, $options = [])
    {
        $this->init = $init;

        parent::__construct();
        $this->setUp($options);
        $this->curlInit();

        if (get_class($this) != 'Datamolino\Token') {
            $this->tokener = Token::instanced($init, $options);
        }

        if (!empty($init)) {
            $this->processInit($init);
        }
    }

    /**
     * SetUp Object to be ready for connect
     *
     * @param array $options Object Options (url,section,defaultUrlParams,
     *                                       defaultUrlParams,debug)
     */
    public function setUp($options = [])
    {
        $this->setupProperty($options, 'url', 'DATAMOLINO_URL');
        $this->setSection( isset($options['section']) ? $options['section'] : $this->section );
        $this->setupProperty($options, 'defaultUrlParams');
        $this->setupProperty($options, 'debug');
        $this->updateApiURL();
    }

    /**
     * Inicializace CURL
     */
    public function curlInit()
    {
        $this->curl = \curl_init(); // create curl resource
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true); // return content as a string from curl_exec
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true); // follow redirects (compatibility for future changes in IPEX)
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, true);       // HTTP authentication
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false); // IPEX by default uses Self-Signed certificates
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_VERBOSE, ($this->debug === true)); // For debugging
    }

    /**
     * Initialise object
     *
     * @param mixed $init 
     */
    public function processInit($init)
    {
        if (empty($init) == false) {
            $this->loadFromAPI($init);
        }
    }

    /**
     * Set section for communication
     *
     * @param string $section section pathName to use
     * @return boolean section switching status
     */
    public function setSection($section)
    {
        $this->section = $section;
        return $this->updateApiURL();
    }

    /**
     * Obtain current used section
     *
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Prepare data for send to API
     *
     * @param string $data
     */
    public function setPostFields($data)
    {
        $this->postFields = $data;
    }

    /**
     * Return basic URL for used Evidence
     *
     * @return string Evidence URL
     */
    public function getSectionURL()
    {
        $sectionUrl = $this->url.'/api/'.$this->protoVersion.'/';
        $section    = $this->getSection();
        if (!empty($section)) {
            $sectionUrl .= $section;
        }
        return $sectionUrl;
    }

    /**
     * Add suffix to Evidence URL
     *
     * @param string $urlSuffix
     *
     * @return string
     */
    public function sectionUrlWithSuffix($urlSuffix)
    {
        $sectionUrl = $this->getSectionURL();
        if (!empty($urlSuffix)) {
            if (($urlSuffix[0] != '/') && ($urlSuffix[0] != ';') && ($urlSuffix[0]
                != '?')) {
                $sectionUrl .= '/';
            }
            $sectionUrl .= $urlSuffix;
        }
        return $sectionUrl;
    }

    /**
     * Update $this->apiURL
     */
    public function updateApiURL()
    {
        $this->apiURL = $this->getSectionURL();
    }

    /**
     * I/O operation function
     *
     * @param string $urlSuffix část URL za identifikátorem firmy.
     * @param string $method    HTTP/REST metoda
     * 
     * @return array|boolean Výsledek operace
     */
    public function requestData($urlSuffix = null, $method = 'GET')
    {
        $this->rowCount = null;

        if (preg_match('/^http/', $urlSuffix)) {
            $url = $urlSuffix;
        } elseif ($urlSuffix[0] == '/') {
            $url = $this->url.$urlSuffix;
        } else {
            $url = $this->sectionUrlWithSuffix($urlSuffix);
        }

        $this->authentication();

        $url = $this->addDefaultUrlParams($url);

        $responseCode = $this->doCurlRequest($url, $method);

        return strlen($this->lastCurlResponse) ? $this->parseResponse($this->rawResponseToArray($this->lastCurlResponse,
                    $this->responseMimeType), $responseCode) : null;
    }

    public function authentication()
    {
        if (!is_null($this->tokener)) {
            $this->defaultHttpHeaders['Authorization'] = 'Bearer '.$this->getTokenString();
        }
    }

    /**
     * Add params to url
     *
     * @param string  $url      originall url
     * @param array   $params   value to add
     * @param boolean $override replace already existing values ?
     *
     * @return string url with parameters added
     */
    public function addUrlParams($url, $params, $override = false)
    {
        $urlParts = parse_url($url);
        $urlFinal = $urlParts['scheme'].'://'.$urlParts['host'];
        if (array_key_exists('path', $urlParts)) {
            $urlFinal .= $urlParts['path'];
        }
        if (array_key_exists('query', $urlParts)) {
            parse_str($urlParts['query'], $queryUrlParams);
            $urlParams = $override ? array_merge($params, $queryUrlParams) : array_merge($queryUrlParams,
                    $params);
        } else {
            $urlParams = $params;
        }
        if (count($urlParams)) {
            $urlFinal .= '?'.http_build_query($urlParams);
        }
        return $urlFinal;
    }

    /**
     * Add Default Url params to given url if not overrided
     *
     * @param string $urlRaw
     *
     * @return string url with default params added
     */
    public function addDefaultUrlParams($urlRaw)
    {
        return $this->addUrlParams($urlRaw, $this->defaultUrlParams, false);
    }

    /**
     * Parse Datamolino API Response
     *
     * @param string $responseRaw raw response body
     *
     * @return array
     */
    public function rawResponseToArray($responseRaw)
    {
        $responseDecoded = json_decode($responseRaw, true, 10);
        $decodeError     = json_last_error_msg();
        if ($decodeError != 'No error') {
            $this->addStatusMessage('JSON Decoder: '.$decodeError, 'error');
            $this->addStatusMessage($responseRaw, 'debug');
        }
        return $responseDecoded;
    }

    /**
     * Parse Response array
     *
     * @param array $responseDecoded
     * @param int $responseCode Request Response Code
     *
     * @return array main data part of response
     */
    public function parseResponse($responseDecoded, $responseCode)
    {
        $response = null;
        switch ($responseCode) {
            case 201: //Success Write
                if (isset($responseDecoded[$this->resultField][0]['id'])) {
                    $this->lastInsertedID = $responseDecoded[$this->resultField][0]['id'];
                    $this->setMyKey($this->lastInsertedID);
                    $this->apiURL         = $this->getSectionURL().'/'.$this->lastInsertedID;
                } else {
                    $this->lastInsertedID = null;
                }
            case 200: //Success Read
                $this->lastResult = $responseDecoded;
                $response         = array_key_exists($this->getSection(), $responseDecoded) ? $responseDecoded[$this->getSection()] : $responseDecoded;
            case 204: //Success delete
                break;

            case 500: // Internal Server Error
            case 404: // Page not found
                if ($this->ignoreNotFound === true) {
                    break;
                }
            case 400: //Bad Request parameters
            case 422:    
            default: //Something goes wrong

                $this->addStatusMessage((isset($responseDecoded['message']) ? $responseDecoded['message'].': '
                            : '').
                    $this->curlInfo['url'], 'warning');
                if (is_array($responseDecoded)) {
                    $this->parseError($responseDecoded);
                }
                $this->logResult($responseDecoded, $this->curlInfo['url']);
                break;
        }
        return $response;
    }

    /**
     * Parse error message response
     *
     * @param array $responseDecoded
     * 
     * @return int number of errors processed
     */
    public function parseError(array $responseDecoded)
    {
        
        if(array_key_exists('errors', $responseDecoded)){
            $this->errors = $responseDecoded['errors'];
            foreach ($responseDecoded['errors'] as $errorInfo){
                $this->addStatusMessage(json_encode($errorInfo) , 'error');
            }
        }
        
        return count($this->errors);
    }

    /**
     * Perform HTTP request
     *
     * @param string $url    URL požadavku
     * @param string $method HTTP Method GET|POST|PUT|OPTIONS|DELETE
     * 
     * @return int HTTP Response CODE
     */
    public function doCurlRequest($url, $method)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->postFields);

        $httpHeaders = $this->defaultHttpHeaders;

        if (!isset($httpHeaders['Accept'])) {
            $httpHeaders['Accept'] = 'application/json';
        }
        if (!isset($httpHeaders['Content-Type'])) {
            $httpHeaders['Content-Type'] = 'application/json';
        }
        $httpHeadersFinal = [];
        foreach ($httpHeaders as $key => $value) {
            if (($key == 'User-Agent') && ($value == 'php-datamolino')) {
                $value .= ' v'.self::$libVersion;
            }
            $httpHeadersFinal[] = $key.': '.$value;
        }

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $httpHeadersFinal);

        $this->lastCurlResponse            = curl_exec($this->curl);
        $this->curlInfo                    = curl_getinfo($this->curl);
        $this->curlInfo['when']            = microtime();
        $this->curlInfo['request_headers'] = $httpHeadersFinal;
        $this->responseMimeType            = $this->curlInfo['content_type'];
        $this->lastResponseCode            = $this->curlInfo['http_code'];
        $this->lastCurlError               = curl_error($this->curl);
        if (strlen($this->lastCurlError)) {
            $this->addStatusMessage(sprintf('Curl Error (HTTP %d): %s',
                    $this->lastResponseCode, $this->lastCurlError), 'error');
        }

        if ($this->debug === true) {
            $this->saveDebugFiles();
        }

        return $this->lastResponseCode;
    }

    /**
     * Save RAW Curl Request & Response to files in Temp directory
     */
    public function saveDebugFiles()
    {
        $tmpdir   = sys_get_temp_dir();
        $fname    = $this->section.'-'.$this->curlInfo['when'].'.json';
        $reqname  = $tmpdir.'/request-'.$fname;
        $respname = $tmpdir.'/response-'.$fname;
        file_put_contents($reqname, $this->postFields);
        file_put_contents($respname, $this->lastCurlResponse);
    }

    /**
     * Load data from API
     * 
     * @param string $key
     * 
     * @return array
     */
    public function loadFromAPI($key)
    {
        return $this->takeData($this->requestData($key));
    }

    /**
     * Write Operation Result.
     *
     * @param array  $resultData
     * @param string $url        URL
     * 
     * @return boolean Log save success
     */
    public function logResult($resultData = null, $url = null)
    {
        $logResult = false;
        if (is_null($resultData)) {
            $resultData = $this->lastResult;
        }
        if (isset($url)) {
            $this->logger->addStatusMessage(urldecode($url));
        }
        if (!empty($resultData) && array_key_exists('message', $resultData)) {
            $this->logger->addStatusMessage($resultData['message'],'warning');
        }

        return $logResult;
    }

    /**
     * Current Token String
     *
     * @return string
     */
    public function getTokenString()
    {
        return $this->tokener->getTokenString();
    }

    /**
     * Set or get ignore not found pages flag
     *
     * @param boolean $ignore set flag to
     *
     * @return boolean get flag state
     */
    public function ignore404($ignore = null)
    {
        if (!is_null($ignore)) {
            $this->ignoreNotFound = $ignore;
        }
        return $this->ignoreNotFound;
    }

    /**
     * Disconnect from server.
     */
    public function disconnect()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
        $this->curl = null;
    }

    /**
     * Reconnect After unserialization
     */
    public function __wakeup()
    {
        parent::__wakeup();
        $this->curlInit();
    }

    /**
     * Disconnect CURL befere pass away
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    public function postData($data = null)
    {
        if (empty($data)) {
            $data = $this->getData();
        }
        $this->setPostFields(json_encode([$this->section => [$data]],
                JSON_PRETTY_PRINT));
        return $this->requestData(null, 'POST');
    }
}
