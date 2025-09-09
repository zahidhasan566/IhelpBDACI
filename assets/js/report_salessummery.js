$(document).ready(function () {
    $('#reporttable').hide();
    $('#excelsearch').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ReportType = $("#ReportType").val();        
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ReportType=" + ReportType;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ReportType=" + ReportType ;
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
            url: base_url + "report/loadsalessummery",
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
               
               salesdata = response['salesdata'];
               datedetails = response['datedetails'];
               $("#currdate").html(datedetails[0]['CurrStartDate'] + ' - ' + datedetails[0]['CurrEndDate'])
               $("#lmsddate").html(datedetails[0]['LMSDStartDate'] + ' - ' + datedetails[0]['LMSDEndDate']);
               $("#splydate").html(datedetails[0]['SPLYStartDate'] + ' - ' + datedetails[0]['SPLYEndDate']);
                if(salesdata.length == 0){               
                     $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                     $('#ExportToExcel').hide();  
                } 
               
                var keys = Object.keys(salesdata[0]); 
                $("#header").empty();
                $("#header").append('<th>SL</th>');
                for(i=0; i < keys.length; i++){
                     $("#header").append('<th>' + keys[i].replace('_',' ') + '</th>');    
                }                    

                var ReportType = $("#ReportType").val();
                if(ReportType == ''){
                    var intvalue = 1;
                }else{
                    var intvalue = 3;
                }
                
                backgroundcolor = '';
                $('#dataloadtable tbody').empty();
                for(j=0; j < salesdata.length; j++){
                     var string = '';
                     var SL = '<td>'+ parseInt(parseInt(j) + parseInt(1)) +'</td>'
                     for(i = 0; i < keys.length; i++){
                        var format = ''; 
                        var textalign = ''; 
                        if(i > intvalue){
                            format =  parseInt(salesdata[j][keys[i]].replace('_',' '));                            
                            if(isNaN(format)){
                                format = 0;
                            }
                            textalign = 'right';
                        }else{
                            format =  salesdata[j][keys[i]].replace('_',' ');
                            textalign = 'left';
                        }
                        if(keys[i] == 'LMSD_Sales_Growth' || keys[i] == 'SPLY_Sales_Growth' ){
                            if(format >= 0){
                                backgroundcolor = ' background-color: green; color: white ';
                            }else{
                                backgroundcolor = 'background-color: red; color: white';
                            }
                            string = string + '<td style="text-align: '+textalign+'; '+backgroundcolor+'  ">' + format + '%</td>';
                        }else{
                            string = string + '<td style="text-align: '+textalign+' ">' + format + '</td>';
                        }
                        
                     } 
                     $('#dataloadtable tbody').append('<tr>' + SL + string + '</tr>'); 
                }
                //t.rows.add($(string)).draw();      
                $("#a_ExportToExcel").attr("href", base_url + "report/orderexcelexport/?" + datasting + "&excelfilename=" + excelfilename);       

            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            } 
        }); 
}




 