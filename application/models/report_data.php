<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Report_data extends CI_Model {

        public function __construct() {
            parent::__construct();
        }
          /*
        public function CustomerList($CustomerListId){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            $sql = " SELECT * FROM Customer WHERE CustomerCode LIKE '$CustomerListId' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }
        
        public function ProductList($producttype){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            $sql = " SELECT * FROM Product WHERE Business = '$producttype' ORDER BY ProductName ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }
        
        public function reportOrder($DateFrom,$DateTo,$CustomerCode,$ProductCode,$reporttype, $pagelimit, $page){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            if(empty($CustomerCode)){ $CustomerCode = '%'; }
            if(empty($ProductCode)){ $ProductCode = '%'; }
            if(empty($pagelimit)){ $pagelimit = '0'; }
            if(empty($page)){ $page = '%'; }
            $sql = "EXEC  usp_reportOrder '$CustomerCode', '$DateFrom','$DateTo','$ProductCode', '$reporttype', '$pagelimit', '$page'  ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $data['Order'] = $query->result_array();
                $data['Paging'] = $query->next_result();
            }
            return $data;
        }      */
        
        public function reportinquiry($DateFrom,$DateTo,$CustomerCode,$ProductCode,$reporttype){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            if(empty($CustomerCode)){ $CustomerCode = '%'; }
            if(empty($ProductCode)){ $ProductCode = '%'; }
            $sql = "EXEC Usp_reportDealerCustomerInquiry '$DateFrom','$DateTo','$CustomerCode','$ProductCode','$reporttype' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }
        
        public function reportWalkInVisitor($DateFrom,$DateTo,$CustomerCode){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            if(empty($CustomerCode)){ $CustomerCode = '%'; }
            $sql = "EXEC usp_reportWalkInVisitor '$DateFrom','$DateTo','$CustomerCode'";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }
        /*
        public function reportInvoice($DateFrom,$DateTo,$CustomerCode,$ProductCode,$reporttype){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            if(empty($CustomerCode)){ $CustomerCode = '%'; }
            if(empty($ProductCode)){ $ProductCode = '%'; }
            $sql = "EXEC usp_reportInvoice '$DateFrom','$DateTo','$CustomerCode','$ProductCode', '$reporttype' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }
        
        public function reportfreeservice($DateFrom,$DateTo,$CustomerCode,$ProductCode,$reporttype){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            if(empty($CustomerCode)){ $CustomerCode = '%'; }
            if(empty($ProductCode)){ $ProductCode = '%'; }
            $sql = "EXEC Usp_reportEstimatedFreeService '$DateFrom','$DateTo','$CustomerCode','$ProductCode', '$reporttype' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }
        
        public function ServiceList($DateFrom,$DateTo,$CustomerCode,$reporttype){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            if(empty($CustomerCode)){ $CustomerCode = '%'; }
            $sql = "EXEC Usp_reportDealerService '$DateFrom','$DateTo','$reporttype','$CustomerCode' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }
        /*
        public function reportStock($CustomerCode,$ProductCode,$reporttype){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            if(empty($CustomerCode)){ $CustomerCode = '%'; }
            if(empty($ProductCode)){ $ProductCode = '%'; }
            $sql = "EXEC usp_reportStock '$CustomerCode','$ProductCode','$reporttype' ";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }       
         */

        public function doLoadInvoiceRatingReport($from,$to,$code){
            $sql = "SELECT 
                    * 
                FROM
                (
                    SELECT
                        DIM.InvoiceNo,DIM.CustomerName, CM.CustomerCode, CM.CustomerName as DealerName, DIM.MobileNo,DID.EngineNo,DID.ChassisNo, CFA.Result,CFA.QuestionId
                    FROM
                        DealarInvoiceMaster DIM
                        INNER JOIN CustomerFeedbackAnswer CFA
                            ON CFA.InvoiceId = DIM.InvoiceID
                        INNER JOIN Customer CM
                            ON CM.CustomerCode = DIM.MasterCode
                        INNER JOIN DealarInvoiceDetails DID on DID.InvoiceID = DIM.InvoiceID
                        WHERE ('' = '$code' OR DIM.MasterCode = '$code') AND CFA.FeedbackTime BETWEEN '$from' AND '$to'
                ) info
                PIVOT(
                    max(Result)
                    FOR QuestionId IN (
                        [1],
                        [2],
                        [3],
                        [4],
                        [5]
                        )
                ) AS pivot_table";

            $result['success'] = false;
            $query = $this->db->query($sql);
            $data = array();
            if ($query) {
                $data = $query->result_array();
            }
            return $data;
        }

        public function doLoadComparisonReport($Period,$PeriodSPLY,$customer_code,$territory_code){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();

            $sql = "EXEC usp_doLoadCustomerPrimarySecoundaryComparison '$customer_code', '$Period', '$PeriodSPLY', '$territory_code' ";
            $query = $this->db->query($sql);

            if($query !== false){
                $rows = $query->result_array();
            }
            return $rows;
         }

         public function teritoryList(){
             $data['success'] = false;
             $data['msgtype'] = 'error';
             $rows = array();

             $sql = "select * From Territory WHERE Business = 'C'";
             $query = $this->db->query($sql);

             if($query !== false){
                 $rows = $query->result_array();
             }
             return $rows;
         }

         public function doLoadServiceSummaryReport($from,$to,$code){
            $sql = "exec usp_doLoadServiceSummaryReport '$from', '$to', '$code'";

            $result['success'] = false;
            $query = $this->db->query($sql);
            $data = array();
            if ($query) {
                $data = $query->result_array();
            }
            return $data;
        }

        public function doLoadBikeReceivePending(){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            $sql = "exec usp_doLoadBikeReceiveAblePending";        
            $query = $this->db->query($sql);             

            if($query !== false){                                       
                $rows = $query->result_array();
            }
            return $rows;
        }

        public function doLoadProductInvoiceByInvoiceNumber($InvoiceId){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            $sql = "";
            $query = $this->db->query($sql);

            if($query !== false){
                $rows = $query->result_array();
            }
            return $rows;
        }

        //Load Invoice Survey Report Data
        public function loadInvoiceSurveyReportData($dateFrom,$dateTo,$customercode, $pagelimit, $page, $searchstring = null){
            $data['success'] = false;
            $data['msgtype'] = 'error';
            $rows = array();
            if(empty($pagelimit)){ $pagelimit = '0'; }
            if(empty($page)){ $page = '%'; }
            if(isset($searchstring)  and $searchstring == 'undefined'){ $searchstring = ''; }

            $dateFrom = $dateFrom. ' 23:59:59.000';
            $dateTo = $dateTo. ' 23:59:59.000';

            $sql = "EXEC usp_invoicereceivesurveyreport '$dateFrom','$dateTo','$customercode','$pagelimit', '$page'";
            $query = $this->db->query($sql);

            if($query !== false){
                $data['receivedata'] = $query->result_array();
                $data['question'] =$query->next_result();

            }
            return $data;
        }
           
    }
