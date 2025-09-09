<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Approval_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doLoadWarrantyAppovalPending($userid, $reportype,
                $pagelimit, $pagenumber) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "EXEC usp_doLoadWarrantyAppovalPending '$userid', '$reportype','$pagelimit', '$pagenumber' ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        
        if ($query !== false) {
            $data['detaildata'] = $query->result_array();
            $data['pagingdata'] = $query->next_result();
            return $data;
        } else {
            return false;
        }
    }
    
    public function doCancelWarrantyClam($warrantyid, $userid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "UPDATE DealarWarrantyClaim SET Status = 2, "
                . " ApproveBy = '$userid', ApproveDate = GETDATE()  WHERE DCWarrantyId = '$warrantyid' ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        
        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }
    
    public function doLoadWarrantyInfoForEdit($warrantyid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "EXEC usp_doLoadWarrantyInfoForEdit '$warrantyid' ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        
        if ($query !== false) {
            $data['warrantydata'] = $query->result_array();
            $data['picturedata'] = $query->next_result();
            return $data;
        } else {
            return false;
        }
    }
    
    public function doCreateWarrantyClaim($warrantyid, $mastercode, 
            $wcdate, $chassisno,  $productcode, $files, $mileage = null, 
            $problemdetails = null, $occurancedate, $typeofwarranty, 
            $sourceofinformation, $seriousness, $technicianname, $sex, 
            $age, $weight, $ridingstyle, $roadcondition, $customercomments,
            $failureanalysis, $remedyresult, $causeoffailure,
            $serviceschedule, $additionalcomments, $problemis,
            $remedy, $result, $riderprofession) {
        $data['success'] = 0;
        $data['message'] = 'Warranty claim information coluld not be saved successfully!';

        $sql = "usp_DWClaimInsertUpdateDelete 'UPDATE', $warrantyid, '$mastercode', '$wcdate', '$chassisno', '$productcode', '$mileage' , '$problemdetails',"
                . " '$occurancedate', '$typeofwarranty', 
                    '$sourceofinformation', '$seriousness', '$technicianname', '$sex', 
                    '$age', '$weight', '$ridingstyle', '$roadcondition', '$customercomments',
                    '$failureanalysis', '$remedyresult', '$causeoffailure',"
                . " '$serviceschedule', '$additionalcomments', '$problemis',
                    '$remedy', '$result', '$riderprofession' ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e.'1st'; exit(); }
               
        if ($query !== false) {
            $row = $query->result();
            $query->free_result();
        }
        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = 'Warranty claim information saved successfully!';
            $data['lastid']  = $warrantyid;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
    
    public function doApprovedWarrantyClam($warrantyid, $userid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "UPDATE DealarWarrantyClaim SET Status = 1, "
                . " ApproveBy = '$userid', ApproveDate = GETDATE()  WHERE DCWarrantyId = '$warrantyid' ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        
        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }
    
    public function doDeleteClamWarrentyProduct($warrantyid) {
        $data['success'] = 0;
        $sql = " DELETE FROM DealarWarrantyClaimProduct WHERE DCWarrantyID = $warrantyid ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); }    
        if ($query !== false) {
            return true;
        }else{
            return false;
        }        
    }

    public function CustomerList($userid){
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = "SELECT *
                FROM Customer C
                INNER JOIN UserCustomer UC
                ON C.CustomerCode = UC.CustomerCode AND UC.UserType = 'SE'
                AND UserId = '$userid'
                WHERE CustomerType IN ('E','D','R') AND LEFT(C.CustomerCode,2) = 'HC'";        
        $query = $this->db->query($sql);             

        if($query !== false){                                       
            $rows = $query->result_array();
        }
        return $rows;
    }  

    public function RegionList($userid){
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = "SELECT 
                DISTINCT RegionName 
                FROM Customer C
                INNER JOIN UserCustomer UC
                ON C.CustomerCode = UC.CustomerCode AND UC.UserType = 'SE'
                AND UserId = '$userid'
                WHERE CustomerType IN ('E','D','R') AND LEFT(C.CustomerCode,2) = 'HC'";        
        $query = $this->db->query($sql);             

        if($query !== false){                                       
            $rows = $query->result_array();
        }
        return $rows;
    }  

}
