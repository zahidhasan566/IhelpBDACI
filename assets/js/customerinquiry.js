/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function doCheckValidation(){
    var nextvisit = $("#nextvisit").val();
    var expecteddelivery = $("#expecteddelivery").val();
    var productcode = $("#productcode").val();
    var inquirylevel = $("#inquirylevel").val();
    if(!nextvisit){
        alert("Please select next visit.");
        $("#nextvisit").focus();
        return false;
    }
    
    if(!expecteddelivery){
        alert("Please select expected delivery.");
        $("#expecteddelivery").focus();
        return false;
    }
    
    if(!productcode){
        alert("Please select product.");
        $("#productcode").focus();
        return false;
    }
    if(!inquirylevel){
        alert("Please select inquiry level.");
        $("#inquirylevel").focus();        
        return false;
    }
    
    var status = $('input[name=offertestride]:checked').val();
    if(status == 1){
        var agentid = $("#agentid").val();
        var ridedate = $("#ridedate").val();
        var ridetime = $("#ridetime").val();
        
        if(!agentid){
            alert("Please select agent.");
            $("#agentid").focus();        
            return false;
        }
        if(!ridedate){
            alert("Please select ride date.");
            $("#ridedate").focus();        
            return false;
        }
        if(!ridetime){
            alert("Please select ride time.");
            $("#ridetime").focus();        
            return false;
        }
    }
    return true;
}

function doChangeTestRide(){    
    var status = $('input[name=offertestride]:checked').val();
    if(status == 1){
        $("#testrideyes").show();
    }else{
        $("#testrideyes").hide();
    }
}
