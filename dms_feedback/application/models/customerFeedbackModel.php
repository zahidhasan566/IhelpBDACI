<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class customerFeedbackModel extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function getCustomerFeedbackQuestion() {
        $sql = "select * from CustomerFeedbackQuestion order by QuestionOrder Asc";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }

    public function verifyAbilityToFeedback($invoice,$mobile_number,$verification_code) {
        $sql = "select top 1 * from  DealarInvoiceMaster where InvoiceNo = '$invoice' and MobileNo= '$mobile_number' and VerifyCode= '$verification_code' and Verified='0' ";
        $query = $this->db->query($sql);
        if($query) {
            return !empty($query->result_array()) ? true : false;
        }
        return false;
    }

    public function markCustomerAsVerified($invoice,$mobile_number,$verification_code) {
        $sql = "update DealarInvoiceMaster set Verified = '1' where InvoiceNo = '$invoice' and MobileNo= '$mobile_number' and VerifyCode= '$verification_code'";
        $query = $this->db->query($sql);
        if($query) {
            return true;
        }
        return false;
    }

}
