<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dealardocument_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
       
    public function doInsertDealerDocument($documentsl, $filename, $entryby, $ipaddress) {
        $data['success'] = 0;
        $sql = "exec usp_doInsertDealerDocument '$documentsl', '$filename', '$entryby', '$ipaddress' ";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return true;
        }else{
            return false;
        }        
    }  
    
    
    public function doLoadDealerDocumentList($pagelimit, $pagenumber, $searchvalue) {
        $data['success'] = 0;
        $sql = "exec usp_doLoadDealerDocumentList '$pagelimit', '$pagenumber', '$searchvalue' ";
		
        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }        
    }
    
}
