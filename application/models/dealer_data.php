<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class dealer_data extends CI_Model {

        public function __construct() {
            parent::__construct();
        }
          
        public function CustomerList($customerlistid){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            $sql = " SELECT * FROM Customer WHERE CustomerCode LIKE '$customerlistid' AND CustomerType IN ('E','D','R') AND LEFT(CustomerCode,2) = 'HC' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        } 
        
        public function AllCustomerList($customerlistid){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            $sql = " SELECT * FROM Customer WHERE CustomerCode LIKE '$customerlistid' AND CustomerType IN ('E','D','R') AND LEFT(CustomerCode,1) = 'H' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        } 
        
        public function CustomerDetails($customercode){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            $sql = " SELECT * FROM Customer WHERE CustomerCode = '$customercode' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }  

        public function RegionList($customercode){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            $sql = "SELECT DISTINCT RegionName  
                    FROM Customer C	
                    INNER JOIN UserCustomer UC ON C.CustomerCode = UC.CustomerCode 
                    AND UC.UserType = 'SE'
                    WHERE C.CustomerCode LIKE '$customercode' 
                    AND CustomerType IN ('E','D','R') 
                    AND LEFT(C.CustomerCode,2) = 'HC'
                    AND Active = 'Y'";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }  
        
           
    }
