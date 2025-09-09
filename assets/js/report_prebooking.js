$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        $("#searchstring").val('');
        $("#search").val('');
        
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ProductCode = $("#ProductCode").val();
        var page = 1;
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&page=" + page;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode;
        $("#string").val(savestring);
        loadajaxfunction(string,page);                
          
        return false;    
    });
        
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })     
     
});  
 


function loadajaxfunction(string,page){
    console.log(string);
    datasting = string;   
    $.ajax({
        url: base_url + "prebooking/loadreport",
        type: "post",
        data: string,
        dataType: "json",
        beforeSend: function(){
            $('#loading').show();
            $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");    
            $('#reporttable').hide();     
        },               
        success: function (response) {
           // you will get response from your php page (what you echo or print)  
           //document.getElementById("reporttable").style.display = "block";
           console.log(response);
           $('#reporttable').show();
           $('#ExportToExcel').show();
           $('#loading').hide();
           $('#dataloadtable tbody').empty(); 
           
           //InquiryList = response['InquiryList'];
           //Page = response['PagingList'];
           
           if(response.length == 0){               
                $('#dataloadtable tbody').append('<tr><td colspan="12" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                $('#ExportToExcel').hide();  
           } 
           
           //var t = $('#dataloadtable').DataTable();                
           for(var i=0; i<response.length; i++){
               var sl = i + 1;
               var string1 = ''; 
               var string = '<tr><td>' + sl + '</td>';
                                  
               var string2 = '<td>' + response[i]['Dealer'] + '</td>\
                    <td>' + response[i]['CustomerName'] + '</td>\
                    <td>' + response[i]['CustomerAddress'] + '</td>\
                    <td>' + response[i]['PhoneNumber'] + '</td>\
                    <td>' + response[i]['Age'] + '</td>\
                    <td>' + response[i]['Occupation'] + '</td>\
                    <td>' + response[i]['Gender'] + '</td>\\n\
                    <td>' + response[i]['Currently_Using_Bike'] + '</td>\\n\
                    <td>' + response[i]['Product'] + '</td>\\n\
                    <td>' + response[i]['Pre_Booked_Amount'] + '</td>\\n\
                    <td>' + response[i]['Money_Receipts_Number'] + '</td>\\n\
                    <td>' + response[i]['Entry_Date'] + '</td>\
               </tr>';
               string = string + string2;
               $('#dataloadtable tbody').append(string);        
               //t.rows.add($(string)).draw();
           }
           /*
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
           */
           $("#a_ExportToExcel").attr("href", base_url + "inquiry/inquiryconversionsummaryexport/?" + datasting + "&excelfilename=" + excelfilename);
                       
        
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
    
    console.log(ajaxstring);
    loadajaxfunction(ajaxstring,pageno,searchstring);
} 
