<?php

/**
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class Push {
    // push message payload
    private $data;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied

    function __construct() {
        
    }

    public function setPayload($data) {
        $this->data = $data;
    }



    public function getPush() {
        $res = array();
        $res['data']['payload'] = $this->data;
        return $res;
    }

}
