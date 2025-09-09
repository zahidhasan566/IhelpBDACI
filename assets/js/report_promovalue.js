$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var Period = $("#Period").val();
        var CustomerCode = $("#CustomerCode").val();
        var BrandCode = $("#BrandCode").val();
        var string = "Period=" + Period + "&CustomerCode=" + CustomerCode + "&BrandCode=" + BrandCode + "&reporttype=" + reporttype;
        //alert(base_url);
        
        var string = "Period=" + Period + "&CustomerCode=" + CustomerCode + "&BrandCode=" + BrandCode + "&reporttype=" + reporttype + "&page=" + page;
        var savestring = "Period=" + Period + "&CustomerCode=" + CustomerCode + "&BrandCode=" + BrandCode + "&reporttype=" + reporttype;
        $("#string").val(savestring);
        
        loadajaxfunction(string,page,searchstring=''); 
         
        return false;    
    });
        
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })     
    $('#Period').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yymm'
    }).focus(function() {
        var thisCalendar = jQuery(this);
        jQuery('.ui-datepicker-calendar').detach();
        jQuery('.ui-datepicker-close').click(function() {
            var month = jQuery("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = jQuery("#ui-datepicker-div .ui-datepicker-year :selected").val();
            thisCalendar.datepicker('setDate', new Date(year, month, 1));
        });
    });
    
});  
 
function loadajaxfunction(string,page,searchstring){
    console.log(string  + "&searchstring=" + searchstring);
    if(!searchstring){ $("#searchstring").val(''); }
    datasting = string; 
    $.ajax({
        url: base_url + "report/loadpromovalue",
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
					<td>' + response[i]['PromoName'] + '</td>\
					<td>' + response[i]['CustomerName'] + '</td>\
					<td>' + response[i]['BrandName'] + '</td>\
					<td class="text-right">' + response[i]['Quantity'] + '</td>\
					<td class="text-right">' + response[i]['TotalValue'] + '</td>\
					</tr>';
				 
				$('#dataloadtable tbody').append(string);        
            } 
                    
            $("#a_ExportToExcel").attr("href", base_url + "report/promovalueexcelexport/?" + datasting + "&excelfilename=" + excelfilename); 
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

