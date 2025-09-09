$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom        = $("#DateFrom").val();
        var DateTo          = $("#DateTo").val();
        var CustomerCode    = $("#CustomerCode").val();
        var string      = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode;
        var savestring  = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode;
        $("#string").val(savestring);
        loadajaxfunction(string,page,searchstring=''); 
        return false;    
    });
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })     

});  

function loadajaxfunction(string,page,searchstring){
    console.log(string  + "&searchstring=" + searchstring);
    if(!searchstring){ $("#searchstring").val(''); }
    datasting = string; 
    $.ajax({
        url: base_url + "report/loadcsidetails",
        type: "post",
        data: string  + "&searchstring=" + searchstring,
        dataType: "json",
        beforeSend: function(){
            $('#loading').show();
            $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");    
            $('#reporttable').hide();     
        },               
        success: function (response) {

            $('#reporttable').show();
            $('#loading').hide();
            $('#dataloadtable tbody').empty(); 

            if(response['csidetails'].length == 0){               
                $('#dataloadtable tbody').append('<tr><td colspan="17" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                $('#ExportToExcel').hide();  
            }  

            var keys = Object.keys(response['csidetails'][0]); 
            $("#header").empty();
            $("#header").append('<th>SL</th>');
            for(i=0; i < keys.length; i++){
                $("#header").append('<th id="' + keys[i].replace('Z_','') + '">' + keys[i].replace('Z_','') + '</th>');    
            }            

            $("#dataloadtable tbody").empty();
            for(j=0; j < response['csidetails'].length; j++){
                var string = '';
                var SL = '<td>'+ parseInt(parseInt(j) + parseInt(1)) +'</td>'
                for(i=0; i < keys.length; i++){
                    string = string + '<td>' + response['csidetails'][j][keys[i]].replace('Z_','') + '</td>';
                } 
                $("#dataloadtable tbody").append('<tr>' + SL + string + '</tr>'); 
            }

            for(var i=0; i<response['question'].length; i++){
                if(document.getElementById(response['question'][i]['QueshionId']).innerHTML){
                    document.getElementById(response['question'][i]['QueshionId']).innerHTML = response['question'][i]['Question'];
                }
            } 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        } 
    }); 
}

function load(pageno){
    savestring = $("#string").val();
    searchstring = $("#searchstring").val();
    ajaxstring = savestring + "&page=" + pageno;
    //console.log(ajaxstring);
    loadajaxfunction(ajaxstring,pageno,searchstring);
} 

