<?php
function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

function get_ip_address(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
                 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe

                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE |
                        FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}

/**
 *   Validate an email address.
 *   Provide email address (raw input)
 *   Returns TRUE if the email address has the email
 *   address format and the domain exists.
 */
function validEmail($email) {
    $isValid = TRUE;
    $atIndex = strrpos($email, "@");
    if (is_bool($atIndex) && !$atIndex) {
        $isValid = FALSE;
    } else{
        $domain = substr($email, $atIndex+1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64) {
            // local part length exceeded
            $isValid = FALSE;
        } else if ($domainLen < 1 || $domainLen > 255) {
            // domain part length exceeded
            $isValid = FALSE;
        } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
            // local part starts or ends with '.'
            $isValid = FALSE;
        } else if (preg_match('/\\.\\./', $local)) {
            // local part has two consecutive dots
            $isValid = FALSE;
        } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
            // character not valid in domain part
            $isValid = FALSE;
        } else if (preg_match('/\\.\\./', $domain)) {
            // domain part has two consecutive dots
            $isValid = FALSE;
        } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
            str_replace("\\\\","",$local))) {
            // character not valid in local part unless
            // local part is quoted
            if (!preg_match('/^"(\\\\"|[^"])+"$/',
                str_replace("\\\\","",$local))) {
                $isValid = FALSE;
            }
        }
    }
    return $isValid;
}

function quoteCheck($value) {
    // Quote if not a number or a numeric string
    if (!is_numeric($value)) {
        $value = mysql_real_escape_string($value);
    }
    return $value;
}

function fileList($dir, $typefile='gif|png') {
    $data = array();
    $d =  get_filenames($dir);

    foreach ($d as $file) {
        $filter = '/\.('.$typefile.')$/';
        if(!preg_match($filter, $file)) continue;
        $info = get_file_info($dir.$file);
        $size = $info['size'];
        $lastmod = date("d/m/Y",($info['date']*1000));
        $data[] = array('name'=>$file, 'size'=>$size,
            'lastmod'=>$lastmod, 'url'=>$dir.$file);
    }
    $o = array('filelist'=>$data);
    return json_encode($o);
}

function passdecode($xpassword) {
    $decode = "";

    For ($i = strLen($xpassword)-1; $i >= 0; $i--) {
        $decode .= Chr(ord(substr($xpassword, $i, 1)) - 104);
    }
    return $decode;
}

function passencode($xpassword) {
    $encode = "";

    For ($i = strLen($xpassword)-1; $i>= 0; $i--) {
        $encode .= Chr(ord(substr($xpassword, $i, 1)) + 104);
    }
    return $encode;
}

function doPrntSelectOption($array, $valuename, $showname, $selectedvalue = ''){
    $string = '';
    if(!empty($array)){
        foreach($array AS $row){
            if($selectedvalue == $row[$valuename]){
                $selected = ' selected="selected" ';
            }else{
                $selected = '';
            }
            $string .= "<option ".$selected." value='".$row[$valuename]."'>".$row[$showname]."</option>";
        }
    }
    return $string;
}

function mssql_escape($str)
{
    if(get_magic_quotes_gpc())
    {
        $str= stripslashes(nl2br($str));
    }
    return str_replace("'", "''", $str);
}

function utf8ize($d) {
    //echo "adf";
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

function sendSms($receipient, $smstext='Sample Text') {
    $CI = & get_instance();
    $ip = $CI->config->item('sms_user_ip');
    $userId = $CI->config->item('sms_user_id');
    $password = $CI->config->item('sms_user_password');
    $smstext = urlencode($smstext);
    $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userId}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
    $smsUrl = preg_replace("/ /", "%20", $smsUrl);
    $response = file_get_contents($smsUrl);
    return json_decode($response);
}

function exportexcel($result, $filename)
{
    for ($i = 0; $i < count($result); $i++) {
        if (!isset($result[$i]['PageNo'])) {
            break;
        }
        unset($result[$i]['PageNo']);
    }
    $arrayheading[0] = !empty($result) ? array_keys($result[0]) : [];
    $result = array_merge($arrayheading, $result);

    header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
    header("Content-Type: application/vnd.ms-excel;");
    header("Pragma: no-cache");
    header("Expires: 0");
    $out = fopen("php://output", 'w');
    foreach ($result as $data) {
        fputcsv($out, $data, "\t");
    }
    fclose($out);
    exit();
}

if (!function_exists('setFlashMsg')) {
    /**
     * set Flash Message
     * @param string $message
     * @param string $messageType
     */
    function setFlashMsg($message = "Successful", $messageType = "success")
    {
        session_start();
        if ($messageType == 'success') {
            $_SESSION['success_msg'] = $message;
        } elseif ($messageType == 'error') {
            $_SESSION['error_msg'] = $message;
        } elseif ($messageType == 'info') {
            $_SESSION['info_msg'] = $message;
        } else {
            $_SESSION['default_msg'] = $message;
        }
    }
}


