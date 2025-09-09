/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function doFollowup(freesscheduleid){
    var remark = $("#remark" + freesscheduleid).val();
    if(!remark){
        alert("Please enter remark");
        $("#remark" + freesscheduleid).focus();
        return false;
    }else{
        //ajax
        var datastring = "remark=" + remark + "&freesscheduleid=" + freesscheduleid + '&userid=' + userid;
        $.ajax({
            type: 'POST',
            url: base_url + "service/insertfreeservicefollowup",
            dataType: 'json',        
            data: datastring,        
            success: function( res ){            
                if(res == "1"){
                    alert("Successfully update remark.");
                    $("#divhide" + freesscheduleid).hide();
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
                console.log(jqXhr, textStatus, errorThrown);
            }
        });
    }
    return false;
}