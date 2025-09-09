/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$().ready(function() {
    // validate signup form on keyup and submit
    $("#dob").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        yearRange: "-100:+0"
    });
    $("#appusername").focus();
    $("#frmagent").validate({
        rules: {
            name: {
                required: true,
                minlength: 5,
                maxlength: 100
            },
            dob: {
                required: true,
                date: true
            },
            phoneno: {
                required: true,
                minlength: 11,
                maxlength:11,
                digits: true
            },
            alternamemobile: {
                minlength: 11,
                maxlength:11,
                digits: true
            },
            emailaddress: {
                maxlength: 100
            },
            address: {
                maxlength: 100
            },
            nid: {
                required: true,
                maxlength: 30,
                minlength: 5,
                digits: true
            },
            drivinglicenceno: {
                required: true,
                maxlength: 30,
                minlength: 5
            },
            ridinglocation: {
                maxlength: 100
            },
            districtcode: {
                required: true
            },
            customercode: {
                required: true
            },
            appusername: {
                required: true,
                maxlength: 30,
                minlength: 5
            },
            apppassword: {
                required: true,
                maxlength: 30,
                minlength: 5
            }
        }
    });
});


function checkvalidation(){
    var appusername = $("#appusername").val();
    var datastring = "appusername=" + appusername; 
    if(appusername.length > 4){
        //ajaxfunction(datastring, "agent/usernamecheck");
    }
}

function ajaxfunction(datastring, url){
    $.ajax({
        url: base_url + url,
        type: "post",
        data: datastring,
        dataType: "json",
        beforeSend: function () {
        },
        success: function (response) {
            if(response['exists'] == true){
                alert("App username already exists!");
                $("#submit").attr("disabled", true);
                return false;
            }else{
                $("#submit").attr("disabled", false)
                return true;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus, errorThrown);
        }
    });
}