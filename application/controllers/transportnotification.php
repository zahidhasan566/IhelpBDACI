<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class TransportNotification extends MY_Controller {

        public function __construct() {
            parent::__construct();
            //$$this->load->library('security');
            $this->load->model('TransportNotification_Data','TransportNotification');
            $this->load->model('Dealer_data','Dealer');
            //$this->load->library('form_validation');
            $data = array();
        }
        public function create() {
            $data['success']    = 0;        
            $data['data']       = array();
		
			$data['userid']         = strtoupper(trim($this->session->userdata('userid')));
            $ipaddress              = $_SERVER['REMOTE_ADDR'];
            $actiontype             = mssql_escape($this->input->post("actiontype",TRUE));
            $actionid               = mssql_escape($this->input->post("actionid",TRUE));
            $customercode           = mssql_escape($this->input->post("customercode",TRUE));
            $dealermobile           = mssql_escape($this->input->post("dealermobile",TRUE));
            $transportid            = mssql_escape($this->input->post("transportid",TRUE));
            $contactno              = mssql_escape($this->input->post("contactno",TRUE));
            $truckno                = mssql_escape($this->input->post("truckno",TRUE));
            $drivercontactno        = mssql_escape($this->input->post("drivercontactno",TRUE));
            $deliverytime           = str_replace("T", " ", $this->input->post("deliverytime",TRUE));
			$drivername             = mssql_escape($this->input->post("drivercontactno",TRUE));
            $motmnumber             = mssql_escape($this->input->post("motmnumber",TRUE));
            $challanno             = mssql_escape($this->input->post("challanno",TRUE));
			//exit();
            
//echo str_replace("T", " ", $deliverytime); exit();
            $return = $this->TransportNotification->doInsertTransportNotification($actiontype, $actionid, $customercode,
                $dealermobile, $transportid, $contactno, $truckno, $drivercontactno, $deliverytime, $data['userid'], $ipaddress,
                $drivername, $motmnumber, $challanno);


            if($return['success'] == 1){
				if(!empty($dealermobile)){
					$smstext = "A truck has been released at your destination. Probable delivery schedule : {$deliverytime}.%0D%0ADriver Information. %0D%0A%0D%0ADriver mobile : {$drivercontactno }. %0D%0ATruck No : {$truckno}. %0D%0A%0D%0AFor more information please log in YAMAHA DMS. %0D%0A%0D%0AThanks %0D%0AACI Motors ";
					$this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', 
						$smstext, $dealermobile);	
				}
                if(!empty($motmnumber)){
                    $smstext = "A truck has been released at your destination ({$customercode}). Probable delivery schedule : {$deliverytime}.%0D%0ADriver Information. %0D%0A%0D%0ADriver mobile : {$drivercontactno }. %0D%0ATruck No : {$truckno}. %0D%0A%0D%0AFor more information please log in YAMAHA DMS. %0D%0A%0D%0AThanks %0D%0AACI Motors ";
                    $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', 
                        $smstext, $motmnumber);    
                }
			
                $this->session->set_userdata('show', 1);
                $this->session->set_userdata('success', '1');
                $this->session->set_userdata('message', 'Successfully update.');
            }else{
                $this->session->set_userdata('show', 0);
                $this->session->set_userdata('success', '0');
                $this->session->set_userdata('message', 'Something wrong.');
            }
            redirect(base_url().'transportnotification');
            
        }
                
		
		public function sendsms($ip, $userid, $password, $smstext, $receipient) {
			$smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userid}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
			//echo urlencode($smsUrl); exit();
			$response = file_get_contents(preg_replace("/ /", "%20",$smsUrl));
			return json_decode($response);
		}

        public function index() {
            $data['title']      = 'Transport Notification >> List Transport Notification';
            $servicetype        = 0;
            $data['userid']     = strtoupper(trim($this->session->userdata('userid')));
            $data['grpUser']    = $this->session->userdata('grpUser');
            
            $data['newaction']  = "transportnotification/newnotification";
            $data['exportlink'] = "transportnotification/getnotificationlist/?excel=yes";
            if ($data['grpUser'] == 1) {
                $customerlistid = '%';
            } else {
                $customerlistid = $data['userid'];
            }
            
            $data['header']     = $this->load->view('header', $data, true);
            $data['footer']     = $this->load->view('footer', null, true);
            $data['sidebar']    = $this->load->view('sidebar', null, true);
            
            $data['content'] = $this->load->view('transportnotification/list', $data, true);
            $this->load->view('dashboard', $data);
        }

        public function getnotificationlist(){
            $data['userid']     = strtoupper(trim($this->session->userdata('userid')));
            $data['grpUser']    = $this->session->userdata('grpUser');
            $data['pagelimit']  = $this->input->get_post("pagelimit", TRUE);
            $date['pagenumber'] = $this->input->get_post("pagenumber", TRUE);
            $data['search']     = $this->input->get_post("searchvalue", TRUE);
            $data['displaylist']= $this->input->get_post("displaylist", TRUE);

            if ($data['grpUser'] == 1) {
                $customerlistid = '%';
            } else {
                $customerlistid = $data['userid'];
            }

            $data['excel'] = $this->input->get("excel", TRUE);

            if($data['excel'] == 'yes'){
                $return = $this->TransportNotification->doLoadTransportNotificationList($customerlistid,
                    $data['pagelimit'], "%", "");
                $this->exportexcel($return['expensedata'], "expenselist");
            }else{
                $return = $this->TransportNotification->doLoadTransportNotificationList($customerlistid,
                    $data['pagelimit'], $date['pagenumber'], $data['search']);
                echo json_encode($return);
            }

        }

        public function exportexcel($result,$filename){
            $arrayheading[0] = array_keys($result[0]);
            $result = array_merge($arrayheading, $result);

            header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
            header("Content-Type: application/vnd.ms-excel;");
            header("Pragma: no-cache");
            header("Expires: 0");
            $out = fopen("php://output", 'w');
            foreach ($result as $data)
            {
                fputcsv($out, $data,"\t");
            }
            fclose($out);
            exit();
        }

        public function newnotification(){
            $data                   = array();
            $data['title']          = 'Transport Notification >> New Transport Notification';
            $data['userid']         = strtoupper(trim($this->session->userdata('userid')));
            $data['grpUser']        = $this->session->userdata('grpUser');

            if(!empty($this->input->get("notificationid", TRUE))){
                $data['notificationid'] = base64_decode($this->input->get("notificationid", TRUE));
                $data['notificationedit'] = $this->TransportNotification->doLoadNotification($data['notificationid'])['result'];
                //var_dump( $data['notificationid'] , $data['notificationedit']); exit();
            }
            $data['transportlist'] = $this->TransportNotification->doLoadTransportList()['transportlist'];
            if ($data['grpUser'] == 1) {
                $customerlistid = '%';
            } else {
                $customerlistid = $data['userid'];
                echo "Something wrong."; exit();
            }
            
            $data['customerlist'] = $this->Dealer->CustomerList($customerlistid);
                //echo "<pre />"; print_r($data['expenseedit']); exit();
           
            $data['header']     = $this->load->view('header', $data, true);
            $data['footer']     = $this->load->view('footer', null, true);
            $data['sidebar']    = $this->load->view('sidebar', null, true);
            
            $data['content']    = $this->load->view('transportnotification/newnotification', $data, true);
            $this->load->view('dashboard', $data);
        }
        public function getcustomermobile(){
            $data['customercode']  = $this->input->get_post("customercode", TRUE);
            echo $this->TransportNotification->doLoadCustomerMobile($data['customercode'] );
        }
        function getchallaninformation(){
            $challanno = $this->input->get_post("challanno", TRUE);
            echo $this->TransportNotification->doLoadChallanInformation($challanno);
        }
    }
