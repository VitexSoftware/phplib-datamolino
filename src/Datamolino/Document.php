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
class Document extends ApiClient {

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
    public function __construct($init = null, $options = array()) {
        parent::__construct($init, $options);
    }

    /**
     * Obtain Documents listing for given folder
     * 
     * @param int   $agendaId FolderID
     * @param array $states   States Requested
     * @param int   $page     Which Page with up to 50 records request ?
     * 
     * @return array
     */
    public function getPageOfDocuments($agendaId, $states = ['ready'], $page = 1) {
        $urlparams = '?agenda_id=' . $agendaId . '&page=' . $page;

        if (!empty($states)) {
            foreach ($states as $state) {
                $statesRaw[] = 'states[]=' . $state;
            }
            $urlparams .= '&states=' . urlencode('[' . implode('&', $statesRaw) . ']');
        }
        return $this->requestData($urlparams);
    }

    /**
     * Get All Documents
     * 
     * @param int   $agendaId FolderID
     * @param array $states   States Requested
     * 
     * @return array All results
     */
    public function getAllDocuments($agendaId, $states = ['ready']) {
        $page = 1;
        $allPages = [];
        do {
            $pageData = $this->getPageOfDocuments($agendaId, $states, $page++);
            if (!empty($pageData)) {
                $allPages = array_merge($allPages, \Ease\Sand::reindexArrayBy($pageData, 'id'));
            }
        } while (count($pageData) == 50);
        return $allPages;
    }

    /**
     * Obtain Original File Data 
     * 
     * @return array
     */
    public function getOriginalFileData($documentId = null) {
        if (is_null($documentId)) {
            $documentId = $this->getMyKey();
        }
        $fileInfo = $this->requestData($documentId . '/original_file')[0];
        $fileInfo['original_file_body'] = \MIME\Base64URLSafe::urlsafe_b64decode($fileInfo['original_file_base64']);
        return $fileInfo;
    }

    /**
     * Save original document to file
     * 
     * @param string $destination Dirpath or filename
     * @param int    $documentId  Datamolino document ID
     * 
     * @return int saved data length
     */
    public function saveOriginalFile($destination, $documentId = null) {
        $originalFileData = $this->getOriginalFileData($documentId);
        return file_put_contents(is_dir($destination) ? $destination . '/' . $originalFileData['user_file_name'] : $originalFileData['user_file_name'],
                $originalFileData['original_file_body']);
    }

}
