$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var Period = $("#Period").val();
        var CustomerCode = $("#CustomerCode").val();
        var string = "Period=" + Period + "&CustomerCode=" + CustomerCode;
        //alert(base_url);
        
        var string = "Period=" + Period + "&CustomerCode=" + CustomerCode;
        var savestring = "Period=" + Period + "&CustomerCode=" + CustomerCode;
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
        url: base_url + "report/loaddealerevaluation",
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
                     
            $('#dataloadtable thead').empty();
            proritydata = response['reportdata'];
            var keys = Object.keys(proritydata[0]); 
            if(proritydata.length == 0){               
                $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
            }
            var thead = '<tr><th colspan="3">Dealer Name</th><th colspan="7">Primary Sales</th>\n\
                <th colspan="4">Secondary Sales</th><th colspan="2">Inventory Status</th><th colspan="2">Sales Evalution</th><th colspan="3">All Evalution</th><th colspan="2">Ranking</th></tr><tr><td>SL</td>';
            for(i = 1; i < keys.length; i++){
                tdname = keys[i];
                thead = thead + "<th>"+tdname.replace("_",' ').replace("_",' ').replace("_",' ').replace("_",' ')+"</th>";
            }
            thead = thead + '</tr>';
            
            $('#dataloadtable thead').append(thead);
            
            for(j=0; j < proritydata.length; j++){
                var string = '';
                var SL = '<td>'+ parseInt(parseInt(j) + parseInt(1)) +'</td>'
                for(i = 1; i < keys.length; i++){
                    var format = proritydata[j][keys[i]]; 
                    if($.isNumeric( format )){
                        if(isNaN(parseInt(format))){
                            string = string + '<td style="text-align: right;">0</td>';                        
                        }else{
                            x = parseFloat(format); //parseFloat();
                            string = string + '<td style="text-align: right;">' + x.toFixed(0) + '</td>';                           
                        }
                    }else{
                        string = string + '<td>' + format + '</td>';                        
                    }
                    //string = string + '<td>' + format + '</td>';                        
                } 
                $('#dataloadtable tbody').append('<tr>' + SL + string + '</tr>'); 
            }   
                    
            $("#a_ExportToExcel").attr("href", base_url + "report/dealerevaluationexcelexport/?" + datasting + "&excelfilename=" + excelfilename); 
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

