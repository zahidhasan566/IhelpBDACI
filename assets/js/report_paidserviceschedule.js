$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ProductCode = $("#ProductCode").val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype + "&page=" + page;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype;
        $("#string").val(savestring);   
        console.log(string);      
        loadajaxfunction(string,page,searchstring='');
        return false;    
    });
        
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })     
      
});  
 


 function loadajaxfunction(string,page,searchstring){
    if(!searchstring){ $("#searchstring").val(''); }
    datasting = string; 
        $.ajax({
            url: base_url + "report/loadpaidserviceschedule",
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
               $('#loading').hide();
               $('#dataloadtable tbody').empty(); 
               
               FreeServiceList = response['FreeServiceList'];
               Page = response['PagingList'];
               
               if(FreeServiceList.length == 0){               
                    $('#dataloadtable tbody').append('<tr><td colspan="12" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                    $('#ExportToExcel').hide();  
               } 
                              
               for(var i=0; i<FreeServiceList.length; i++){
                   var sl = i + 1;
                   var string1 = '';
                   var string = '<tr><td>' + FreeServiceList[i]['SL'] + '</td>';
                   string1 = '<td>' + FreeServiceList[i]['DealerCode'] + ' - ' +  FreeServiceList[i]['DealerName'] + '</td>';                   
                   var string2 = '<td>' + FreeServiceList[i]['ProductCode'] + ' - ' +  FreeServiceList[i]['ProductName'] + '</td>\
                        <td>' + FreeServiceList[i]['Color'] + '</td>\
                        <td>' + FreeServiceList[i]['ChassisNo'] + '</td>\
                        <td>' + FreeServiceList[i]['EngineNo'] + '</td>\
                        <td>' + FreeServiceList[i]['CustomerType'] + '</td>\
                        <td>' + FreeServiceList[i]['CustomerName'] + '</td>\\n\
                        <td>' + FreeServiceList[i]['CustomerAddress'] + '</td>\
                        <td>' + FreeServiceList[i]['MobileNo'] + '</td>\
                        <td>' + FreeServiceList[i]['ScheduleTitle'] + '</td>\
                        <td>' + FreeServiceList[i]['InvoiceDate'] + '</td>\
                        <td>' + FreeServiceList[i]['ScheduleDate'] + '</td>\\n\
                        <td>' + FreeServiceList[i]['InvoiceAge'] + '</td>\
                        <td>' + FreeServiceList[i]['Days'] + '</td>\
                        <td>' + FreeServiceList[i]['Mileage'] + '</td>';
                        var string2 = string2 + '</tr>';
                    string = string + string1 + string2; //.concat(string1, string2);                         
                    $('#dataloadtable tbody').append(string);        
                    
               }
               
               $('#pagination').empty(); 
               
               previous = page - 1;
               next = page + 1;
               
               if(Page.length != 0 && page != 1){
                    $('#pagination').append('<li><a onclick="load(' + previous + ')" href="#" id="PageNo">Previous</a></li>');        
               }
               for(var i=0; i<Page.length; i++){
                   if(page == Page[i]['PageNo']){ classcontent = ' class="active"'; }else{ classcontent = ''; }
                    $('#pagination').append('<li'+classcontent+'><a onclick="load(' + Page[i]['PageNo'] + ')" href="#" id="PageNo">' + Page[i]['PageNo'] + '</a></li>');    
               }  
               if(Page.length != 0 && page != Page.length){
                    $('#pagination').append('<li><a onclick="load(' + next + ')" href="#" id="PageNo">Next</a></li>');        
               }
               $("#a_ExportToExcel").attr("href", base_url + "report/paidservicescheduleexcelexport/?" + datasting + "&excelfilename=" + excelfilename); 
            
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

 