<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expense_Data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    
    public function doLoadExpenseList( $userid, $pagelimit, $pagenumber, $search ) {

        $data['success'] = 0;
        $sql = "exec usp_doLoadExpenseList '$userid', '$pagelimit', '$pagenumber', '$search'";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['expensedata'] = $query->result_array();
            $data['pagingdata'] = $query->next_result();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
    
    public function doLoadPendingExpense($userid, $designation, $baseurl){
        $data['success'] = 0;
        $sql = "exec usp_doLoadPendingExpenseList '$userid', '$designation', '$baseurl'";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            return $query->result_array();
        } else {
            $data['message'] = $e;
            return $data;
        }
    }
    
    public function doUpdateDealarExpense($expenseid, $approvedamount, $userid, $designation){
        $sql = "exec usp_doUpdateDealarExpense '$expenseid', '$approvedamount', '$userid', '$designation'";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    public function doLoadExpenseHead() {

        $data['success'] = 0;
        $sql = " SELECT * FROM ExpenseHead ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['headlist'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
    
    public function doLoadExpenseSubHead($headid) {
        $data['success'] = 0;
        $sql = " SELECT * FROM ExpenseSubHead WHERE HeadId = '$headid' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['headlist'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
    
    public function doLoadExpense($expenseid) {
        $data['success'] = 0;
        $sql = " SELECT * FROM DealarExpense WHERE ExpenseID = '$expenseid' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['result'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
    
    public function doInsertDealarExpense($actiontype, $actionid, $customercode, 
            $expensedate, $expensehead, $expensesubhead, $purpose, $details, $customername, 
            $customermobile, $engineno, $chassisno, $amount, $userid){
        
        $data['success'] = 0;
        $sql = "exec usp_doInsertDealarExpense '$actiontype', '$actionid', '$customercode', "
                . "'$expensedate', '$expensehead', '$expensesubhead', '$purpose', '$details', "
                . "'$customername', '$customermobile', '$engineno', '$chassisno', '$amount', '$userid' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); }
        if ($e == '') {
            $data['result'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
    
    public function doInsertDealarExpenseAttachment($insertid, $filename){        
        $sql = "INSERT INTO DealarExpenseAttachment VALUES ('$insertid', '$filename') ";
        
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); }
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    public function doDeleteDealarExpenseAttachment($insertid, $filename){        
        $sql = "DELETE FROM DealarExpenseAttachment WHERE ExpenseID = '$insertid' ";
        
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); }
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    public function doLoadExpenseApprovalList($baseurl, $customercode, $datefrom ,$dateto){
        
        $data['success'] = 0;
        $sql = "exec usp_doLoadExpenseApprovalList '$baseurl', '$customercode', "
                . "'$datefrom', '$dateto' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); }
        if ($e == '') {
            $data['result'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
	
}
