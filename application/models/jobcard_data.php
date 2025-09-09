<?php

use LDAP\Result;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class JobCard_Data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    
    public function doLoadJobCardList($userid, $pagelimit, $pagenumber, $search ) {

        $data['success'] = 0;
        $ipaddress = get_ip_address();
        $sql = "exec usp_doLoadJobCardList '$userid', '$pagelimit', '$pagenumber', '$search'";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['jobcarddata'] = $query->result_array();
            $data['pagingdata'] = $query->next_result();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }
    
    public function doLoadJobCardNo($userid) {
        $row = array();
        $sql = "exec usp_doLoadJobCardNoNew '$userid' ";
        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result_array();
        }
        return $row;
    }
    
    public function doLoadJobTechnician($userid, $active = 'Y', $techniciancode = '') {
        $data['success'] = 0;
        $ipaddress = get_ip_address();
        
        $sql = "SELECT 
                    TechnicianCode,
                    TechnicianEmpCode AS Employee_Code,
                    TechnicianName,
                    ContactNo AS Mobile,
                    LEFT(JoiningDate,11) AS Joining_Date,
                    Address,
                    EducationalQualification AS Educational_Qualification,
                    Training,
                    Comment,
                    Designation,
                    CASE WHEN Active = 'Y' THEN 'Active' ELSE 'Inactive' END Active_Status,
                    Active,
                    DefaultBay
                FROM tblTechnicianSetup
                WHERE ServiceCenterCode = '$userid' ";
        if(!empty($active)){ $sql .= " AND Active = 'Y' "; } 
        if(!empty($techniciancode)){ $sql .= " AND TechnicianCode = '$techniciancode' "; } 
        $sql .= " Order BY  TechnicianCode ";
       
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['techniciandata'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }
        return $data;
    }
    
    public function doLoadJobBay($userid, $active = 'Y', $onlyfree = 2,
            $baycode = '') {
        $data['success'] = 0;
        $ipaddress = get_ip_address();
        
        $sql = "SELECT 
                    BayCode,
                    BayName,
                    Comment,
                    CASE WHEN Active = 'Y' THEN 'Active' ELSE 'Inactive' END Active_Status,
                    Active
                FROM TblBaySetup
                WHERE ServiceCenterCode = '$userid' ";
        if(!empty($onlyfree) && $onlyfree == 2){ $sql .= " AND StatusCode != 2"; }
        if(!empty($active) && $active == 'Y'){ $sql .= " AND Active = 'Y' "; }
        if(!empty($baycode)){ $sql .= " AND BayCode = '$baycode' "; }
        
        $sql .= " ORDER BY 2 ";
//echo $sql; exit();
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['baydata'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }
        return $data;
    }
    
    public function doLoadJobStatus($userid) {
        $data['success'] = 0;
        $ipaddress = get_ip_address();
        
        $sql = "SELECT * FROM TblJobStatus WHERE Active = 'Y' ORDER BY  ShowOrder";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['jobstatus'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }
        return $data;
    }
    
    public function doLoadJobType($userid) {
        $data['success'] = 0;
        $ipaddress = get_ip_address();
        
        $sql = "SELECT 
                    * 
                FROM TblJobType 
                WHERE Active = 'Y'
                    AND ParentId = 0
                ORDER BY ReportOrder";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['jobtype'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }
        return $data;
    }
    
    public function doLoadWork($userid, $search, $active = '', $workcode = '', $top = ' TOP 100 ') {
        $data['success'] = 0;
        $ipaddress = get_ip_address();
       
        $sql = "SELECT {$top} "
        . " ServiceCenterCode,
            WorkCode,
            WorkName,
            WorkRate,
            Comment,
            CASE WHEN Active = 'Y' THEN 'Active' ELSE 'Inactive' END Active_Status,
            Active 
        FROM tblWorkSetup WHERE ServiceCenterCode = '$userid'";
        if(!empty($search)){ $sql .= " AND WorkName LIKE '%".$search."%' ";  }
        if(!empty($active)){ $sql .= " AND Active LIKE '$active' ";  }
        if(!empty($workcode)){ $sql .= " AND WorkCode LIKE '$workcode' ";  }
        $sql .= " Order by WorkCode ";
        
        //echo $sql; exit();
        
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            //var_dump($query->result_array()); exit();
            $data['worklist'] = $query->result_array();
            $data['success'] = 1;
        } else {
            echo $e; exit();
            $data['message'] = $e;
        }
        return $data;
    }
    
    public function doGenerateCode($userid, $table, $field) {
        $data['success'] = 0;
        $ipaddress = get_ip_address();
        
        $sql = "SELECT 
                    CASE WHEN 
                            RIGHT('00'+ISNULL( CONVERT(VARCHAR(3), MAX(CONVERT(INT, $field)) + 1) ,''),2) = '00' THEN '01' ELSE 
                    RIGHT('00'+ISNULL( CONVERT(VARCHAR(3), MAX(CONVERT(INT, $field)) + 1) ,''),2)  END Code
                FROM {$table} 
                WHERE ServiceCenterCode = '$userid' ";
        
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doGenerateCodeWork($userid, $table, $field) {
        $data['success'] = 0;
        $ipaddress = get_ip_address();
        
        $sql = "SELECT 
                    CASE WHEN 
                            RIGHT('000'+ISNULL( CONVERT(VARCHAR(3), MAX(CONVERT(INT, $field)) + 1) ,''),3) = '000' THEN '001' ELSE 
                            RIGHT('000'+ISNULL( CONVERT(VARCHAR(3), MAX(CONVERT(INT, $field)) + 1) ,''),3)  END Code
                FROM $table
                WHERE ServiceCenterCode = '$userid' ";
        
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doInsertJobBay($action, $baycode, $bayname,
                $comments, $active, $entryby) {
        $data['success'] = 0;
        $sql = "exec usp_doInsertJobBay '$action', '$baycode', '$bayname',
                '$comments', '$active', '$entryby' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    public function doInsertJobWork($action, $workcode, $workname,
            $workrate, $comments, $active, $entryby) {
        $data['success'] = 0;
        $sql = "exec usp_doInsertJobWork '$action', '$workcode', '$workname',
                '$workrate',' $comments', '$active', '$entryby' ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    public function doInsertJobTechnician($action, $techniciancode, $technicianempcode, 
            $technicianname, $contactno, $joiningdate, $address, $educationalqualification, 
            $training, $comment, $designation, $active, $entryby,$defaultBay='') {
        $data['success'] = 0;
        $sql = "exec usp_doInsertJobTechnician '$action', '$techniciancode', '$technicianempcode', 
            '$technicianname', '$contactno', '$joiningdate', '$address', '$educationalqualification', 
            '$training', '$comment', '$designation', '$active', '$entryby', '$defaultBay'";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function doInsertJobCard($actiontype, $jobcardno, $jobdate, $jobdatetime, $serialno, $customername, $purchasedate, $mobileno, 
                $chassisno, $registrationno, $engineno, $brand, $model, $mileage,
                $underwarrenty, $address, $problemdetails, $motorcycleoutercondition, $reasonprolemrepairdetails, $techniciancode,
                $baycode, $timerequired, $jobstatus, $jobtypeid, $freesscheduleid, $servicecentercode, $discounttype, $aciemployeeid,
                $discountpercent, $entryby, $mechanicscode = '',$YTD_status='N',$YTD_status_no_reason=null,$ytd_file, $FI_status, $FI_status_no_reason) {
        
        if(empty($discountpercent)){
            $discountpercent            = 0;
        }
        $data['success'] = 0;
        $sql = "exec usp_doInsertJobCard '$actiontype', '$jobcardno', '$jobdate', '$jobdatetime', 
                    '$serialno', '$customername', '$purchasedate', '$mobileno', 
                    '$chassisno', '$registrationno', '$engineno', '$brand', '$model', '$mileage',
                    '$underwarrenty', '$address', '$problemdetails', '$motorcycleoutercondition', 
                    '$reasonprolemrepairdetails', '$techniciancode', '$baycode', '$timerequired', '$jobstatus', 
                    '$jobtypeid', '$freesscheduleid', '$servicecentercode', '$discounttype', '$aciemployeeid',
                    '$discountpercent', '$entryby', '$mechanicscode', '$YTD_status', '$YTD_status_no_reason','$ytd_file', '$FI_status', '$FI_status_no_reason'";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e.$sql; exit(); } 
        if ($e == '') {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doLoadJobCardDetails($jobcardno){
        $data['success'] = 0;
        $sql = " SELECT T.*,TS.TechnicianName,ISNULL(F.ScheduleTitle, J.JobTypeName) ServiceNoName
                FROM tblJobCard T
                    LEFT JOIN FreeServiceSchedule F
                            ON F.FreeSScheduleID = T.FreeSScheduleID
                    LEFT JOIN tblJobType J
                            ON T.JobTypeId = J.id
                    LEFT JOIN tblTechnicianSetup TS
							ON T.TechnicianCode = TS.TechnicianCode
                WHERE JobCardNo = '$jobcardno' ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); } 
        if ($e == '') {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doLoadJobCardDetailsPartsWork($jobcardno, $itemtype, $userid){
        $data['success'] = 0;
        $sql = "SELECT 
                    J.*, ISNULL(P.ProductName, OP.ProductName) ProductName,
                    ISNULL(P.PartNo, OP.SMSCode)  PartNo,
                    W.WorkName
                FROM tblJobCardDetailSparepartWork J
                    LEFT JOIN Product P
                            ON J.ItemCode = P.ProductCode
                    LEFT JOIN OtherProduct OP
                            ON J.ItemCode = CONVERT(VARCHAR(10),OP.ProductCode)
                    LEFT JOIN tblWorkSetup W
                            ON J.ItemCode = W.WorkCode AND W.ServiceCenterCode = '$userid'
                WHERE JobCardNo = '$jobcardno'
                        AND ItemType = '$itemtype' ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); } 
        if ($e == '') {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doLoadJobCardProblemDetails($jobcardno){
        $data['success'] = 0;
        $sql = "SELECT * FROM tblJobCardProblemDetails
                WHERE JobCardNo = '$jobcardno'";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); } 
        if ($e == '') {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doDeleteJobCard($jobcardno, $itemtype){
        $data['success'] = 0;
        $sql = "DELETE FROM tblJobCardDetailSparepartWork "
                . "WHERE JobCardNo = '$jobcardno' AND ItemType = '$itemtype' ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e. $sql; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function doInsertJobCardDetails($jobcardno, $itemtype, $itemcode, 
                $quantity, $unitprice, $totalprice, $servicecharge, $discount){
        $data['success'] = 0;
        $sql = "INSERT INTO tblJobCardDetailSparepartWork 
			(JobCardNo, ItemType, ItemCode, Quantity, UnitPrice, TotalPrice, ServiceCharge, Discount)
                VALUES	('$jobcardno', '$itemtype', '$itemcode', 
                $quantity, $unitprice, $totalprice, $servicecharge, $discount) ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e.' - '.$sql; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    public function doLoadJobCardDetailsForPrint($jobcardno,$source=''){        
        $data['success'] = 0;
        $sql = "exec usp_doLoadJobCardDetils '$jobcardno' ";
        if($source == '3s') {
            $CI = & get_instance();
            $CI->db = $this->load->database('motor3s',true);
        }
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e.' - '.$sql; exit(); } 
        if ($e == '') {
            $data['jobcarddetails'] = $query->result_array();
            $data['partsdetails']   = $query->next_result();
            $data['workdetails']    = $query->next_result();
            return $data;
        } else {
            return false;
        }
    }
    
    public function doDeleteJobCardProblemDetails($jobcardno){
        $data['success'] = 0;
        $sql = "DELETE FROM tblJobCardProblemDetails "
                . "WHERE JobCardNo = '$jobcardno'  ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e. $sql; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    public function doInsertJobCardProblemDetails($jobcardno, $problemdetails){
        $data['success'] = 0;
        $sql = "INSERT INTO  tblJobCardProblemDetails VALUES ('$jobcardno','$problemdetails') ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e. $sql; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    public function doInsertDealarInvoiceMaster($customercode, $invoicedate, 
                $invoicedatetime, $customername, $mobileno){
        $data['success'] = 0;
        $sql = "usp_DIMasterInsertUpdateDelete 'INSERT', 0, '$customercode', '$invoicedate', '$invoicedatetime',  
	            '$customername', '', '', '', '', '$mobileno', '', '', '0', '', ''";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e. $sql; exit(); } 
        if ($e == '') {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doInsertDealarInvoiceDetails($invoiceid, $productcode,
            $productname, $quantity, $unitprice){
        $data['success'] = 0;
        $sql = "INSERT INTO DEALARINVOICEDETAILS 
                (InvoiceId, ProductCode, ProductName, Quantity, UnitPrice, VAT, Discount) 
                VALUES ($invoiceid, '$productcode','$productname',$quantity,$unitprice,0,0)";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e. $sql; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }
    
    public function doLoadJobCardReport($datefrom, $dateto, $customercode, $jobstatus, $jobtype,$ageLimit='100',$pageNumber='1'){
        $data['success'] = 0;
        $sql = "exec usp_doLoadJobCardReport2 '$datefrom', '$dateto', '$customercode', '$jobstatus','$jobtype','$ageLimit', '$pageNumber' ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e. $sql; exit(); } 
        $data = [];
        if ($e == '') {
            $data['result'] = $query->result_array();
            $data['paging'] = $query->next_result();
        } 
        return $data;
    }
    
    public function doLoadTechnicianWiseReport($datefrom, $dateto, $customercode){
        $data['success'] = 0;
        $sql = "exec usp_doLoadTechnicianWiseReport '$datefrom', '$dateto', '$customercode' ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e. $sql; exit(); } 
        $data = [];
        if ($e == '') {
            $data['result'] = $query->result_array();
            $data['paging'] = $query->next_result();
        } 
        return $data;
    }
    
    public function doUpdateJobCardStatus($jobstatus, $jobcardno, $entryby){
        $data['success'] = 0;
        $sql = "exec usp_doUpdateJobCardStatus '$jobstatus', '$jobcardno', '$entryby' ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e. $sql; exit(); } 
        if ($e == '') {
            if ($jobstatus == 'Gatepass') {
                $status_change_sql = "UPDATE TblJobCard SET GatePass = '1' WHERE JobCardNo = '$jobcardno'";
                $this->db->query($status_change_sql);
            }
            return true;
        } else {
            return false;
        }
    }
    
    
    public function doLoadJobCardBookingReport($datefrom, $dateto, $customercode){
        $data['success'] = 0;
        $sql = "SELECT 
                    B.CustomerName AS Customer_Name, CustomerMobile AS Customer_Mobile, Chassisno AS Chassis_no, 
                    LEFT(ServiceDate,11) Service_Date, TimeSlot AS Time_Slot, ServiceTYpe AS Service_Type, ServiceName AS Service_Name,	
                    ReservationNo AS Reservation_No, ServiceCenterCode AS Service_Center_Code
                FROM [192.168.100.201].dbYamahaServiceCenter.dbo.tblOnlineBooking B
                    INNER JOIN Customer C
                        ON B.ServiceCenterCode = C.CustomerCode
                    INNER JOIN [192.168.100.201].dbYamahaServiceCenter.dbo.tblTimeSlot T
                        ON T.TimeSlotId = B.TimeSlotId
                WHERE ('' = '$customercode' OR ServiceCenterCode = '$customercode')
                        AND ServiceDate BETWEEN '$datefrom' AND '$dateto' ORDER BY ReservationNo";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e. $sql; exit(); } 
        if ($e == '') {
            return $query->result_array();
        } else {
            return false;
        }
    }
	
	public function doLoadLocalMechanics($userid, $active = 'Y', $mechanicscode = '') {
        $data['success'] = 0;
        $data['mechanicsdata'] = array();
        
        $sql = "SELECT 
                    M.ServiceCenterCode AS Dealar,
                    -- D.DistrictCode,
                    D.DistrictName,
                    -- U.UpazillaCode,
                    U.UpazillaName,
                    MechanicsCode,
                    MechanicsName,
                    MechanicsPhone AS Mobile,
                    LEFT(JoiningDate,11) AS Joining_Date,
                    Address,
                    EducationQualification AS Educational_Qualification,
                    MechanicsShopName,
                    CASE WHEN Active = 'Y' THEN 'Active' ELSE 'Inactive' END Active_Status,
                    Active
                FROM tblDealarMechanics M
                    INNER JOIN District D
                        ON M.DistrictCode = D.DistrictCode
                    LEFT JOIN Upazilla U 
                        ON U.UpazillaCode = M.UpazillaCode
                "; //WHERE ServiceCenterCode = '$userid' 
        if(!empty($active)){ $sql .= " AND Active = 'Y' "; } 
        if(!empty($techniciancode)){ $sql .= " AND MechanicsCode = '$mechanicscode' "; } 
        $sql .= " Order BY  MechanicsCode ";
       
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['mechanicsdata'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }
        return $data;
    }

    public function getAffiliator($affiliatorCode = '',$serviceCenterCode = '') {
        $sql = "SELECT 
                    
                    A.DistrictCode,
                    D.DistrictName,
                    AffiliatorCode,
                    AffiliatorName,
                    AffiliatorPhone AS Mobile,
                    LEFT(JoiningDate,11) AS Joining_Date,
                    Address,
                    EducationQualification AS Educational_Qualification,
                    AffiliatorShopName,
                    CASE WHEN Active = 'Y' THEN 'Active' ELSE 'Inactive' END Active_Status,
                    Active
                FROM tblDealarAffiliator A
                    INNER JOIN District D
                        ON A.DistrictCode = D.DistrictCode                    
                    Where A.Active = 'Y'
                ";
        
        if(!empty($affiliatorCode)){ $sql .= " AND A.AffiliatorCode = '$affiliatorCode' "; } 
        
        $sql .= " Order BY  A.AffiliatorCode ";

        // die($sql);
       
        try {
            $query = $this->db->query($sql);            
            return $query->result_array();
        } catch (\Throwable $th) {
            log_message('error', $this->db->_error_message()." ==== IN: ".__FILE__." Line:".__LINE__);
            show_error(QUERY_ERROR_MESSAGE);
        }
    }
	
    
    public function doLoadLocalMechanicsEdit($userid, $active = 'Y', 
            $mechanicscode = '') {
        $data['success'] = 0;
        $data['mechanicsdata'] = array();
        
        $sql = "SELECT 
                    M.ServiceCenterCode AS Dealar,
                    D.DistrictCode,
                    D.DistrictName,
                    U.UpazillaCode,
                    U.UpazillaName,
                    MechanicsCode,
                    MechanicsName,
                    MechanicsPhone AS Mobile,
                    LEFT(JoiningDate,11) AS Joining_Date,
                    Address,
                    EducationQualification AS Educational_Qualification,
                    MechanicsShopName,
                    CASE WHEN Active = 'Y' THEN 'Active' ELSE 'Inactive' END Active_Status,
                    Active
                FROM tblDealarMechanics M
                    INNER JOIN District D
                        ON M.DistrictCode = D.DistrictCode
                    LEFT JOIN Upazilla U 
                        ON U.UpazillaCode = M.UpazillaCode
                WHERE ServiceCenterCode = '$userid' ";
        // if(!empty($active)){ $sql .= " AND Active = 'Y' "; } 
        // if(!empty($techniciancode)){ $sql .= " AND MechanicsCode = '$mechanicscode' "; } 
        // $sql .= " Order BY  MechanicsCode ";
       
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['mechanicsdata'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }
        return $data;
    }
	

    public function doLoadLocalMechanicsUpdate($userid, $active = 'Y', 
            $mechanicscode = '') {
        $data['success'] = 0;
        $data['mechanicsdata'] = array();
        
        $sql = "SELECT 
                    M.ServiceCenterCode AS Dealar,
                    D.DistrictCode,
                    D.DistrictName,
                    U.UpazillaCode,
                    U.UpazillaName,
                    MechanicsCode,
                    MechanicsName,
                    MechanicsPhone AS Mobile,
                    LEFT(JoiningDate,11) AS Joining_Date,
                    Address,
                    EducationQualification AS Educational_Qualification,
                    MechanicsShopName,
                    CASE WHEN Active = 'Y' THEN 'Active' ELSE 'Inactive' END Active_Status,
                    Active
                FROM tblDealarMechanics M
                    INNER JOIN District D
                        ON M.DistrictCode = D.DistrictCode
                    LEFT JOIN Upazilla U 
                        ON U.UpazillaCode = M.UpazillaCode
                WHERE ServiceCenterCode = '$userid' ";
        if(!empty($active)){ $sql .= " AND Active = 'Y' "; } 
        if(!empty($techniciancode)){ $sql .= " AND MechanicsCode = '$mechanicscode' "; } 
        $sql .= " Order BY  MechanicsCode ";
       
        //echo "<pre />"; echo $sql; exit();

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['mechanicsdata'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }
        return $data;
    }

	public function doLoadDistrict() {
        $data['success'] = 0;
        $ipaddress = get_ip_address();
        
        $sql = "SELECT DistrictCode, DistrictName FROM District ORDER BY DistrictName ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['district'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }
        return $data;
    }
    
    public function doLoadUpazila($districtcode) {
        $data['success'] = 0;
        $ipaddress = get_ip_address();
        
        $sql = "SELECT 
                    DistrictCode, UpazillaCode, UpazillaName 
                FROM Upazilla 
                WHERE DistrictCode = '$districtcode' ORDER BY UpazillaName ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($e == '') {
            $data['upazila'] = $query->result_array();
            $data['success'] = 1;
        } else {
            $data['message'] = $e;
        }
        return $data;
    }
	
	public function doInsertLocalMechanics($actiontype, 
                $mechanicscode, $mechanicsname, $districtcode, $upazillacode,
                $joiningdate, $address, $contactno,  $educationalqualification, 
                $mechanicsshopname, $active, $userid){
        $sql = "exec usp_doInsertDealarMechanics '$actiontype', '$mechanicscode', '$mechanicsname', '$districtcode', 
            '$upazillacode', '$joiningdate', '$address', '$contactno', '$educationalqualification', 
            '$mechanicsshopname', '$active', '$userid' ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }

    public function addAffiliator($actiontype, 
                $affiliatorCode, $affiliatorName, $districtCode,
                $joiningDate, $address, $contactno,  $educationalqualification, 
                $affiliatorShopName, $active){
        $sql = "exec usp_addAffiliator '$actiontype', '$affiliatorCode', '$affiliatorName', '$districtCode', 
             '$joiningDate', '$address', '$contactno', '$educationalqualification', 
            '$affiliatorShopName', '$active' ";
    
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); } 
        if ($e == '') {
            return true;
        } else {
            return false;
        }
    }

    public function getYtdNoStatusReason($category) {
        $sql = "select * from YtdNoStatusReason  WHERE Category = '$category'";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }

    public function doLoadProblemStatements() {
        $sql = "SELECT * FROM tblJobCardProblemStatement";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }

    public function doCheckPartStock($data){
        $userid = $data['userid'];
        $productcode    = $data['productcode'];
        $sql    = "SELECT 
                    SUM(Total_Receive) - SUM(Sales)  CurrentStock
                    FROM (
                    SELECT 
                    SUM(D.ReceivedQnty) Total_Receive, 0 AS Sales
                    FROM DealarReceiveInvoiceMaster m
                    INNER JOIN DealarReceiveInvoiceDetails d
                    ON m.ReceiveID = d.ReceiveID
                    WHERE MasterCode = '$userid'
                    AND ProductCode = '$productcode'
                    UNION ALL
                    SELECT 
                    0 AS Receive, SUM(d.Quantity) Sales
                    FROM DealarInvoiceMaster m
                    INNER JOIN DealarInvoiceDetails d
                    ON m.InvoiceID = d.InvoiceID
                    WHERE MasterCode = '$userid'
                    AND ProductCode = '$productcode'
                    ) s";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }
	
    public function getJobcardEstimation($userId,$estimationNo='') {
        $sql = "select * from JobcardEstimation where EntryBy = '$userId' and (''='$estimationNo' or EstimationNo= '$estimationNo' ) order by EstiamtionDate desc";
        $query = $this->db->query($sql);
        if($query &&  $result = $query->result_array()) {
            if($estimationNo !='') {
                return $result[0];

            }
            return $result;
        }
        return [];
    }
    public function getJobcardEstimationDetails($estimationNo) {
        $sql = "select ED.*,P.ProductName from  JobcardEstimationDetails ED
                    join Product P on P.ProductCode=ED.ItemCode
                where EstimationNo = '$estimationNo'";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }


    public function getJobCardData($jobcardno) {
        $sql = "select * from tblJobCard where JobCardNo = '$jobcardno' ";
        $query = $this->db->query($sql);
        if($query &&  $result = $query->result_array()) {
            return $result;
        }
        return [];
    }

    //getServiceCenterJobStatics
    public function getServiceCenterJobStatics($from,$to,$dailyServiceEstimate,$code){
        $sql = "exec usp_getServiceCenterJobStatics '$from', '$to','$dailyServiceEstimate','$code'";

        $result['success'] = false;
        $query = $this->db->query($sql);
        $data = array();
        if ($query) {
            $data = $query->result_array();
        }
        return $data;
    }

}
