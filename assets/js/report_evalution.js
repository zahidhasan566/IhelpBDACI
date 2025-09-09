$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ProductCode = $("#ProductCode").val();
        var ReportType = $("#ReportType").val();
            console.log(string);
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ReportType=" + ReportType;
        loadajaxfunction(string);          
        return false;    
    });        
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })     
     
});  
 
function loadajaxfunction(string,page,searchstring){
    datasting = string; 
    $.ajax({
        url: base_url + "report/loadevalution",
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
           
           EvalutionData = response;
           
           
            if(EvalutionData.length == 0){               
                $('#dataloadtable tbody').append('<tr><td colspan="13" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
            }             
                
            for(var i=0; i<EvalutionData.length; i++){
               var sl = i + 1;
               target = parseInt(EvalutionData[i]['Target']);
               score = parseInt(EvalutionData[i]['Score']);
               score_percentage = parseInt(EvalutionData[i]['Score_Percentage']);
               var string1 = '';
               var string = '<tr><td>' + EvalutionData[i]['SL'] + '</td>\
                    <td>' + EvalutionData[i]['RegionName'] + '</td>\
                    <td>' + EvalutionData[i]['CustomerCode'] + '</td>\
                    <td>' + EvalutionData[i]['CustomerName'] + '</td>\
                    <td>' + EvalutionData[i]['EvalutedBy'] + '</td>\
                    <td>' + EvalutionData[i]['District'] + '</td>\
                    <td>' + EvalutionData[i]['EvalutedDate'] + '</td>\
                    <td>' + EvalutionData[i]['OpenIngDate'] + '</td>\
                    <td>' + EvalutionData[i]['EvalutionType'] + '</td>\\n\
                <td class="text-right">' + target + '</td>\\n\
                <td class="text-right">' + score + '</td>\\n\
                <td class="text-right">' + score_percentage + '</td>\
                    <td><a target="_blank" href="'+base_url+'evalution/evalutiondetails/'+EvalutionData[i]['EvalutionId']+'/'+EvalutionData[i]['EvalutionType']+'">Details</a></td>\
                    <tr>';               
                
                $('#dataloadtable tbody').append(string); 
            }
            if(EvalutionData.length>0){
                $("#a_ExportToExcel").attr("href", base_url + "report/evaluationsalesserviceexcel/?" + datasting + "&excelfilename=" + excelfilename);
            }

         },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        } 
    }); 
}
 