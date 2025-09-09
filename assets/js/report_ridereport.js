$(document).ready(function () {
    $('#reporttable').hide();
    $('#excelsearch').hide(); 
    $('#a_ExportToExcel').hide();
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode;
        console.log(string);
        $("#string").val(savestring);
        loadajaxfunction(string,page,searchstring=''); 
        return false;    
    }); 
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })  
       
});  
function load(pageno){
    savestring = $("#string").val();
    searchstring = $("#searchstring").val();
    ajaxstring = savestring + "&page=" + pageno;
    //console.log(ajaxstring);  alert(ajaxstring);
    loadajaxfunction(ajaxstring,pageno,searchstring);
}                                              
 
 function loadajaxfunction(string,page,searchstring){
    if(!searchstring){ $("#searchstring").val(''); }
    datasting = string;                                 
    $.ajax({
            url: base_url + "agent/loadridereport",
            type: "post",
            data: string + "&searchstring=" + searchstring,
            dataType: "json",
            beforeSend: function(){
                $('#loading').show();
                $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");    
                $('#reporttable').hide();     
            },               
            success: function (response) {
                // you will get response from your php page (what you echo or print)  
                //document.getElementById("reporttable").style.display = "block";
                $('#reporttable').show();
                $('#ExportToExcel').show();
                $('#excelsearch').show();
                $('#loading').hide();
                $('#damdtaloadtable tbody').empty(); 
               
                $('#dataloadtable tbody').empty();
               
                receivedata = response['receivedata'];
                if(receivedata.length == 0){               
                    $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                    $('#ExportToExcel').hide();  
                } 
               
                var keys = Object.keys(receivedata[0]); 
                $("#header").empty();
                for(i=0; i < keys.length; i++){
                     $("#header").append('<th>' + keys[i].replace('_',' ') + '</th>');    
                } 
                
                for(j=0; j < receivedata.length; j++){
                     var string = '';
                     var SL = '<td>'+ parseInt(parseInt(j) + parseInt(1)) +'</td>'
                     for(i = 0; i < keys.length; i++){
                        var format = receivedata[j][keys[i]].replace('_',' ');; 
                         string = string + '<td>' + format + '</td>';                        
                     } 
                     $('#dataloadtable tbody').append('<tr>' + string + '</tr>'); 
                }    
                $('#a_ExportToExcel').show();
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            } 
        }); 
}




 