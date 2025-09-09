<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class stock_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doLoadMyStock($mastercode, $producttype) {
        $data['success'] = 0;

        $sql = "exec usp_dealerCurrentStock '$mastercode','$producttype'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $data['first'] = $query->result_array();
            $data['secound'] = $query->next_result();
            $data['success'] = 1;
        }
        $query->free_result();

        return $data;
    }


    public function doLoadMyStockMSL($mastercode, $producttype) {
        $data['success'] = 0;

        $sql = "exec usp_dealerCurrentStockMSL '$mastercode','$producttype'";
//echo $sql; exit();
        $query = $this->db->query($sql);
        if ($query !== false) {
            $data['first'] = $query->result_array();
           // $data['secound'] = $query->next_result();
            $data['success'] = 1;
        }
        $query->free_result();

        return $data;
    }

    public function reportStock($customercode, $productcode, $reporttype, $pagelimit, $page, $searchstring = null) {
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
        if (isset($searchstring) and $searchstring == 'undefined') {
            $searchstring = '';
        }
        $sql = "EXEC usp_doLoadReportdealerCurrentStock '$customercode','P','$pagelimit','$page'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['Stock'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        return $data;
    }
    
    public function reportProductStock($CustomerCode,$ProductCode,$reporttype, $pagelimit, $page){
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        if(empty($CustomerCode)){ $CustomerCode = '%'; }
        if(empty($ProductCode)){ $ProductCode = '%'; }
        $sql = "EXEC usp_reportStock '$CustomerCode','$ProductCode','$reporttype','$pagelimit','$page' ";        
        $query = $this->db->query($sql);             

        if($query !== false){                                       
            $data['Stock'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Header'] = $query->next_result();
        }
        // print "<pre />"; print_r($data); exit(); 
        return $data;
    }

    public function reportOCStock($datefrom, $dateto, $customercode, $productcode, $report_type) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        if ($report_type == 'Bike') {
            $sql = "EXEC usp_reportCustomerOpeningClosingStock '$datefrom','$dateto','$customercode','$productcode'";
        }elseif($report_type == 'Spare_Part') {
            $sql = "EXEC usp_reportCustomerOpeningClosingStockSP '$datefrom','$dateto','$customercode','$productcode'";
        }
		//echo $sql; 
        $query = $this->db->query($sql);

        if ($query !== false) {
            $result =  $query->result_array();
			$pattern = '/[^A-Za-z0-9\. -#@]/';
			//echo "<pre />"; print_r($result); exit();
			for ($i=0; $i < count($result); $i++) {
				$d = preg_replace($pattern, '', $result[$i]['CustomerCode']);
				$result[$i]['CustomerCode'] = $d;
				$d = preg_replace($pattern, '', $result[$i]['CustomerName']);
				$result[$i]['CustomerName'] = $d;				
				$d = preg_replace($pattern, '', $result[$i]['ProductCode']);
				$result[$i]['ProductCode'] = $d;
				$d = preg_replace($pattern, '', $result[$i]['ProductName']);
				$result[$i]['ProductName'] = $d;								
			}
			return $result;
        } else {
            return false;
        }
    }
    
    public function reportStockAllocaiton($customercode, $productcode) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT 
                    P.ProductCode, P.ProductName, A.RackName
                FROM ProductRackAllocation A
                    INNER JOIN Product P
                        ON A.ProductCode = P.ProductCode
                WHERE CustomerCode = '$customercode'
                        AND ('' = '$productcode' OR P.ProductCode = '$productcode')";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doInsertRackAllocation($customercode, $productcode, $rackname) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "exec usp_doInsertRackAllocation '$customercode', '$productcode', '$rackname' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }

}
