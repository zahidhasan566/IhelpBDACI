<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logistics_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doCheckCurporateInvoice($invoiceno) {
        $data['success'] = 0;
        $sql = "exec doLoadInvoiceDetails  '$invoiceno' ";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }
    public function doLoadInvoiceDetailsLostDocument($invoiceno){
        $data['success'] = 0;
        $sql = "exec doLoadInvoiceDetailsLostDocument  '$invoiceno' ";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }
    
    public function doInsertDealearInvoiceDocument($invoiceno, $customercode,
                $chassisno, $engineno, $productcode, $entryby, $ipaddress,
                $senddate) {
        $data['success'] = 0;
        $sql = "exec usp_doInsertDealearInvoiceDocument  '$invoiceno', '$customercode',
                '$chassisno', '$engineno', '$productcode', '$entryby', '$ipaddress','$senddate' ";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return false;
        }else{
            return true;
        }        
    }
    
    public function doCheckAlreadySend($invoiceno) {
        $data['success'] = 0;
        $sql = "SELECT COUNT(*) FROM DealearInvoiceDocument WHERE Invoiceno = '$invoiceno' ";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }
    
    public function doLoadDealarPendingDocument($customercode){
        $data['success'] = 0;
        $sql = "SELECT 
                    D.InvoiceNo AS Invoice_No, C.CustomerCode + ' - ' + C.CustomerName Customer, 
                    P.ProductCode + ' - ' + P.ProductName Product, 
                    Chassisno, EngineNo,
                    CONVERT(VARCHAR(11), D.SendDate, 13) SendDate					
                FROM DealearInvoiceDocument D
                    INNER JOIN Customer C
                        ON D.CustomerCode = C.CustomerCode
                    INNER JOIN Product P
                        ON P.ProductCode = D.ProductCode
                WHERE D.CustomerCode = '$customercode'
                        AND ReceiveDate IS NULL ORDER BY 1 ";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }
    
    public function doUpdateDocumentStatus($chassisno, $action, $userid, $receivedate) {
        $data['success'] = 0;
        $sql = "UPDATE DealearInvoiceDocument SET
                    IsReceive = 'Y',
                    ReceiveDate = '$receivedate',
                    ReceiveIpAddress = '".$_SERVER['REMOTE_ADDR']."',
                    ReceiveBy = '$userid'
                WHERE Chassisno = '$chassisno'";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return true;
        }else{
            return false;
        }
    }
    
    public function doUpdateDocumentStatusSMSInfo($chassisno, 
            $sendsmsiserror, $insertedsmsids, $smsmessage) {
        $data['success'] = 0;
        $sql = "UPDATE DealearInvoiceDocument SET
                    SendSMSIsError	= '$sendsmsiserror',
                    InsertedSmsIds	= '$insertedsmsids',
                    SMSMessage		= '$smsmessage'
                WHERE Chassisno		= '$chassisno'";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return true;
        }else{
            return false;
        }
    }
    
    public function reportLostdocument ($datefrom, $dateto, $customercode,
                    $chassisno, $reporttype,$invoiceno){
        $data['success'] = 0;
        $sql = "exec usp_doLoadLogisticsLostDocumentReport'$datefrom', '$dateto', '$customercode',
                    '$chassisno','$invoiceno','$reporttype'";

        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }
    public function reportReceiveReport($datefrom, $dateto, $customercode,
                                        $chassisno, $reporttype){
        $data['success'] = 0;
        $sql = "exec usp_doLoadLogisticsDocumentReport '$datefrom', '$dateto', '$customercode', 
                    '$chassisno','$reporttype'";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }
    
    public function reportReceiveReportSummery($datefrom, $dateto, $customercode){
        $data['success'] = 0;
        $sql = "
                SELECT 
                        D.CustomerCode, C.CustomerName, COUNT(ReceiveId) Units  
                FROM DealearInvoiceDocument D
                        INNER JOIN Customer C
                                ON D.CustomerCode = C.CustomerCode
                WHERE SendDate BETWEEN '$datefrom' AND '$dateto 23:59:59.000'
                        AND ('' = '$customercode' OR D.CustomerCode = '$customercode')
                GROUP BY D.CustomerCode, C.CustomerName 
                ORDER BY 3 DESC";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }

    //Yamaha Feedback report data
    public function reportyamahafeedback ($datefrom, $dateto){
        $data['success'] = 0;
        $sql = "exec usp_doLoadYamahaFeedbackReport'$datefrom', '$dateto'";

        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }

    public function getFeedbackData (){
        $data['success'] = 0;
        $sql = "Select * from YamahaFeedback";

        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }

    public function getChallan($dateFrom,$dateTo,$customerCode = '',$challanNo='') {
        $sql = "select C.ChallanID,ChallanNumber,C.EntryDate,C.ChallanImage,Cus.CustomerName
                from Challan  C
                join Customer Cus on Cus.CustomerCode=C.EntryBy
                where ('' = '$customerCode' or C.EntryBy='$customerCode') 
                    and C.EntryDate between '$dateFrom' and '$dateTo'
                    and ('' = '$challanNo' or ChallanNumber = '$challanNo')";
        
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];

    }

    public function getChallanByNumber($challanNo) {
        $sql = "select C.ChallanID,ChallanNumber,C.EntryDate,C.ChallanImage
                from Challan  C
                where                     
                   ChallanNumber = '$challanNo'";
        
        $query = $this->db->query($sql);
        if($query && !empty($result = $query->result_array())) {
            return $result[0];
        }
        return [];

    }

    public function getBRTA_registration_status_list($dateFrom,$dateTo,$customerCode = '',$chassisNo='') {
        $sql = "select 
                RS.ChassisNO,
                RS.IssueDate,
                RS.IssueDate,
                RS.BRTA_RegistrationNumber,
                case
                    when RS.BRTA_BankDeposite = 'Y' then 'Done'
                    when RS.BRTA_BankDeposite = 'N' then 'Pending'
                    else ''
                end as BRTA_BankDeposite,
                case 
                    when RS.RegDocumentComplete = 'Y' then 'Done' 
                    when RS.RegDocumentComplete = 'N' then 'Pending'
                    else '' 
                end as RegDocumentComplete,
                case
                    when RS.FileReceivedByCustomer = 'Y' then 'Done'
                    when RS.FileReceivedByCustomer ='N' then 'Pending'
                end as FileReceivedByCustomer,
                case
                    when RS.UnregisteredOrDuePayment ='Y' then 'Done'
                    when RS.UnregisteredOrDuePayment='N' then 'Pending'
                    else ''
                end as UnregisteredOrDuePayment,                
                Cus.CustomerName as DealerName,DIM.CustomerName, DID.EngineNo,DID.ProductCode,DID.ProductName
                from BRTA_RegistrationStatus RS 
                join Customer Cus on Cus.CustomerCode=RS.EntryBy 
                join DealarInvoiceDetails DID on DID.ChassisNo=RS.ChassisNO
                join DealarInvoiceMaster DIM on DIM.InvoiceID=DID.InvoiceID
                where ('' = '$customerCode' or RS.EntryBy='$customerCode')
                    and ('' = '$chassisNo' or RS.ChassisNo='$chassisNo')
                    and RS.EntryDate between '$dateFrom' and '$dateTo'";
                // die($sql);

        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];

    }
    
}
