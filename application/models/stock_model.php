<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stock_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_products() {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $sql = "SELECT * FROM Product";
        
        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
        } else {
            return false;
        }
    }

}
