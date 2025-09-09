$(document).ready(function () {
    $('#reporttable').hide();
    $('#excelsearch').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ProductCode = $("#ProductCode").val();        
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype + "&page=" + page;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype;
        $("#string").val(savestring);
        //alert(base_url);
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
            url: base_url + "report/loadOrder",
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
               console.log(string + "&searchstring=" + searchstring);
               $('#reporttable').show();
               $('#ExportToExcel').show();
               $('#excelsearch').show();
               $('#loading').hide();
               $('#dataloadtable tbody').empty(); 
               
               OrderList = response['OrderList'];
               Page = response['PagingList'];
               
               if(OrderList.length == 0){               
                    $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                    $('#ExportToExcel').hide();  
               } 
               
               for(var i=0; i<OrderList.length; i++){
                   var sl = i + 1;
                   string = '<tr><td>' + OrderList[i]['SL'] + '</td>\
                        <td>' + OrderList[i]['OrderNo'] + '</td>\
                        <td>' + OrderList[i]['OrderDate'] + '</td>';
                   string1 = '<td>' + OrderList[i]['MasterCode'] + ' - ' +  OrderList[i]['CustomerName'] + '</td>';
                   string2 = '<td>' + OrderList[i]['ProductCode'] + ' - ' +  OrderList[i]['ProductName'] + '</td>\
                        <td style="text-align: right;">' + OrderList[i]['Quantity'] + '</td>\
                        <td style="text-align: right;">' + OrderList[i]['UnitPrice'] + '</td>\
						<td style="text-align: right;">' + OrderList[i]['VAT'] + '</td>\
                        <td style="text-align: right;">' + OrderList[i]['Total'] + '</td>\
                        </tr>';
                                                       
                    string = string + string1 + string2;  
                    $('#dataloadtable tbody').append(string);
                    //t.rows.add($(string)).draw();      
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
               
               $("#a_ExportToExcel").attr("href", base_url + "report/orderexcelexport/?" + datasting + "&excelfilename=" + excelfilename);       
                     
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            } 
        }); 
}




 