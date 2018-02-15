<?php
/**
 * Datamolino - Document handling Class.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2018 Vitex Software
 */

namespace Datamolino;

/**
 * Document 
 * 
 * @link https://datamolino.docs.apiary.io/#reference/document Document 
 */
class Document extends ApiClient
{
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
    public $section = 'document';

    /**
     * Document
     * 
     * @link https://datamolino.docs.apiary.io/#reference/document/
     *
     * @param mixed $init
     * @param array $options
     */
    public function __construct($init = null, $options = array())
    {
        parent::__construct($init, $options);
    }

}
