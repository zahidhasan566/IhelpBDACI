<?php

use LDAP\Result;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setup_Data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    
    public function syncGazipurData() {

        $data['success'] = 0;
       
        $sql = "exec usp_doUpdateJobCardInfoGazipur";

        $query = $this->db->query($sql);
        // echo "<pre/>";print_r($query);exit();
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    
    public function doLoadCustomerAssigningList() {

        $data['success'] = 0;
       
        $sql = "SELECT U.*,C.CustomerName from UserCustomer U left join Customer C on U.CustomerCode=C.CustomerCode";

        $query = $this->db->query($sql);
        // echo "<pre/>";print_r($query);exit();
        if ($query) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function doLoadUserTypes() {

        $data['success'] = 0;
       
        $sql = "SELECT distinct UserType from UserCustomer";

        $query = $this->db->query($sql);
        // echo "<pre/>";print_r($query);exit();
        if ($query) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function CustomerList(){
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = " SELECT * FROM Customer where CustomerType IN ('E','D','R') AND LEFT(CustomerCode,2) = 'HC' ";        
        $query = $this->db->query($sql);             

        if($query !== false){                                       
            return $query->result_array();
        }
        return [];
    } 

    public function insertNewCustomerData($custData)
    {
        // echo "<pre/>";print_r($custData);exit();
        $UserId = $custData['UserId'];
        $CustomerCode = $custData['CustomerCode'];
        $RegionName = $custData['RegionName'];
        $UserType = $custData['UserType'];
        $ActiveStatus = $custData['ActiveStatus'];

        $sql = "INSERT into UserCustomer(UserId,CustomerCode,RegionName,UserType,ActiveStatus) 
                values ('$UserId','$CustomerCode','$RegionName','$UserType','$ActiveStatus')";
        $query = $this->db->query($sql);

        if($query){
            return true;
        }else{
            return false;
        }
    }
    
    public function doLoadAssignedCustomer($id)
    {
        $data['success'] = 0;
       
        $sql = "SELECT * from UserCustomer where UserCustomerId='$id'";

        $query = $this->db->query($sql);
        // echo "<pre/>";print_r($query);exit();
        if ($query) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function updateAssignedCustomerData($custData)
    {
        // echo "<pre/>";print_r($custData);exit();
        $UserCustomerId = $custData['UserCustomerId'];
        $UserId = $custData['UserId'];
        $CustomerCode = $custData['CustomerCode'];
        $RegionName = $custData['RegionName'];
        $UserType = $custData['UserType'];
        $ActiveStatus = $custData['ActiveStatus'];

        $sql = "UPDATE UserCustomer 
                SET 
                UserId='$UserId',CustomerCode='$CustomerCode',RegionName='$RegionName',
                UserType='$UserType',ActiveStatus='$ActiveStatus'
                where UserCustomerId='$UserCustomerId'" ;

        $query = $this->db->query($sql);

        if($query){
            return true;
        }else{
            return false;
        }
    }
    
    
}
