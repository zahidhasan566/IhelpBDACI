$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        $("#searchstring").val('');
        $("#search").val('');
        
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ProductCode = $("#ProductCode").val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype+ "&page=" + page;
        //alert(base_url);
        //console.log(string);
        
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype;
        $("#string").val(savestring);
        loadajaxfunction(string,page,searchstring='');                
          
        return false;    
    });
        
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })     
    /*
    $(".allproduct").autocomplete({        
        source: function(request, response){
           $.ajax({
               type: "POST",
               url: base_url + "orders/allproductlist",
               data: {search : request.term},               
               dataType: "json",
               cache: false,
               
               success: function (res) {
                    var transformed = $.map(res.data, function (el) {
                        return {
                            label: el.productname,
                            value: el.productcode,
                            unitprice: el.unitprice,
                            vat: el.vat
                        };
                    });
                    response(transformed);                   
               },
               error: function (msg) {
                   response([]);
               }
           })
       },
        focus: function (event, ui) {
            event.preventDefault();
            //$(this).val(ui.item.label);
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
    });*/     
});  
 


function loadajaxfunction(string,page,searchstring){
    
    console.log("&searchstring=" + searchstring);
    datasting = string;   
    $.ajax({
        url: base_url + "report/loadinquiry",
        type: "post",
        data: string  + "&searchstring=" + searchstring,
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
           
           InquiryList = response['InquiryList'];
           Page = response['PagingList'];
           
           if(InquiryList.length == 0){               
                $('#dataloadtable tbody').append('<tr><td colspan="9" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                $('#ExportToExcel').hide();  
           } 
           
           
           
           //var t = $('#dataloadtable').DataTable();                
           for(var i=0; i<InquiryList.length; i++){
               var sl = i + 1;
               var string1 = ''; 
               var string = '<tr><td>' + InquiryList[i]['SL'] + '</td>';
               if(grpUser == 1){
                    string1 = '<td>' + InquiryList[i]['DealerCode'] + ' - ' +  InquiryList[i]['DealerName'] + '</td>';
               }                   
               var string2 = '<td>' + InquiryList[i]['ProductCode'] + ' - ' +  InquiryList[i]['ProductName'] + '</td>\
                    <td>' + InquiryList[i]['InquiryDate'] + '</td>\
                    <td>' + InquiryList[i]['VisitorName'] + '</td>\
                    <td>' + InquiryList[i]['Mobile'] + '</td>\
                    <td>' + InquiryList[i]['Address'] + '</td>\
                    <td>' + InquiryList[i]['Age'] + '</td>\
                    <td>' + InquiryList[i]['Days'] + '</td>\
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
           
           $("#a_ExportToExcel").attr("href", base_url + "report/inquiryexcelexport/?" + datasting + "&excelfilename=" + excelfilename);
                       
        
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
