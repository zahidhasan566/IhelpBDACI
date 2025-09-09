<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }      
    
    public function usp_doLoadCustomerDue($customercode){
        $data['success'] = 0;        
       
        $sql = "exec usp_doLoadCustomerDueFroDMS '$customercode' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array(); 
        }
        $query->free_result();

        return $data;
    }
	
}
