<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function monthlyIncomeSlack() {
        $sql = "select * from MonthlyIncome";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }
    public function productIntroducingMedia() {
        $sql = "select * from ProductIntroducingMedia";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }
    public function interestInProduct() {
        $sql = "select * from InterestInProduct";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }
    public function popularBike() {
        $sql = "select * from PopularBike";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }
    public function bikeCC() {
        $sql = "select * from BikeCC";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }
    public function previousBikeUsage() {
        $sql = "select * from PreviousBikeUsage";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }
    public function causesForBuyingNewBike() {
        $sql = "select * from CauseForBuyingNewBike";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }

    public function doCreateSpareInvoice($userid, $invoicedate, $invoicetime, $customername, $address, $mobileno, $ipaddress, $parts, $mechanicscode,$affiliatorCode='',$affiliatorDiscount=0) {
        $data['success'] = 0;
        $data['message'] = 'Could not save invoice.';
        $data['invoiceid'] = 0;
        $e = '';

        // TODO:: need to update Procedure
        $sql = "exec usp_DIMasterInsertUpdateDelete 'INSERT', 0, '$userid', '$invoicedate', '$invoicetime', 
            '$customername', '', '', '$address', '$address', '$mobileno', '','', '0', '$ipaddress', '',
             '','','','','', '$mechanicscode', '0', '0', '0', '', '', '',
             '','','','','','','','','','','','','','','','',
             '$affiliatorCode','$affiliatorDiscount' ";
	// echo $sql; exit();	
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($query !== false) {
            $row = $query->result();
            $query->free_result();

            if (count($row) > 0) {
                $invoiceid = $row[0]->lastid;
                if (!empty($parts)) {
                    foreach ($parts as $part) {
                        $sql = "exec usp_DIDetailsInsertUpdateDelete 'INSERT', $invoiceid, '{$part['productcode']}', {$part['qnty']}, 
                        '', '', {$part['discount']}, 0";
                        

                        $query = $this->db->query($sql);
                        $e = $this->db->_error_message();
                        if ($e != '') {
                            break;
                        } else {
                            $query->free_result();
                          }
                    }
                }
            }
        }

        if ($e == "") {
            $data['success'] = 1;
            $data['message'] = 'Invoice has been saved sucessfully!';
            $data['invoiceid'] = $invoiceid;
        }

        return $data;
    }

    public function doInsertDealarInvoicePayment($invoiceid, $paymenttype, $emibankid, $amount, $swiperate, $swiperateamount) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $sql = " INSERT INTO DealarInvoicePayment (InvoiceID, PaymentType, EMIBankID, Amount, SwipeRate, SwipeRateAmount) "
                . " VALUES ('$invoiceid', '$paymenttype', '$emibankid', '$amount', '$swiperate', '$swiperateamount') ";

        $query = $this->db->query($sql);
        if ($query) {
            $data['success'] = true;
            return true;
        } else {
            return false;
        }
    }

    public function doCreateInvoice($userid, $invoicedate, $invoicetime, $customername, 
		$fathername, $mothername, $peraddress, $preaddress, $mobileno, $email, $nid, 
		$inquirysale, $ipaddress_nouse, $productcode, $quantity, $discount, $chassisno,
		$engineno, $ipaddress, $deteofbirth, $marriageday, $isemi, $installmentsize,
		$emibank, $mechanicscode, $emiamount, $emiinterestrate, $emiinterestpayable, 
         $exchangebrand, $exchangeengineno, $exchangechassisno, $occupationId, $districtCode, $upazillaCode, $salesStaffName, $salesStaffDesignation,
         $monthlyIncomeId,$productIntroducingMedia,$interestInProduct,$previouslyUsedBike,$causeForBuyingNewBike,$previousBikeCC,
         $previousBikeUsage,$YRCisKnown,$wantJoinYRC, $gender, $ownertype) {
        $data['success'] = 0;
        $data['message'] = 'Could not save invoice.';
        $data['invoiceid'] = 0;
        $e = '';

        $exists = $this->getCheckBikeExists($chassisno);
        if (empty($exists[0]['cexists']) || !isset($exists[0]['cexists'])) {
            $sql = "exec usp_DIMasterInsertUpdateDelete 'INSERT', 0, '$userid', '$invoicedate', '$invoicetime', 
                '$customername', '$fathername', '$mothername', '$peraddress', '$preaddress', '$mobileno', '$email',
                '$nid', '$inquirysale', '$ipaddress', '', '$deteofbirth', '$marriageday', $isemi, '$installmentsize', '$emibank', '$mechanicscode',"
                    . "'$emiamount', '$emiinterestrate', '$emiinterestpayable', '$exchangebrand', '$exchangeengineno', '$exchangechassisno',"
                     ." '$occupationId', '$districtCode', '$upazillaCode', '$salesStaffName', '$salesStaffDesignation','$monthlyIncomeId',"
                     ." '$productIntroducingMedia','$interestInProduct','$previouslyUsedBike','$causeForBuyingNewBike','$previousBikeCC',"
                     ." '$previousBikeUsage','$YRCisKnown','$wantJoinYRC', '$gender', '$ownertype' ";
//echo $sql; exit();
            $query = $this->db->query($sql);
            $e = $this->db->_error_message();
            if($e !='') {               
                    file_put_contents('application/logs/invoice_create_FromModel.txt',json_encode($e));
               
            }
            if ($query !== false) {
                $row = $query->result();
                $query->free_result();

                if (count($row) > 0) {
                    $invoiceid = $row[0]->lastid;
                    $verifycode = $row[0]->verifycode;
                    $invoiceno = $row[0]->invoiceno;

                    if ($_FILES['photo']['size'] > 0) {
                        $upload_path = "upload/invoice/";
                        $config = array(
                            'upload_path' => $upload_path,
                            'allowed_types' => "gif|jpg|png|jpeg",
                            'overwrite' => false,
                            'max_size' => "2048000",
                            'file_name' => str_replace("/", "_", $invoiceno) . substr($_FILES['photo']['name'], -4)
                        );
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('photo')) {
                            $data['imageError'] = $this->upload->display_errors();
                        }
                        $sql = "UPDATE DealarInvoiceMaster SET Picture = '" . $config['file_name'] . "'
                            WHERE InvoiceID = $invoiceid";
                        $query = $this->db->query($sql);
                    }

                    $sql = "exec usp_DIDetailsInsertUpdateDelete 'INSERT', $invoiceid, '$productcode', $quantity, '$chassisno', 
                        '$engineno', $discount";

                    $query = $this->db->query($sql);
                    $e = $this->db->_error_message();
                    if ($e == '') {
                        $query->free_result();
                        $sql = "exec usp_DReceiveDetailsSalesUpdate '$chassisno', '$userid'";
                        $query = $this->db->query($sql);
                        $e = $this->db->_error_message();
                        $query->free_result();
                        if ($e == '') {
                            $sql = "exec usp_FreeServiceScheduleInsert '$chassisno'";
                            $query = $this->db->query($sql);
                            $e = $this->db->_error_message();
                        }
                    }
                }
            }
        } else {
            $e = 'Bike already sold.';
            $data['message'] = 'Chasiss no already sold to ' . $exists['custname'];
        }

        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = 'Invoice has been saved sucessfully!';
            $data['invoiceid'] = $invoiceid;
            $data['invoiceno'] = $invoiceno;
            $data['verifycode'] = $verifycode;
        }

        return $data;
    }

    public function doLoadBikes($userid, $chassisno) {
        $data['success'] = 0;
        $data['msgtype'] = 'error';
        $data['data'] = array();
        $sql = " EXEC usp_LoadBikeList '$userid', '$chassisno' ";

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

    public function doLoadBikeDetails($chasisno) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $sql = "exec usp_LoadBikeDetails '$chasisno' ";

        $query = $this->db->query($sql);
        if ($query) {
            $data['success'] = true;
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doInvoiceInfo($invoiceid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = "exec usp_LoadInvoiceDetails $invoiceid ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }

    public function doSpareInvoiceInfo($invoiceid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $data['inv'] = array();
        $data['pros'] = array();

        $rows = array();
        $pros = array();
        $sql = "exec usp_LoadSpareInvoiceDetails $invoiceid ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result();
            $pros = $query->next_result();
            $data['success'] = 1;
            $data['inv'] = $rows;
            $data['pros'] = $pros;
        }
        return $data;
    }

    public function doLoadMysales($mastercode, $baseurl = '', $pagelimit, $pagenumber) {
        $data['success'] = 0;
        $data = array(
            "sEcho" => 1,
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "data" => array()
        );
        $sql = "exec usp_LoadMySales '$mastercode','$baseurl','$pagelimit','$pagenumber'";

        $pattern = '/[^A-Za-z0-9\. -#@]/';
        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            //echo count($row); exit();
            $data['iTotalRecords'] = count($row);
            $data['iTotalDisplayRecords'] = 10;
            
            //echo "<pre />"; print_r($row); exit();
            for ($i=0; $i < count($row); $i++) {
				$d = preg_replace($pattern, '', $row[$i]->customername);
				$row[$i]->customername = $d;

                $d = preg_replace($pattern, '', $row[$i]->productname);
				$row[$i]->productname = $d;
			}

            $data['data'] = $row;
            $data['paging'] = $query->next_result();
            $query->free_result();
        }

        return $data;
    }

    private function getCheckBikeExists($chasissno) {
        $rows = array();
        $sql = "exec usp_CheckBikeExists '$chasissno' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }

    public function reportInvoice($datefrom, $dateto, $customercode, $productcode, $reporttype, $pagelimit, $page, $searchstring = null) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
		
		$data['Invoice'] = array();
		$data['Paging'] = array();
		$data['Header'] = array();
		
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

        $sql = "EXEC usp_reportInvoice '$datefrom','$dateto','$customercode','$productcode', '$reporttype', '$pagelimit', '$page','$searchstring' ";
        // die($sql);
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['Invoice'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        return $data;
    }
    
    
    public function reportAffiliatorDiscountInvoice($datefrom, $dateto, $customercode, $productcode, $reporttype, $pagelimit, $page, $searchstring = null) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
		
		$data['Invoice'] = array();
		$data['Paging'] = array();
		$data['Header'] = array();
		
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

        $sql = "EXEC usp_reportInvoiceAffiliatorDiscount '$datefrom','$dateto','$customercode','$productcode', '$reporttype', '$pagelimit', '$page','$searchstring' ";
        // die($sql);
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['Invoice'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        return $data;
    }

    public function doLoadEMIInstallment() {
        $rows = array();
        $sql = "exec usp_LoadEMIInstallment";
        $query = $this->db->query($sql);
        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }

    public function doLoadEMIBank() {
        $rows = array();
        $sql = "exec usp_LoadEMIBank";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }
    
    public function doLoadCompititorBrand() {
        $rows = array();
        $sql = "SELECT * FROM CompititorBrand WHERE Active = 'Y' ORDER BY OrderSL";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }
    
    public function getTenderType(){         
        $sql = "SELECT 
                    EMIBankID TenderId, EMIBankName TenderType,
                    SwipeCharge 
                 FROM EMIBank 
                 WHERE SwipeCharge IS NOT NULL
                 ORDER BY 1";              //    exit();
        $result['success'] = false;
        $query = $this->db->query($sql);
        $data = array();
        if ($query) {
            $rows = $query->result_array();
        }        
        return $rows;
    }
    
    public function getPayableInterest($emibank, $installmentsize, $emiamount){         
        $sql = "SELECT
                        CONVERT(NUMERIC(18,0), ((($emiamount/100) * InterestRate) / $installmentsize) * ($installmentsize - 6)) AS Interest, InterestRate
                FROM EMIBankInterestRate 
                WHERE EMIBankID = $emibank
                        AND InstallmentSize = $installmentsize";              //    exit();
        //echo $sql; exit();
        $result['success'] = false;
        $query = $this->db->query($sql);
        $data = array();
        if ($query) {
            $rows = $query->result_array();
        }        
        return $rows;                
    }

    public function doLoadDealerInoviceBikeDetails($chassisno) {
        $rows = array();
        $sql = "
                SELECT 
                    *
                FROM DealarInvoiceMaster DIM
                    INNER JOIN DealarInvoiceDetails DID
                        ON DIM.InvoiceID = DID.InvoiceID
                WHERE ChassisNo = '$chassisno' 
            ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }

    public function doCheckCanPrint($chassisno)
    {
        $rows = array();
        $sql = "select top 1  * From DealearInvoiceDocument WHERE ChassisNo = '$chassisno'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->row();
        }
        return $rows;
    }
    
    public function invoiceEdit($invId) {
        $rows = array();
        $sql = "
                SELECT 
                    *
                FROM DealarInvoiceMaster 
                WHERE InvoiceID = '$invId' 
            ";
        $query = $this->db->query($sql);

        if ($query) {
            $rows = $query->result_array();
        }
        return $rows;
    }
    
    public function saveInvoiceEdit($data) {
        $rows = array();
        // echo "<pre/>";print_r($data["invoiceIdHide"]);exit();
        $invId = $data["invoiceIdHide"];
        $CustomerName = $data["CustomerName"];
        $EMail = $data["EMail"];
        $MobileNo = $data["MobileNo"];
        $FatherName = $data["FatherName"];
        $MotherName = $data["MotherName"];
        $PreAddress = $data["PreAddress"];
        $PerAddress = $data["PerAddress"];
        $NID = $data["NID"];

        $sql = "UPDATE DealarInvoiceMaster SET CustomerName='$CustomerName',EMail='$EMail',MobileNo='$MobileNo',
                FatherName='$FatherName',MotherName='$MotherName',PreAddress='$PreAddress',PerAddress='$PerAddress',
                NID='$NID'
                WHERE InvoiceID = '$invId' 
            ";
        $query = $this->db->query($sql);

        if ($query) {
            $sql2 = "SELECT * FROM DealarInvoiceMaster WHERE InvoiceID = '$invId'";
            $query2 = $this->db->query($sql2);
            $rows = $query2->result_array();
        }
        return $rows;
    }

    public function deleteInvoice($chassisno, $userid, $ipaddress, $divicestate) {
        $rows = array();
        $sql = "EXEC usp_deleteInvoice '$chassisno','$userid','$ipaddress','$divicestate' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }
    
    public function reportSalesSummery($datefrom, $dateto, $customercode, $reporttype) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        
        $sql = "EXEC usp_reportRetailSalesSummary '$datefrom','$dateto','$customercode','$reporttype'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['salesdata'] = $query->result_array();
            $data['datedetails'] = $query->next_result();
        }
        return $data;
    }
    
    public function doLoadDMSInvoiceStatus($invoiceno) {
        $sql = "EXEC usp_doLoadDMSInvoiceStatus '$invoiceno' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['invoicesummery']     = $query->result_array();
            $data['successsummery']     = $query->next_result();
            $data['invoicedetails']     = $query->next_result();
            return json_encode($data);
        } else {
            return false;
        }
    }

    public function doGetInvoiceMasterInfo($invoiceno, $userid) {
        $sql = "SELECT InvoiceID,InvoiceNo FROM DealarInvoiceMaster WHERE MasterCode='$userid' and InvoiceNo='$invoiceno'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doInsertReturnInvoiceDetails($data, $CurrentQty, $CurrentDiscount, $InvoiceDetailsID){

        $sql = "UPDATE DealarInvoiceDetails SET Quantity = '$CurrentQty',	Discount = '$CurrentDiscount' 
                WHERE InvDetailsID = '$InvoiceDetailsID' ";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
		if(!empty($e)){ echo $e . $sql; exit(); } 

        if ($query !== false) {
            $insert = $this->db->insert('ReturnDealarInvoiceLog', $data);
            if ($insert == true) {
                $updateColumn = array('Quantity' => $data['CurrentQty'], 'Discount' => $data['CurrentDiscount']);
                $this->db->where('InvoiceID', $data['InvoiceID']);
                $this->db->where('InvDetailsID', $data['InvoiceDetailsID']);
                $this->db->update('DealarInvoiceDetails', $updateColumn);
                return true;
            } else {
                return false;
            }
        }
    }

}