if (!function_exists('getFlashMsg')) {
    /**
     * get Flash Message
     * @return string
     */
    function getFlashMsg()
    {
        $message = "";
        if(!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['success_msg']) && $_SESSION['success_msg'] != '') {
            $message = getMsgWithHtml($_SESSION['success_msg'], 'success');
            unset($_SESSION['success_msg']);
        } elseif (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] != '') {
            $message = getMsgWithHtml($_SESSION['error_msg'], 'error');
            unset($_SESSION['error_msg']);
        } elseif (isset($_SESSION['info_msg']) && $_SESSION['info_msg'] != '') {
            $message = getMsgWithHtml($_SESSION['info_msg'], 'info');
            unset($_SESSION['info_msg']);
        } elseif (isset($_SESSION['default_msg']) && $_SESSION['default_msg'] != '') {
            $message = getMsgWithHtml($_SESSION['default_msg'], 'default');
            unset($_SESSION['default_msg']);
        }
        return $message;
    }
}


if (!function_exists('getMsgWithHtml')) {
    function getMsgWithHtml($message, $msgType = 'success')
    {
        $messageHtml = "";
        if ($message == '') return $messageHtml;

        if ($msgType == 'success') {
            $messageHtml = "<div class='alert alert-success noborder text-center weight-400 nomargin noradius flashMessageHtml'>" . $message . "</div>";
        } elseif ($msgType == 'error') {
            $messageHtml = "<div class='alert alert-danger noborder text-center weight-400 nomargin noradius flashMessageHtml'>" . $message . "</div>";
        } elseif ($msgType == 'info') {
            $messageHtml = "<div class='alert alert-info noborder text-center weight-400 nomargin noradius flashMessageHtml'>" . $message . "</div>";
        } else {
            $messageHtml = "<div class='alert alert-default noborder text-center weight-400 nomargin noradius flashMessageHtml'>" . $message . "</div>";
        }
        return $messageHtml;
    }
}

if (!function_exists('getAlertMsgWithHtml')) {
    function getAlertMsgWithHtml($message='No Data Found', $msgType = 'error')
    {
        $messageHtml = "";
        if ($message == '') return $messageHtml;

        if ($msgType == 'success') {
            $messageHtml = "<div class='alert alert-success noborder text-center weight-400 nomargin noradius'>" . $message . "</div>";
        } elseif ($msgType == 'error') {
            $messageHtml = "<div class='alert alert-danger noborder text-center weight-400 nomargin noradius'>" . $message . "</div>";
        } elseif ($msgType == 'info') {
            $messageHtml = "<div class='alert alert-info noborder text-center weight-400 nomargin noradius'>" . $message . "</div>";
        } else {
            $messageHtml = "<div class='alert alert-default noborder text-center weight-400 nomargin noradius'>" . $message . "</div>";
        }
        return $messageHtml;
    }
}

if (!function_exists('uploadImage')) {
    function uploadImage($imageFile,$dir='upload',$uploadName='',$config = [])
    {
        if(!file_exists($dir)) {
            mkdir($dir);
        }
        $file_tmp = $_FILES[$imageFile]['tmp_name'];

        $file_ext= pathinfo($_FILES[$imageFile]["name"], PATHINFO_EXTENSION);
        
        if($uploadName == '') {
            $file_name = time().'_'.rand(2,5).'.'.$file_ext;
        } else {
            $file_name = $uploadName.'.'.$file_ext;
        }

        if(move_uploaded_file($file_tmp,$dir.'/'.$file_name)) {
            return $file_name;
        }
        return null;

    }
}
// Upload Image
function uploadImage2($fileName,$uplaodOption = []) {

    try{
        $ext = pathinfo($_FILES[$fileName]["name"], PATHINFO_EXTENSION);
        $prefix = "";
        if(!empty($uplaodOption['prefix'])) {
            $prefix = $uplaodOption['prefix']."_";
        }
        $newName = $prefix.time().'_'.rand(1,10).'.'.$ext;
        $tempname = $_FILES[$fileName]["tmp_name"];




        if(!empty($uplaodOption['upload_path'])) {
            $folder = $uplaodOption['upload_path']."/";
        } else {
            $folder = "uploads/";
        }

//        die($folder);
        if(!file_exists($folder)) {
            mkdir($folder);
        }
        $file = $folder."/".$newName;
        $status = move_uploaded_file($tempname, $file);
        $response['success'] = false;
        if($status) {
            $response['success'] = true;
            $response['name'] = $newName;
            $response['error'] = '';
            if(isset($uplaodOption['move_different_path'])) {
                rename($folder, $uplaodOption['move_different_path'].$newName);
            }
        }


    }catch(\Exception $ex) {
        $response['error'] = $ex->getMessage();

    }
    return $response;

}

function downloadImage($imageUrl)
{       
    $CI = & get_instance();
    $CI->load->helper('download');
    $data = @file_get_contents ($imageUrl);
    // explode('/',$imageUrl);
    force_download('test.jpeg', $data);
}

function isUrlExists($url) {
    $headers=get_headers($url);
    return stripos($headers[0],"200 OK")?true:false;
}
