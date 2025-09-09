<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class BankLoan_Data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    
    public function doLoadBankLoanFileList( $userid, $pagelimit, $pagenumber, $search ) {

        $data['success'] = 0;
        $sql = "exec usp_doLoadBankLoanFileList '$userid', '$pagelimit', '$pagenumber', '$search'";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['bankfiledata'] = $query->result_array();
            $data['pagingdata'] = $query->next_result();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
    
    public function doLoadEMIBankList() {

        $data['success'] = 0;
        $sql = " SELECT * FROM EMIBank ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['banklist'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
    
    public function doLoadBankLoanStatusList() {
        $data['success'] = 0;
        $sql = " SELECT * FROM BankLoanStatus ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['bankstatuslist'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;        
        }
        return $data;
    }
    
    public function doInserNewBankLoanFile($actiontype, $actionid,$customername,
            $address, $contactno, $age, $occupationid, $emibankid, $productcode, $branchname, 
            $bankcontactperson, $bankcontactpersonmobile, $filenumber, $bankloanstatusid,$entryby){
        
        $sql = " exec usp_doInserNewBankLoanFile  '$actiontype', '$actionid', "
                . "'$customername', '$address', '$contactno', '$age', '$occupationid', "
                . "'$emibankid', '$productcode', '$branchname', '$bankcontactperson', "
                . "'$bankcontactpersonmobile', '$filenumber', '$bankloanstatusid', '$entryby'";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = "Successfully insert.";        
        } else {
            $data['success'] = 0;
            $data['message'] = $e;        
        }
        return $data;
    }
    
    public function doLoadBankLoan($loanid) {
        $data['success'] = 0;
        $sql = " SELECT * FROM BankLoan WHERE BankLoadId = '$loanid' ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['bankloandata'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;        
        }
        return $data;
    }
    
	
}
