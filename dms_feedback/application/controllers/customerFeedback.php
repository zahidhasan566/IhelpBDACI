<?php

    if (!defined('BASEPATH')) {
        exit('No direct script access allowed');
    }
    class CustomerFeedback extends CI_Controller {

        public function __construct() {
            parent::__construct();
            $this->load->model('customerFeedbackModel');
        }

        public function index() {
//            TOOD:: remove the block bellow
            // to make feedback link for sms
//            $verificationCode = '32888';
//            $mobileNumber = '01715225949';
//            $invoiceId = 'HC000E/2001645';
//            $encoded = base64_encode($invoiceId.'.'.$mobileNumber.'.'.$verificationCode);
//            $feedbackBaseUrl = base_url();
//            $link = $feedbackBaseUrl.'?i='.$encoded;
//            die($link);
            // End to make feedback link for sms

            if(isset($_GET['i'])) {
                $info = $_GET['i'];
                $info = base64_decode($info);
                $info = explode('.', $info);
                $data['invoice'] = isset($info[0]) ? $info[0] : '';
                $data['mobile_number'] = isset($info[1]) ? $info[1] : '';
                $data['verification_code'] = isset($info[2]) ? $info[2] : '';
                $data['invoice_id'] = isset($info[3]) ? $info[3] : '';
                $isAbleToFeedback = $this->customerFeedbackModel->verifyAbilityToFeedback($data['invoice'],$data['mobile_number'],$data['verification_code']);
                if($isAbleToFeedback) {
                    $data['enable_to_feedback'] = 1;
                    $data['customer_feedback_question'] = $this->customerFeedbackModel->getCustomerFeedbackQuestion();
                } else {
                    $data['message'] = "আপনি ইতিমধ্যে মতামত দিয়ে ফেলেছেন ";
                }

            } elseif(isset($_GET['message']) && isset($_GET['success']) ) {
                $data['message'] = $_GET['message'];
                $data['success'] = $_GET['success'];
            } else {
                $data['message'] = "আপনার অনুমোদন নেই";
            }

            $this->load->view('customer_feedback', $data);

        }

        public function storeCustomerFeedback() {
            if(isset($_POST['customer_feedback_submit'])) {
                $invoiceId = $this->input->post('invoice_id');
                $invoiceNo = $this->input->post('invoice');
                $mobile_number = $this->input->post('mobile_number');
                $verification_code = $this->input->post('verification_code');

                $isAbleToFeedback = $this->customerFeedbackModel->verifyAbilityToFeedback($invoiceNo,$mobile_number,$verification_code);
                if($isAbleToFeedback) {
                    $answers = $this->input->post('result');
                    $result = null;
                    foreach ($answers as $answerKey => $answer) {
                        $dataToinsert = [
                            'InvoiceId' => $invoiceId,
                            'InvoiceNo' => $invoiceNo,
                            'QuestionId' => $answerKey,
                            'Result' => $answer,
                            'FeedbackTime' => date('Y-m-d H:i:s'),
                        ];
                        $CI = & get_instance();;
                        $result = $CI->db->insert('CustomerFeedbackAnswer',$dataToinsert);
                    }
                    if($result) {
                        $this->customerFeedbackModel->markCustomerAsVerified($invoiceNo,$mobile_number,$verification_code);
                        $data['success'] = 1;
                        $data['message'] = 'আপনার মতামতের জন্য ধন্যবাদ';
                    } else {
                        $data['success'] = 0;
                        $data['message'] = 'আমরা দুঃখিত';
                    }
                } else {
                    $data['success'] = 0;
                    $data['message'] = "আপনি ইতিমধ্যে মতামত দিয়ে ফেলেছেন ";
                }

                redirect(base_url().'?success='.$data['success'].'&message='.$data['message'].'');
//                echo "<pre>",print_r($_POST);die();
                // submit the feedback
            }
        }

    }
