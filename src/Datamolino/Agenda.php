<?php
/**
 * Datamolino - Agenda handling Class.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2018 Vitex Software
 */

namespace Datamolino;

/**
 * Agenda handling Class.
 *
 * @url https://datamolino.docs.apiary.io/#reference/agenda/
 */
class Agenda extends ApiClient
{
    /**
     * Saves obejct instace (singleton...).
     *
     * @var Shared
     */
    private static $_instance = null;

    /**
     * Section used by object
     *
     * @var string
     */
    public $section = 'agendas';

    /**
     * Agenda
     *
     * @link http://devdoc.primaerp.com/rest/authentication.html Documentation
     * 
     * @param mixed $init
     * @param array $options
     */
    public function __construct($init = null, $options = [])
    {
        parent::__construct($init, $options);
    }

    /**
     * Get Agendas list
     * 
     * @return array
     */
    public function getListing()
    {
        return $this->requestData();
    }

    /**
     * Create New Agenda
     * 
     * @param array $data
     * 
     * @return array
     */
    public function createNew($data)
    {
        return $this->postData($data);
    }
}
