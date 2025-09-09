<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class feedbackdata_data extends CI_Model {

        public function __construct() {
            parent::__construct();
        }
          
        public function loadCustomerFeedback($customercode,$pagelimit='20',$pagenumber=''){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            $sql = " usp_reportCustomerFeedback '$customercode','$pagelimit','$pagenumber' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $data['feedbackdata'] = $query->result_array();
                $data['paging'] = $query->next_result();
                return $data;
            }
            return $rows;
        }         
        
           
    }
