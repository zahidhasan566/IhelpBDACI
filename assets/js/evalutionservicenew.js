$(document).ready(function () {                    
    $('#openingdate').datepicker({
      dateFormat: 'yy-mm-dd',
      startDate: '-3d'
    })
    
    $('#evalutiondate').datepicker({
      dateFormat: 'yy-mm-dd',
      startDate: '-3d'
    })
	        
});
        
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}


function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}

function changescore(target,weight,score,actual){
    var valtarget = parseFloat(document.getElementById(target).value);
    var valweight = parseFloat(document.getElementById(weight).value);
    var valactual = parseFloat(actual);
    var cal = parseFloat(( valactual / valtarget ) * valweight); 
    if(isNaN(cal)){
        cal = 0;
    }
    document.getElementById(score).value = cal;
    //console.log(valtarget,valweight,actual,cal);
    var arr = document.getElementsByName('score[]');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
            tot += parseFloat(arr[i].value);
    }
    document.getElementById('scoretotal').innerHTML = tot;
}

function showresult(requirmentid, result, showtype){
    $("#requirmentname"+ requirmentid).css('color','black');
    $("#requirmentname"+ requirmentid).css('font-weight','normal');
    $("#resultinput" + requirmentid).val(result);
    $("#result" + requirmentid).html(result);
    
    updateTotal();
}

function updateTotal() {
    var total = 0;//
    var list = document.getElementsByName("result[]");
    var values = 0.00;
    for(var i = 0; i < list.length; ++i) {
        if(list[i].value){
            values = values + parseFloat(list[i].value);
        }        
    }
    document.getElementById("scoretotal").innerHTML = values;    
}

function validation(){
    var customercode    = $("#customercode").val();
    var districtcode    = $("#districtcode").val();
    var openingdate     = $("#openingdate").val();
    var evalutiondate   = $("#evalutiondate").val();
    var evalutionby     = $("#evalutionby").val();
    
    if(customercode.length < 4){
        alert('Please select Dealer.');
        $("#customercode").css("border","1px solid red");
        $("#customercode").focus();
        return false;
    }else{
        $("#customercode").css("border","1px solid #ccc");
    }
    if(!districtcode){
        alert('Please select district.');
        $("#districtcode").css("border","1px solid red");
        $("#districtcode").focus();
        return false;
    }else{
        $("#districtcode").css("border","1px solid #ccc");
    }
    
    
    var result = document.getElementsByName("result[]");
    var allrequirmentid = document.getElementsByName("requirmentid[]");
    for(var i = 0; i < result.length; ++i) {
        requirmentid = allrequirmentid[i].value;
        if(!result[i].value){
            requirmentname = $("#requirmentname"+ requirmentid).html();
            trimvar = requirmentname.trim();
            alert('Please select result for "' + requirmentname + '"');
            document.getElementById('requirmentname' + requirmentid).scrollIntoView({ offsetTop: 0});
            $("#requirmentname"+ requirmentid).css('color','red');
            $("#requirmentname"+ requirmentid).css('font-weight','bold');
            return false;
        }else{
            $("#requirmentname"+ requirmentid).css('color','black');
            $("#requirmentname"+ requirmentid).css('font-weight','normal');
            /*
            visiblestatus = $("#show" + requirmentid).css('display');
            if(visiblestatus != 'none'){
                var reason          = $("#reason" +  requirmentid).val();
                var whathappen      = $("#whathappen" +  requirmentid).val();
                var whattodo        = $("#whattodo" + requirmentid).val();
                var deadline        = $("#deadline" + requirmentid).val();
                var personincharge  = $("#personincharge" + requirmentid).val();
                if(reason.length < 4){
                    alert('Please enter reason');
                    $("#reason" +  requirmentid).css("border","1px solid red");
                    $("#reason" +  requirmentid).focus();
                    return false;
                }else{
                    $("#reason" +  requirmentid).css("border","1px solid #ccc");
                }
                if(whathappen.length < 4){
                    alert('Please enter what happen');
                    $("#whathappen" +  requirmentid).css("border","1px solid red");
                    $("#whathappen" +  requirmentid).focus();
                    return false;
                }else{
                    $("#whathappen" +  requirmentid).css("border","1px solid #ccc");
                }
                if(whattodo.length < 4){
                    alert('Please enter what to do');
                    $("#whattodo" +  requirmentid).css("border","1px solid red");
                    $("#whattodo" +  requirmentid).focus();
                    return false;
                }else{
                    $("#whattodo" +  requirmentid).css("border","1px solid #ccc");
                }
                if(deadline.length < 4){
                    alert('Please select deadline.');
                    $("#deadline" +  requirmentid).css("border","1px solid red");
                    $("#deadline" +  requirmentid).focus();
                    return false;
                }else{
                    $("#deadline" +  requirmentid).css("border","1px solid #ccc");
                }
                if(personincharge.length < 4){
                    alert('Please enter person in charge.');
                    $("#personincharge" +  requirmentid).css("border","1px solid red");
                    $("#personincharge" +  requirmentid).focus();
                    return false;
                }else{
                    $("#personincharge" +  requirmentid).css("border","1px solid #ccc");
                }                 
            }*/
        }
    }
    return true;
}