<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doCreateService($mastercode, $servicetype, $chassisno, 
            $freesscheduleid, $schecked, $servicename, $schange, $serviceid, 
            $productcode, $productname, $unitprice, $qnty, 
            $servicecharge, $jobcardno, $customerentrytime, 
            $servicestarttime, $serviceendttime,
            $technicianname, $problemdetails, $failureanalysis, 
            $remedyresult, $mileage) {
        $data['success'] = 0;
        $data['message'] = 'Service information can not be saved successfully!';
        $servicedate = date('Y-m-d H:i:s', strtotime("now") - 360);
        //echo date('Y-m-d ').' '.$customerentrytime; exit();
        $customerentrytime = date('Y-m-d ') . $customerentrytime;
        $servicestarttime = date('Y-m-d ') . $servicestarttime;
        $serviceendttime = date('Y-m-d ') . $serviceendttime;
        $ipaddress = get_ip_address();
        $svrname = "";
        $srv = array();
        for ($i = 0; $i < count($schecked); $i++) {
            $found = false;
            $srv = array();
            for ($j = 0; $j < count($servicename); $j++) {
                $srv = explode("_", $servicename[$j]);
                $sid = substr($servicename[$j], 0, strlen($schecked[$i]));
                if ($sid == $schecked[$i]) {
                    $found = true;
                    break;
                }
            }

            if ($found == true) {
                $svrname .= $srv[1] . ", ";
            }
        }
        if (!empty($svrname))
            $svrname = substr(trim($svrname), 0, -1);
        $prochaned = '';
        for ($i = 0; $i < count($productname); $i++) {
            $prochaned .= $productname[$i] . ", ";
        }
        if (!empty($prochaned))
            $prochaned = substr(trim($prochaned), 0, -1);

        switch (strtoupper($servicetype)) {
            case "FREE":
                $servicetype = 0;
                break;
            case "PAID":
                $servicetype = 1;
                break;
            case "REPAIR":
                $servicetype = 2;
                break;
        }

        $sql = "usp_DSMasterInsertUpdateDelete 'INSERT', 0, $servicetype, '$mastercode', '$servicedate', " .
                "'$chassisno', $freesscheduleid, '$ipaddress', '$svrname', '$prochaned', '$jobcardno', '$customerentrytime',
                '$servicestarttime', '$serviceendttime', '$technicianname', '$problemdetails', "
                . "'$failureanalysis', '$remedyresult','$mileage' ";
        
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($query !== false) {
            $row = $query->result();
            @$query->free_result();
            $lastid = $row[0]->lastid;
            if (count($schecked) > 0) {
                foreach ($schecked as $checked) {
                    $changed = array_search($checked, $schange);
                    if (!empty($changed)) {
                        $changed = $schange[$changed];
                    } else {
                        $changed = 0;
                    }

                    $sql = "exec usp_DSCheckInsertUpdateDelete 'INSERT', 0, $lastid, " . $checked . "," . $changed;
                    $query = $this->db->query($sql);
                    $e = $this->db->_error_message();
                    @$query->free_result();
                    if ($e != '')
                        break;
                }
            }
            if ($servicetype == 2) {
                if (count($serviceid) > 0) {
                    for ($i = 0; $i < count($serviceid); $i++) {
                        $sql = "exec usp_DSChangeInsertUpdateDelete 'INSERT', 0, $lastid, " . $serviceid[$i] . ", " .
                                "'" . $serviceid[$i] . "', 1, " . $servicecharge[$i];
                        $query = $this->db->query($sql);
                        $e = $this->db->_error_message();
                        @$query->free_result();
                        if ($e != '')
                            break;
                    }
                }
                if (count($productcode) > 0) {
                    for ($i = 0; $i < count($productcode); $i++) {
                        $sql = "exec usp_DSChangeInsertUpdateDelete 'INSERT', 0, $lastid, 0, " .
                                "'" . $productcode[$i] . "', 1, " . $unitprice[$i];
                        $query = $this->db->query($sql);
                        $e = $this->db->_error_message();
                        @$query->free_result();
                        if ($e != '')
                            break;
                    }
                }
            } else {
                for ($i = 0; $i < count($serviceid); $i++) {
                    $sql = "exec usp_DSChangeInsertUpdateDelete 'INSERT', 0, $lastid, " . $serviceid[$i] . ", " .
                            "'" . $productcode[$i] . "', 1, " . $unitprice[$i];
                    $query = $this->db->query($sql);
                    $e = $this->db->_error_message();
                    @$query->free_result();
                    if ($e != '')
                        break;
                }
            }
            if ($e == '') {
                if ($freesscheduleid != 0) {
                    $sql = "exec usp_FreeServiceScheduleUpdate $freesscheduleid";
                    $query = $this->db->query($sql);
                    $e = $this->db->_error_message();
                }
            }
        }

        if ($e == '') {
            $data['success'] = 1;
            $data['dsmasterid'] = $lastid;
            $data['message'] = 'Service information has been saved sucessfully.';
        } else {
            $data['message'] = $e;
        }

        return $data;
    }

    public function doCreateRegistration($action, $servregid, $mastercode, $customername, $productname, $chassisno, $engineno, $color, $bikeage) {

        $data['success'] = 0;
        $data['message'] = 'Could not save bike registration information.';

        $ipaddress = get_ip_address();
        $sql = "exec usp_DSBikeRegInsertUpdateDelete '$action', $servregid, '$mastercode', '$customername', '$productname', 
            '$chassisno', '$engineno', '$color', '$bikeage', '$ipaddress'";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        $e = str_replace('[Microsoft][ODBC SQL Server Driver][SQL Server]', '', $e);
        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = 'Bike registration has been saved sucessfully.';
        } else {
            $data['message'] = $e;
        }

        return $data;
    }

    public function doCreateWarrantyClaim($mastercode, $JobCardNo, $wcdate, $chassisno, 
            $productcode, $files, $mileage = null, $problemdetails = null, 
            $occurancedate, $typeofwarranty, 
            $sourceofinformation, $seriousness, $technicianname, $sex, 
            $age, $weight, $ridingstyle, $roadcondition, $customercomments,
            $failureanalysis, $remedyresult, $causeoffailure,
            $serviceschedule, $additionalcomments, $problemis,
            $remedy, $result, $riderprofession) {
        $data['success'] = 0;
        $data['message'] = 'Warranty claim information coluld not be saved successfully!';

        $sql = "usp_DWClaimInsertUpdateDelete 'INSERT', 0, '$mastercode', '$wcdate', '$chassisno', '$productcode', '$mileage' , '$problemdetails',"
                . " '$occurancedate', '$typeofwarranty', 
                    '$sourceofinformation', '$seriousness', '$technicianname', '$sex', 
                    '$age', '$weight', '$ridingstyle', '$roadcondition', '$customercomments',
                    '$failureanalysis', '$remedyresult', '$causeoffailure',"
                . " '$serviceschedule', '$additionalcomments', '$problemis',
                    '$remedy', '$result', '$riderprofession', '$JobCardNo' ";

                    //echo $sql; exit();
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e.'1st'; exit(); }
               
        if ($query !== false) {
            $row = $query->result();
            $query->free_result();
            $lastid = $row[0]->lastid;
            $thumb = '';
            $filescount = count($_FILES['uploadpic']['name']);
            for ($i = 0; $i < $filescount; $i++) {
                $_FILES['userfile']['name'] = $files['uploadpic']['name'][$i];
                $_FILES['userfile']['type'] = $files['uploadpic']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $files['uploadpic']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $files['uploadpic']['error'][$i];
                $_FILES['userfile']['size'] = $files['uploadpic']['size'][$i];
                if(!empty($_FILES['userfile']['name'])){
                    $config = array(
                        'upload_path' => "./upload/warrantyclaim/",
                        'allowed_types' => "jpg|png|jpeg",
                        'overwrite' => false,
                        'max_size' => "1024000",
                        'file_name' => $chassisno . "_" . $productcode . "_" . ($i) . '.' . end(explode('.', $_FILES['userfile']['name']))
                    );
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('userfile')) {
                        $data['imageError'] = $this->upload->display_errors();
                        if(!empty($data['imageError'])){ echo $data['imageError']; exit(); }
                    } else {
                        //$success = $this->image_thumb($config['upload_path'].$config['file_name']);
                        //if ($success) $thumb = substr($files['uploadpic']['name'][$i], 0, -4)."_thumb".substr($files['uploadpic']['name'][$i], -4);
                        $data = array('upload_data' => $this->upload->data());
                        $uploadfilename = $data['upload_data']['file_name'];
                    }
                    $sql = "usp_DWClaimDetailsInsertUpdateDelete 'INSERT', 0, $lastid, '$thumb', '" . $uploadfilename . "'";
                    $query = $this->db->query($sql);
                    $query->free_result();
                    $e = $this->db->_error_message();
                    if(!empty($e)){ echo $e.'2nd'; exit(); }
                }
            }
        }

        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = 'Warranty claim information saved successfully!';
            $data['lastid']  = $lastid;
        } else {
            $data['message'] = $e;
        }

        return $data;
    }

    function image_thumb($image_path) {
        // Get the CodeIgniter super object
        $CI = & get_instance();
        $success = false;
        // Path to image thumbnail
        $image_thumb = $image_path;
        $CI->load->library('image_lib');
        if (!file_exists(substr($image_thumb, 0, -4) . "_thumb" . substr($image_thumb, -4))) {
            // LOAD LIBRARY                
            // CONFIGURE IMAGE LIBRARY
            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_path;
            $config['new_image'] = substr($image_thumb, 0, -4) . "_thumb" . substr($image_thumb, -4);
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['height'] = 150;
            $config['width'] = 150;
            $CI->image_lib->initialize($config);
            $success = $CI->image_lib->resize();
            $CI->image_lib->clear();
        }

        return $success;
    }

    public function doLoadMyServices($mastercode, $pagelimit = 1000, $pagenumber = '1') {
        $data['success'] = 0;
        $data = array(
            "sEcho" => 1,
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "data" => array()
        );
        $sql = "exec usp_LoadMyServices '$mastercode',$pagelimit,'$pagenumber' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['iTotalRecords'] = count($row);
            $data['iTotalDisplayRecords'] = 10;
            $data['data'] = $row;
            $data['paging'] = $query->next_result();
            $query->free_result();
        }

        return $data;
    }

    public function insertOtherProduct($actiontype, $smscode, $productname, $unitprice, $userid) {
        $data['success'] = 0;
        $sql = "exec usp_insertOtherProduct '$actiontype','','$smscode','$productname','$unitprice','$userid' ";
        $query = $this->db->query($sql);
        if ($query !== false) {
            $data['success'] = 1;
            $query->free_result();
        }
        return $data;
    }
    
    public function doInsertClamWarrentyProduct($warrantyid, $warrantyinvoiceid, $productcode, $quantity = 0, $servicecharge = 0,
            $unitprice) {
        $data['success'] = 0;
        $sql = "INSERT INTO DealarWarrantyClaimProduct (DCWarrantyID,WarrantyInvoiceId,ProductCode,Quantity,ServiceCharge, UnitPrice)
                    VALUES ('$warrantyid','$warrantyinvoiceid','$productcode','$quantity','$servicecharge','$unitprice') ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if(!empty($e)){ echo $e; exit(); }    
        if ($query !== false) {
            $data['success'] = 1;
            //$query->free_result();
        }
        return $data;
    }

    public function doLoadServiceDetails($dsmasterid) {
        $data['success'] = 0;
        $data['message'] = '';
        $sql = "exec usp_LoadServiceDetails $dsmasterid";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result_array();
            $pros = $query->next_result();
            $data['success'] = 1;
            $data['svr'] = $row;
            $data['pros'] = $pros;
        }

        return $data;
    }
    
    public function doLoadJobCardPrintInfo($dsmasterid) {
        $data['success'] = 0;
        $data['message'] = '';
        $sql = "exec usp_doLoadJobCardPrintInfo $dsmasterid";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $pros = $query->next_result();
            $data['success'] = 1;
            $data['svr'] = $row;
            $data['pros'] = $pros;
        }

        return $data;
    }
    public function doLoadServices($servicetype, $search = '') {
        $row = array();

        $sql = "exec usp_LoadServices $servicetype, '$search'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
        }
        return $row;
    }
    
    public function doLoadJobCardNo($userid) {
        $row = array();

        $sql = "exec usp_doLoadJobCardNo '$userid' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result_array();
        }
        return $row;
    }

    public function doLoadServiceCharge($servicetype, $packsize, $serviceid) {
        $data['success'] = 0;
        $data['message'] = '';
        $sql = "exec usp_LoadServiceCharge $servicetype, '$packsize', $serviceid";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['success'] = true;
            $data['data'] = $row;
        }

        return $data;
    }

    public function doLoadCustomerDetails($chassisno) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $sql = "exec usp_LoadCustomerDetails '$chassisno'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $data['data'] = $query->result_array();
            $data['service'] = $query->next_result();
        }

        return $data;
    }

    public function doLoadBikes($userid, $chassisno, $chasistype = 1) {
        $data['success'] = 0;
        $data['msgtype'] = 'error';
        $data['data'] = array();
        $sql = " EXEC usp_ServiceBikeList '$userid', '$chassisno', '$chasistype' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($query !== false) {
            $row = $query->result();
            if (count($row) > 0) {
                $data['success'] = 1;
                $data['msgtype'] = '';
                $data['data'] = $row;
            }
            $query->free_result();
        }
        return $data;
    }

    public function doLoadBikesRegistered($userid, $chassisno) {
        $data['success'] = 0;
        $data['msgtype'] = 'error';
        $data['data'] = array();
        $sql = " exec usp_ServiceBikeRegistered '$userid', '$chassisno' ";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($query !== false) {
            $row = $query->result();
            if (count($row) > 0) {
                $data['success'] = 1;
                $data['msgtype'] = '';
                $data['data'] = $row;
            }
            $query->free_result();
        }
        return $data;
    }

    public function doLoadMyWarrantyClaim($userid) {
        $data['success'] = 0;
        $data = array(
            "sEcho" => 1,
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "data" => array()
        );
        $sql = "exec usp_LoadWarrantyClaim '$userid'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['iTotalRecords'] = count($row);
            $data['iTotalDisplayRecords'] = 10;
            $data['data'] = $row;
            $query->free_result();
        }

        return $data;
    }

    public function doWarrantyApprove($dcwarrantyid) {
        $data['success'] = 0;
        $data['message'] = 'Warranty claim could not be approved!';
        $ipaddress = get_ip_address();
        $sql = "exec usp_DealarWarrantyClaimApprove '$dcwarrantyid'";

        $query = $this->db->query($sql);
        $e = $this->db->_error_message();

        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = 'Warranty claim has been approved sucessfully.';
        } else {
            $data['message'] = $e;
        }

        return json_encode($data);
    }

    public function reportfreeservice($datefrom, $dateto, $customercode, $productcode, 
            $reporttype, $pagelimit = null, $page = null, $searchstring = null,
            $withoutremark = '', $doneservice = '') {
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
        $sql = "EXEC Usp_reportEstimatedFreeService '$datefrom','$dateto',"
                . "'$customercode','$productcode', '$reporttype','$pagelimit',"
                . "'$page','$searchstring','$withoutremark'"
                . ", '$doneservice' ";        
        $query = $this->db->query($sql);
        if ($query !== false) {
            $data['FreeService'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        return $data;
    }
    
    public function reportpaidservice($datefrom, $dateto, $customercode, $productcode, 
            $reporttype, $pagelimit = null, $page = null, $searchstring = null,
            $withoutremark = '', $doneservice = '') {
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
        $sql = "EXEC Usp_reportEstimatedPaidService '$datefrom','$dateto',"
                . "'$customercode','$productcode', '$reporttype','$pagelimit',"
                . "'$page','$searchstring' ";
        
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['FreeService'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        return $data;
    }

    public function ServiceList($datefrom, $dateto, $customercode, $reporttype, $pagelimit = null, $page = null, $searchstring = null) {
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
        $sql = "EXEC Usp_reportDealerService '$datefrom','$dateto','$reporttype','$customercode','$pagelimit','$page','$searchstring' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['Service'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        return $data;
    }
    
    public function doLoadCustomerProfileing($datefrom, $dateto, 
            $customercode, $searchstring = null, $pagelimit = null, $page = null) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        
        $sql = "EXEC usp_doLoadCustomerProfileing '$datefrom','$dateto','$customercode','$searchstring','$pagelimit','$page' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['Service'] = $query->result_array();
            $data['Paging'] = $query->next_result();
        }
        return $data;
    }

    public function BikeRegistrationReport($datefrom, $dateto, $customercode, $pagelimit = null, $page = null, $searchstring = null) {
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

        $sql = "EXEC usp_reportBikeRegistration '$datefrom','$dateto','$customercode','$pagelimit','$page','$searchstring' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['BikeRegistration'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        return $data;
    }

    public function ClaimWarrantyReport($datefrom, $dateto, $customercode, $pagelimit = null, $page = null, $searchstring = null, $approvedtype = null, $region, $userid) {
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

        $sql = "EXEC Usp_reportWarrantyClaim '$datefrom','$dateto','$customercode', '%','$pagelimit','$page','$searchstring','$approvedtype','$region','$userid'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['WarrantyReport'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        return $data;
    }

    public function doChangePartsReceivingStatus($DCWarrantyId,$ProductCode)
    {
        $time = date("Y-m-d H:i:s");
        $sql = "UPDATE DealarWarrantyClaimProduct SET PartsReceivingStatus=1,PartsReceivingTime='$time' WHERE DCWarrantyID=$DCWarrantyId AND ProductCode='$ProductCode'";
        $query = $this->db->query($sql);

        if($query !== false){
            return true;
        }
        return false;
    }
    
    public function dochangeWarrantyJudgementStatus($DCWarrantyId,$ProductCode)
    {
        $time = date("Y-m-d H:i:s");
        $sql = "UPDATE DealarWarrantyClaimProduct SET WarrantyJudgementByService=1,WarrantyJudgementTime='$time' WHERE DCWarrantyID=$DCWarrantyId AND ProductCode='$ProductCode'";
        $query = $this->db->query($sql);

        if($query !== false){
            return true;
        }
        return false;
    }
    
    public function dochangeWarrantyJudgementRejectStatus($DCWarrantyId,$ProductCode)
    {
        $time = date("Y-m-d H:i:s");
        $sql = "UPDATE DealarWarrantyClaimProduct SET WarrantyJudgementByService=2,WarrantyJudgementTime='$time' WHERE DCWarrantyID=$DCWarrantyId AND ProductCode='$ProductCode'";
        $query = $this->db->query($sql);

        if($query !== false){
            return true;
        }
        return false;
    }
    
    public function dochangeFactoryQAStatus($DCWarrantyId,$ProductCode)
    {
        $time = date("Y-m-d H:i:s");
        $sql = "UPDATE DealarWarrantyClaimProduct SET FactoryQA=1,FactoryQATime='$time' WHERE DCWarrantyID=$DCWarrantyId AND ProductCode='$ProductCode'";
        $query = $this->db->query($sql);

        if($query !== false){
            return true;
        }
        return false;
    }
    
    public function dochangeFactoryQARejectStatus($DCWarrantyId,$ProductCode)
    {
        $time = date("Y-m-d H:i:s");
        $sql = "UPDATE DealarWarrantyClaimProduct SET FactoryQA=2,FactoryQATime='$time' WHERE DCWarrantyID=$DCWarrantyId AND ProductCode='$ProductCode'";
        $query = $this->db->query($sql);

        if($query !== false){
            return true;
        }
        return false;
    }

    public function reportServiceRatio($datefrom, $dateto, $customercode, $productcode) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        
        $sql = "EXEC usp_reportDealarServiceRatio '$datefrom','$dateto','$customercode','$productcode'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function reportInTimeServiceRatio($datefrom, $dateto, $customercode, $reporttype) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();


        $sql = "EXEC Usp_reportInTimeServiceRatio '$datefrom','$dateto 23:59:59','$customercode','$reporttype'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function reportsupplyratio($datefrom, $dateto, $customercode) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "EXEC sp_DealerWiseSupplyRatio '$datefrom','$dateto','P','$customercode'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    
    public function updateFreeServiceFollowUP($remark, $freesscheduleid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

            $sql = "UPDATE FreeServiceSchedule SET
                        Remark = '$remark'
                WHERE FreeSScheduleID = $freesscheduleid";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return '1';
        } else {
            return '0';
        }
    }   
    
    public function doLoadWarrentyFirstTime() {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        
        $sql = "EXEC usp_doLoadWarrentyFirstTime";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
//print_r($e); exit();
        if ($query !== false) {
            $data['msgtype'] = 'success';
            $data['success'] = true;
            $data['WarrantyType']           = $query->result_array();
            $data['WarrantySource']         = $query->next_result();
            $data['WarrantySeriousness']    = $query->next_result();
            $data['WarrantyInvoiceType']    = $query->next_result();
            $data['ServiceSchedule']        = $query->next_result();            
            $data['WarrantyProblemIs']      = $query->next_result();
            $data['WarrantyRemedy']         = $query->next_result();
            $data['WarrantyProblemResult']  = $query->next_result();
            $data['Occupation']             = $query->next_result();
            return $data;
        } else {
            $data['WarrantyType']           = '';
            $data['WarrantySource']         = '';
            $data['WarrantySeriousness']    = '';
            $data['WarrantyInvoiceType']    = '';
            $data['ServiceSchedule']        = '';
            $data['WarrantyProblemIs']      = '';
            $data['WarrantyRemedy']         = '';
            $data['WarrantyProblemResult']  = '';
            $data['Occupation']             = '';
            return $data;
        }
    }
    
    public function doLoadWarrentyDetails($warrantyid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $sql = "exec usp_doLoadWarrentyDetails '$warrantyid'"; 

        $query = $this->db->query($sql);
        if ($query !== false) {
            $data['deatilsdata']    = $query->result_array();
            $data['imagesdata']     = $query->next_result();
        }

        return $data;
    }

    public function doLoadcustomerLubeBuyingInformation($datefrom, $dateto, $customercode, $pagelimit = null, $page = null, $searchstring = null, $userid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        if (empty($customercode)) {
            $customercode = '';
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

        $sql = "EXEC usp_doLoadCustomerLubeBuyingInformation '$datefrom','$dateto','$customercode','$pagelimit','$page'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['WarrantyReport'] = $query->result_array();
            $data['Paging'] = $query->next_result();
        }
        // echo "<pre />"; print_r($data)		; exit();
        return $data;
 
    }

    public function getClaimWarrantyChartData($datefrom, $dateto)
    {
        if(!empty($datefrom)){
            $sql = "SELECT count(Status) as counter from DealarWarrantyClaim
                    where WCDate between '$datefrom' and '$dateto'
                    group by Status order by Status asc";
            $query = $this->db->query($sql);
            if($query){
                // echo "<pre />"; print_r($query->result_array())		; exit();
                return $query->result_array();
            }else{
                return [];
            }
        }
        else{
            $sql = "SELECT count(Status) as counter from DealarWarrantyClaim
            group by Status order by Status asc";
            $query = $this->db->query($sql);
            if($query){
                return $query->result_array();
            }else{
                return [];
            }
        }
    }
    
    public function getClaimWarrantyChartDataHeading($datefrom, $dateto)
    {
        if(!empty($datefrom)){
            $sql = "SELECT 
                    CASE 
                        WHEN Status = 0 THEN 'Pending' 
                        WHEN Status = 1 THEN 'Approved' 
                        WHEN Status = 2 THEN 'Cancel' END Approved_Status
                    from DealarWarrantyClaim
                            where WCDate between '$datefrom' and '$dateto'
                            group by Status order by Status asc";
            $query = $this->db->query($sql);
            if($query){
                return $query->result_array();
            }else{
                return [];
            }
        }
        else{
            $sql = "SELECT 
                    CASE 
                        WHEN Status = 0 THEN 'Pending' 
                        WHEN Status = 1 THEN 'Approved' 
                        WHEN Status = 2 THEN 'Cancel' END Approved_Status
                    from DealarWarrantyClaim
                    group by Status order by Status asc";
            $query = $this->db->query($sql);
            if($query){
                // echo "<pre />"; print_r($query->result_array())		; exit();
                return $query->result_array();
            }else{
                return [];
            }
        }
    }
    
    public function getClaimWarrantyTotalCost($datefrom, $dateto)
    {
        if(!empty($datefrom)){
            // echo "<pre />"; print_r($datefrom)		; exit();
            $sql = "  SELECT 
            CASE 
                WHEN Status = 0 THEN 'Pending' 
                WHEN Status = 1 THEN 'Approved' 
                WHEN Status = 2 THEN 'Cancel' END Approved_Status,
            sum(isnull(DP.UnitPrice,0) + isnull(DP.ServiceCharge,0)) as Total_Cost,
            count(distinct dc.DCWarrantyID) Total
            from DealarWarrantyClaim DC
            left join DealarWarrantyClaimProduct DP on DP.DCWarrantyID=DC.DCWarrantyID 
            where DC.WCDate between '$datefrom' and '$dateto'
            group by DC.Status order by DC.Status asc";
            $query = $this->db->query($sql);
            if($query){
                // echo "<pre />"; print_r('hi')		; exit();
                return $query->result_array();
            }else{
                return [];
            }
        }
        else{
            $sql = "  SELECT 
                        CASE 
                            WHEN Status = 0 THEN 'Pending' 
                            WHEN Status = 1 THEN 'Approved' 
                            WHEN Status = 2 THEN 'Cancel' END Approved_Status,
                        sum(isnull(DP.UnitPrice,0) + isnull(DP.ServiceCharge,0)) as Total_Cost,
                        count(distinct DC.DCWarrantyID) as Total
                        from DealarWarrantyClaim DC
                        left join DealarWarrantyClaimProduct DP on DP.DCWarrantyID=DC.DCWarrantyID 
                        group by DC.Status order by Status asc";
            $query = $this->db->query($sql);
            if($query){
                // echo "<pre />"; print_r($query->result_array())		; exit();
                return $query->result_array();
            }else{
                return [];
            }
        }
    }

}
