<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class corporate_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doCheckCurporateInvoice($invoiceno) {
        $data['success'] = 0;
        $sql = "exec usp_doCheckCurporateInvoice '$invoiceno' ";
        $query = $this->db->query($sql);
        
        if ($query !== false) {
            $data['customer'] = $query->result_array();
            $data['invoice'] = $query->next_result();
            $data['invoiedata'] = $query->next_result();
            $data['success'] = 1;
            //var_dump($data);
        }
        $query->free_result();

        return $data;
    }

    //Lost Documents Detail
    public function doCheckLostCurporateInvoice($invoiceno) {
    $data['success'] = 0;
    $sql = "exec usp_doCheckLostCurporateInvoice '$invoiceno' ";
    $query = $this->db->query($sql);

    if ($query !== false) {
        $data['customer'] = $query->result_array();
        $data['invoice'] = $query->next_result();
        $data['invoiedata'] = $query->next_result();
        $data['success'] = 1;
        //var_dump($data);
    }
    $query->free_result();

    return $data;
}
    public function doInsertCorporateSales($invoiceno){
        $data['success'] = 0;
        $sql = "exec usp_doInsertCorporateSales '$invoiceno' ";
        $query = $this->db->query($sql);
        
        if ($query !== false) {
            $data['success'] = 1;
        }
        //$query->free_result();
        return $data;
    }

}
