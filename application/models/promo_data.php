<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Promo_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }      
    
    public function reportDealarPromoData($Period, $CustomerCode, $BrandCode){
        $data['success'] = 0;        
       
        $sql = "exec usp_doLoadDealarPromoValue '$Period','$CustomerCode','$BrandCode' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array(); 
        }
        $query->free_result();

        return $data;
    }
	
}
