$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ReportType = $("#ReportType").val();
        if(!CustomerCode){
            alert("Please select a customer");
            return false;
        }
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ReportType=" + ReportType;
        //alert(base_url);
        
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ReportType=" + ReportType  + "&page=" + page;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ReportType=" + ReportType;
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
    console.log(string  + "&searchstring=" + searchstring);
    if(!searchstring){ $("#searchstring").val(''); }
    datasting = string; 
    $.ajax({
        url: base_url + "report/loadocstock",
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
                     
            if(response.length == 0){               
                $('#dataloadtable tbody').append('<tr><td colspan="13" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                $('#ExportToExcel').hide();  
            }             
                
            for(var i=0; i<response.length; i++){
                var sl = i + 1;
 				var string = '';
				var string = '<tr><td>'+sl+'</td>\
					<td>' + response[i]['CustomerCode'] + ' - ' + response[i]['CustomerName'] + '</td>\
					<td>' + response[i]['ProductCode'] + ' - ' + response[i]['ProductName'] + '</td>\
					<td>' + response[i]['BuyingPrice'] + '</td>\
					<td>' + response[i]['MRP'] + '</td>\
					<td>' + response[i]['OpeningStock'] + '</td>\
					<td>' + response[i]['Received'] + '</td>\
					<td>' + response[i]['Sales'] + '</td>\
					<td>' + response[i]['Adjustment'] + '</td>\
					<td>' + response[i]['ClosingStock'] + '</td>\
					</tr>';
				 
				$('#dataloadtable tbody').append(string);        
            } 
                    
            $("#a_ExportToExcel").attr("href", base_url + "report/ocstockexcelexport/?" + datasting + "&excelfilename=" + excelfilename); 
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

