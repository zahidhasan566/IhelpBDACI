<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class inquiry_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doCreateInquiry($mastercode, $inquirydate, $inquiry) {
        $data['success'] = 0;
        $data['message'] = 'Inquiry inforation can not be saved successfully!';

        $pro = "exec usp_DCInquiryInsertUpdateDelete 'INSERT', 0, '$mastercode', '$inquirydate'";

        foreach ($inquiry as $inq) {
            $sql = $pro . ", " . $inq['numberofcustomer'] . ", '" . $inq['visitorname'] . "',"
                    . "'" . $inq['mobileno'] . "', '" . $inq['address'] . "', '" . $inq['age'] . "',"
                    . "'" . $inq['days'] . "', '" . $inq['productcode'] . "'";
            $query = $this->db->query($sql);
            $e = $this->db->_error_message();
            $query->free_result();
            if ($e != '')
                break;
        }

        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = 'Inquiry inforation has been saved sucessfully.';
        } else {
            $data['message'] = $e;
        }

        return $data;
    }

    public function reportinquiry($datefrom, $dateto, $customercode, $productcode, $reporttype, $pagelimit = null, $page = null, $searchstring = null) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        if (empty($customercode)) {
            $customercode = '%';
        }
        if (empty($productcode)) {
            $productcode = '%';
        }
        if (empty($pagelimit)) {
            $pagelimit = '0';
        }
        if (empty($page)) {
            $page = '%';
        }
        if ($searchstring == 'undefined') {
            $searchstring = '';
        }
        $sql = "EXEC Usp_reportDealerCustomerInquiry '$datefrom','$dateto','$customercode','$productcode','$reporttype','$pagelimit','$page','$searchstring' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['Inquiry'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        return $data;
    }

    public function reportInquiryProgressCard($datefrom, $dateto, $customercode) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = "EXEC usp_reportInquiryProgressCard '$datefrom','$dateto 23:59:59.000','$customercode' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        }
        return $data;
    }

    public function reportWalkInVisitor($datefrom, $dateto, $customercode, $pagelimit = null, $page = null, $searchstring = null) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        if (empty($customercode)) {
            $customercode = '%';
        }
        if (empty($pagelimit)) {
            $pagelimit = '0';
        }
        if (empty($page)) {
            $page = '%';
        }
        if ($searchstring == 'undefined') {
            $searchstring = '';
        }
        $sql = "EXEC usp_reportWalkInVisitor '$datefrom','$dateto','$customercode','$pagelimit','$page','$searchstring' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['WalkInVisitor'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
            $data['Total'] = $query->next_result();
        }
        return $data;
    }

    public function doLoadInquiryDesignation() {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT * FROM InquiryOccupation WHERE Active = 1 order by SL";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doLoadInquiryCustomerCategory() {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT * FROM InquiryCustomerCategory WHERE Active = 1";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doLoadInquiryMainUser() {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT * FROM InquiryMainUser WHERE Active = 1";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doLoadInquiryMediaCategory() {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT * FROM InquiryMediaCategory WHERE Active = 1";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doLoadInquiryLevel() {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT * FROM InquiryLevel WHERE Active = 1";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doLoadVisitResult() {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT * FROM VisitResult";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doLoadInquiryDocumentCategory() {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = "SELECT * FROM InquiryDocumentCategory WHERE Active = 1";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doLoadInquiryStatus($inquiryid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = "SELECT 	TOP 1 * FROM InquiryStatus WHERE InquiryId = $inquiryid ORDER BY 1 DESC ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doInsertInquiryMaster($customername, $contactno, $convenienttimetocall, $add1, $age, $gender, $occupationid, $current2wheeler, $customercategoryid, $inquirymainuserid, $mainuseroccupationid, $usercurrent2wheeler, $modelsuggested, $offertestride, $productcode, $modelyear, $expectedvalue, $bankscheme, $inquirylevelid, $slaescunsultantname, $inquiryremark, $entryby, $entryipaddress, $actiontype, $actionid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        if (empty($expectedvalue)) {
            $expectedvalue = 0;
        }
        if (empty($age)) {
            $age = 0;
        }
        if (empty($occupationid)) {
            $occupationid = 0;
        }
        if (empty($customercategoryid)) {
            $customercategoryid = 0;
        }
        if (empty($inquirymainuserid)) {
            $inquirymainuserid = 0;
        }
        if (empty($mainuseroccupationid)) {
            $mainuseroccupationid = 0;
        }
        if (empty($offertestride)) {
            $offertestride = 0;
        }
        if (empty($inquirylevelid)) {
            $inquirylevelid = 0;
        }

        $sql = "doInsertInquiryMaster '$customername','$contactno','$convenienttimetocall','$add1',"
                . "'$age','$gender','$occupationid','$current2wheeler','$customercategoryid','$inquirymainuserid',"
                . "'$mainuseroccupationid','$usercurrent2wheeler','$modelsuggested','$offertestride','$productcode',"
                . "'$modelyear','$expectedvalue','$bankscheme','$inquirylevelid','$slaescunsultantname',"
                . "'$inquiryremark','$entryby','$entryipaddress','$actiontype','$actionid'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doInsertInquiryDocument($inquiryid, $documentid) {
        $sql = "INSERT INTO InquiryDocument VALUES ('$inquiryid', '$documentid') ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function doInsertInquiryMedia($inquiryid, $categoryid) {
        $sql = "INSERT INTO InquiryMedia VALUES ('$inquiryid', '$categoryid') ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function doInsertInquiryStatus($inquiryid, $visitresultid, $productcode, $expecteddelivery, $deliveryprority, $nextdelivery, $entryby) {
        $sql = "INSERT INTO InquiryStatus VALUES ('$inquiryid', '$visitresultid', '$productcode',
                '$expecteddelivery', '$deliveryprority', '$nextdelivery', '$entryby',"
                . "GETDATE()) ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }
    
    public function doInsertTestRideInfo($inquiryid,$agentid,
            $ridedate,$ridedatetime) {
        $ridedatetime = $ridedate . ' ' .$ridedatetime;
        $sql = "INSERT INTO TestRideInfo (InquiryId,AgentId,RideDate,RideDateTime) "
                . "VALUES ('$inquiryid','$agentid','$ridedate','$ridedatetime') ";
        $query = $this->db->query($sql);
        //exit();
        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function doLoadInquiryFollowUpReport($userid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "exec usp_doLoadInquiryFollowUpReport '$userid' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    
    
    public function reportInquiryConversionSummary($datefrom, $dateto, $customercode, 
            $productcode, $pagelimit, $page) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        if (empty($customercode)) {
            $customercode = '';
        }
        if (empty($productcode)) {
            $productcode = '';
        }
        if (empty($pagelimit)) {
            $pagelimit = '20';
        }
        if (empty($page)) {
            $page = '%';
        }
        
        if (!empty($dateto)) {
            $dateto = $dateto . ' 23:59:59.000';
        }

        $sql = "EXEC usp_doLoadInquiryConversionSummary '$datefrom','$dateto','$customercode','$productcode', '$pagelimit', '$page'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['Inquiry'] = $query->result_array();
            $data['Paging'] = $query->next_result();
        }
        return $data;
    }
    
    public function doLoadRideRequestData($customercode) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT 
                    TestRideRequestId AS Request_Id,
                    P.ProductCode AS Product_Code,
                    P.ProductName AS Product_Name,
                    IM.InquiryMediaCategoryName AS Inquiry_Media,
                    RiderName AS Rider_Name,
                    RiderContactNo AS Rider_Contact_No,
                    ConvenientTimeToCall AS Convenient_Time_to_Call,
                    Address AS Rider_Address,
                    Age AS Rider_Age,
                    Gender AS Rider_Gender,
                    Current2Wheeler AS Current_2_Wheeler
                FROM TestRideRequest RR	
                    INNER JOIN Product P
                        ON RR.ProductCode = P.ProductCode
                    INNER JOIN InquiryMediaCategory IM
                        ON RR.InquiryMediaId = IM.InquiryMediaCategoryId 
                WHERE CustomerCode = '$customercode'
                    AND IsApproved = 'N'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doCancelRideRequestData($requestid){
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "UPDATE TestRideRequest SET IsApproved = 'C' WHERE TestRideRequestId = '$requestid'";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }
    
    public function doLoadRideRequestDataSingle($requestid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT 
                    TestRideRequestId AS Request_Id,
                    P.ProductCode AS Product_Code,
                    P.ProductName AS Product_Name,
                    IM.InquiryMediaCategoryName AS Inquiry_Media,
                    RiderName AS Rider_Name,
                    RiderContactNo AS Rider_Contact_No,
                    ConvenientTimeToCall AS Convenient_Time_to_Call,
                    Address AS Rider_Address,
                    Age AS Rider_Age,
                    Gender AS Rider_Gender,
                    Current2Wheeler AS Current_2_Wheeler,
                    IsApproved
            FROM TestRideRequest RR	
                    INNER JOIN Product P
                            ON RR.ProductCode = P.ProductCode
                    INNER JOIN InquiryMediaCategory IM
                            ON RR.InquiryMediaId = IM.InquiryMediaCategoryId 
            WHERE RR.TestRideRequestId = $requestid";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    
    public function doUpdateInquiryRequest($inquiryid , $requestid){
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "UPDATE TestRideRequest SET IsApproved = 'Y' , InquiryId = $inquiryid WHERE TestRideRequestId = $requestid";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }
}
