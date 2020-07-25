<?php

/**
 * Datamolino - User Class.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  (C) 2017-2020 Vitex Software
 */

namespace Datamolino;

use Datamolino\ApiClient;
use Ease\Shared;

/**
 * Token handling Class.
 *
 * @url http://devdoc.primaerp.com/rest/authentication.html
 */
class User extends ApiClient {

    /**
     * Saves obejct instace (singleton...).
     *
     * @var Shared
     */
    private static $_instance = null;

    /**
     * Sekce užitá objektem.
     * Section used by object
     *
     * @var string
     */
    public $section = 'me';

    /**
     * Token
     *
     * @param mixed $init
     * @param array $options
     */
    public function __construct($init = null, $options = array()) {
        parent::__construct($init, $options);
    }

}
