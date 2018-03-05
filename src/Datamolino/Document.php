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
    public $section = 'documents';

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

    /**
     * Obtain Documents listing for given folder
     * 
     * @param int $agendaId FolderID
     * 
     * @return array
     */
    public function getDocuments($agendaId)
    {
        return $this->requestData('?agenda_id='.$agendaId);
    }

    /**
     * 
     * @return type
     */
    public function getOriginalFileData($documentId = null)
    {
        if (is_null($documentId)) {
            $documentId = $this->getMyKey();
        }
        return current($this->requestData($documentId.'/original_file'));
    }

    /**
     * Save original document to file
     * 
     * @param string $destination Dirpath or filename
     * @param int    $documentId  Datamolino document ID
     * 
     * @return int saved data length
     */
    public function saveOriginalFile($destination, $documentId = null)
    {
        $originalFileData = $this->getOriginalFileData($documentId);
        return file_put_contents(is_dir($destination) ? $destination.'/'.$originalFileData['user_file_name']
                : $originalFileData['user_file_name'],
            base64_decode($originalFileData['original_file_base64']));
    }
}
