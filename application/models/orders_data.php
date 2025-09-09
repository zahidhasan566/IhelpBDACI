<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Orders_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }      
    
    public function doCreateOrder($mastercode, $orders) {
        $data['success'] = 0;
        $data['message'] = 'Order can not be saved successfully!';
        $orderdate = date('Y-m-d', strtotime("now"));
        $ordertime = date('Y-m-d H:i:s', strtotime("now"));
        $ipaddress = get_ip_address();
        $sql = "exec usp_OrderInsertUpdateDelete 'INSERT', 0, '$mastercode',
            '$orderdate', '$ordertime', '$ipaddress'";
         
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        if ($query !==false){
            $row = $query->result();
            $query->free_result();
            $orderno = $row[0]->orderno;
            foreach($orders as $ord) {
                $sql = "exec usp_OrderDetailsInsertUpdateDelete 'INSERT', $orderno, '".$ord['productcode']."',".$ord['qnty'];
                $query = $this->db->query($sql);
                $e = $this->db->_error_message();
                $query->free_result();
                if ($e != '') break;
            }
        }
        
        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = 'Order has been saved sucessfully.';
        } else {
            $data['message'] = $e;
        }

        return json_encode($data);
    }
    public function doCreateReceive($mastercode, $invoiceno) {
        $data['success'] = 0;
        $data['message'] = 'Invoice could not be received successfully!';
        $ipaddress = get_ip_address();
        $sql = "exec usp_DealarReceiveInsert '$mastercode', '$invoiceno'";
         
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        
        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = 'Invoice has been received sucessfully.';
        } else {
            $data['message'] = $e;
        }

        return json_encode($data);
    }
    
    public function doDeleteOrder($orderno) {
        $data['success'] = 0;
        $data['message'] = 'Order can not be deleted!';
        $sql = "exec usp_OrderInsertUpdateDelete 'DELETE', $orderno";
         
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();        
                
        if ($e == '') {
            $data['success'] = 1;
            $data['message'] = 'Order has been deleted sucessfully.';
        } else {
            $data['message'] = $e;
        }

        return json_encode($data);
    }
    
    public function doLoadMyOrders($mastercode) {
        $data['success'] = 0;        
        $data = array(
                "sEcho" => 1,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "data" => array()
            );
        $sql = "exec usp_LoadMyOrders '$mastercode'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['iTotalRecords'] = count($row);            
            $data['iTotalDisplayRecords'] = 10;            
            $data['data'] = $row;            
        }
        $query->free_result();

        return $data;
    }
    
    public function doLoadMyReceivable($mastercode) {
        $data['success'] = 0;        
        $data = array(
                "sEcho" => 1,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "data" => array()
            );
        $sql = "exec usp_LoadMyReceivable '$mastercode'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['iTotalRecords'] = count($row);            
            $data['iTotalDisplayRecords'] = 10;            
            $data['data'] = $row;            
        }
        $query->free_result();

        return $data;
    }
	
	public function reportOrder($datefrom,$dateto,$customercode,$productcode,$reporttype, $pagelimit, $page, $searchstring = null){
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        if(empty($customercode)){ $customercode = '%'; }
        if(empty($productcode)){ $productcode = '%'; }
        if(empty($pagelimit)){ $pagelimit = '0'; }
        if(empty($page)){ $page = '%'; }
        if(isset($searchstring)  and $searchstring == 'undefined'){ $searchstring = ''; }
        
        $sql = "EXEC  usp_reportOrder '$customercode', '$datefrom','$dateto','$productcode', '$reporttype', '$pagelimit', '$page' , '$searchstring'  ";        
        $query = $this->db->query($sql);             

        if($query !== false){                                       
            $data['Order'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Haeder'] = $query->next_result();
        }
        return $data;
    }

    //Yamaha feedback report
    public function excelreportfeedback($dateFrom,$dateTo, $pagelimit, $page, $searchstring = null){
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        if(empty($pagelimit)){ $pagelimit = '0'; }
        if(empty($page)){ $page = '%'; }
        if(isset($searchstring)  and $searchstring == 'undefined'){ $searchstring = ''; }

        $dateFrom = $dateFrom. ' 23:59:59.000';
        $dateTo = $dateTo. ' 23:59:59.000';
        $sql = "EXEC  usp_excelreportyamahafeedback  '$dateFrom','$dateTo', '$pagelimit', '$page' , '$searchstring'  ";
        $query = $this->db->query($sql);


        if($query !== false){
            $data['receivedata'] = $query->result_array();
            $data['Paging'] = $query->next_result();
            $data['Haeder'] = $query->next_result();
        }
        return $data;
    }
    public function getInvoiceReceiveSurveyData(){
        $sql = "select  SurveyQuestionID, SurveyQuestion  FROM InvoiceReceiveSurveyQuestions";
        $sql_surAns = "select  SurveyAnswerID, SurveyAnswer,SurveyQuestionID  FROM InvoiceReceiveSurveyAnswers";
        $query = $this->db->query($sql);
        $queryAns =  $this->db->query($sql_surAns);

        if ($query !== false && $queryAns !== false) {
            $survey =[];
            $survey['questions'] = $query->result_array();
            $survey['answers'] = $queryAns->result_array();
            return $survey;
        }else{
            return false;
        }

    }
}
