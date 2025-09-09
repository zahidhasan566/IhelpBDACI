<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class TransportNotification_Data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    
    public function doLoadTransportNotificationList( $userid, $pagelimit, $pagenumber, $search ) {

        $data['success'] = 0;
        $sql = "exec usp_doLoadTransportNotificationList '$userid', '$pagelimit', '$pagenumber', '$search'";
//echo $sql; exit();
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


    public function doLoadTransportList() {

        $data['success'] = 0;
        $sql = "SELECT * FROM Transport WHERE Active = 'Y' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['success'] = 1;
            $data['transportlist'] =  $query->result_array();
        } else {
            $data['message'] = $e;
        }

        return $data;
    }

    public function doLoadCustomerMobile($customercode) {

        $data['success'] = 0;
        $sql = " SELECT 
                    CASE WHEN D.CustomerMobile IS NOT NULL THEN D.CustomerMobile ELSE 
                        CASE WHEN Phone = '' THEN RIGHT(REPLACE(Mobile, '-',''),11) ELSE RIGHT(REPLACE(Mobile, '-',''),11) END END Phone,
                    ISNULL(D.MOTMNumber,'') AS MOTMNumber
                FROM Customer C
                    LEFT JOIN (SELECT TOP 1 * FROM TransportNotification WHERE CustomerCode = '$customercode' AND MOTMNumber <> '' ORDER BY NotificationID DESC) D
                        ON C.CustomerCode = D.CustomerCode
                WHERE C.CustomerCode = '$customercode' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['success'] = 1;
            $data['mobilenumber'] =  $query->result_array();
        } else {
            $data['message'] = $e;
        }

        return json_encode($data);
    }

    public function doLoadChallanInformation($challanno) {

        $data['success'] = 0;
        $sql = " SELECT 
                    CustomerCode, TransportName, DriverName, DriverPhoneNo, TransportNo 
                FROM ChallanMaster	WHERE ChallanNo = '$challanno' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['success'] = 1;
            $data['data'] =  $query->result_array();
        } else {
            $data['message'] = $e;
        }

        return json_encode($data);
    }

    public function doInsertTransportNotification($actiontype, $actionid, $customercode, $dealermobile,
            $transportid, $contactno, $truckno, $drivercontactno, $deliverytime, $userid, 
            $entryip, $drivername, $motmnumber, $challanno){

        $data['success'] = 0;
        $sql = "exec usp_doInsertTransportNotification '$actiontype', '$actionid', '$customercode', '$dealermobile', 
            '$transportid', '$contactno', '$truckno', '$drivercontactno', '$deliverytime', 
            '$userid', '$entryip', '$drivername', '$motmnumber', '$challanno' ";
//echo $sql; exit();
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); }
        if ($e == '') {
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }

    public function doLoadNotification($notificationid) {
        $data['success'] = 0;
        $sql = " SELECT * FROM TransportNotification WHERE NotificationID = '$notificationid' ";

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
}
