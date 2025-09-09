$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var CustomerCode = $("#CustomerCode").val();
        var string = "CustomerCode=" + CustomerCode + "&page=" + page;
        var savestring   = "CustomerCode=" + CustomerCode;
        $("#string").val(savestring);

        loadajaxfunction(string,page,searchstring=''); 
          
        return false;    
    });
        
        
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
 


 
 function loadajaxfunction(string,page){
    datasting = string;
    $.ajax({
            url: base_url + "report/loadcustomerfeedback",
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
               $('#ExportToExcel').hide();
               $('#loading').hide();
               $('#dataloadtable tbody').empty(); 
               
               feedbackdata = response['feedbackdata'];
               Page = response['paging'];
			   //TotalCount = response['TotalCount'];
               
               if(feedbackdata.length == 0){               
                    $('#dataloadtable tbody').append('<tr><td colspan="9" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                    $('#ExportToExcel').hide();  
               } 
               
               
               var totalwalkin = 0;
               for(var i=0; i<feedbackdata.length; i++){
                   var string1 = ''; 
                   var string = '<tr><td>' + feedbackdata[i]['SL'] + '</td>';
                   var string2 = '<td>' + feedbackdata[i]['ChassisNo'] + '</td>\
                        <td>' + feedbackdata[i]['Mobile'] + '</td>\
                        <td>' + feedbackdata[i]['CustomerName'] + '</td>\
                        <td>' + feedbackdata[i]['ServiceType'] + '</td>\
                        <td>' + feedbackdata[i]['Rating'] + '</td>\
                        <td>' + feedbackdata[i]['Comments'] + '</td>\
                        <td>' + feedbackdata[i]['Intime'] + '</td>\
                        <td>' + feedbackdata[i]['OutTime'] + '</td>\
                        </tr>';
                   string = string + string1 + string2;
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
               
               //$("#a_ExportToExcel").attr("href", base_url + "report/visitorexcelexport/?" + datasting);       
            
                     
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            } 
        });
 }
 
 function load(pageno){
    savestring = $("#string").val();
    ajaxstring = savestring + "&page=" + pageno;
    console.log(ajaxstring);
    loadajaxfunction(ajaxstring,pageno);
} 