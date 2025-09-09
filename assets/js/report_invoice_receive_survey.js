$(document).ready(function () {
    $('#reporttable').hide();
    $('#excelsearch').hide();
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode    = $("#CustomerCode").val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo+ "&CustomerCode=" + CustomerCode+ "&page=" + page ;
        var savestring  = "DateFrom=" + DateFrom + "&DateTo=" + DateTo+ "&CustomerCode=" + CustomerCode+ "&page=" + page ;
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
            url: base_url + "report/loadInvoiceSurveyReport",
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
               
                $('#dataloadtable tbody').empty();

                receivedata = response['receivedata'];
                question = response['question'];



                if(receivedata.length == 0){               
                    $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                    $('#ExportToExcel').hide();  
                } 
               
                var keys = Object.keys(receivedata[0]);
                $("#header").empty();
                $("#header").append('<th>SL</th>');

                let sl =0;
                string1 = '';
                string2 = '';
                string3 = '';
                string4 = '';
                string5 = '';
                string6 = '';
                for(var i=0; i<receivedata.length; i++){
                    sl = i + 1;
                    string = '<tr><td>'+sl+'</td>\
                        <td>' + receivedata[i]['InvoiceNo'] + '</td>\
                        <td>' + receivedata[i]['CustomerCode'] + '</td>\
                        <td>' + receivedata[i]['CustomerName'] + '</td>';
                    string6=  '<td>' + receivedata[i]['Comment'] + '</td></tr>';
                    for(j=0; j < question.length; j++){
                        if(question[j]['SurveyQuestionID'] ==1){
                            string1 = '<td>' + receivedata[i]['1'] + '</td>'
                        }
                        if(question[j]['SurveyQuestionID'] ==2){
                            string2 = '<td>' + receivedata[i]['2'] + '</td>'
                        }
                        if(question[j]['SurveyQuestionID'] ==3){
                            string3 = '<td>' + receivedata[i]['3'] + '</td>'
                        }
                        if(question[j]['SurveyQuestionID'] ==3){
                            string4 = '<td>' + receivedata[i]['3'] + '</td>'
                        }
                        if(question[j]['SurveyQuestionID'] ==5){
                            string5 = '<td>' + receivedata[i]['5'] + '</td>'
                        }
                    }
                    string = string + string1+ string2+ string3+ string4+ string5 + string6;
                    $('#dataloadtable tbody').append(string);
                }

                $("#header").empty();
                $("#header").append('<th>SL</th>');
                $("#header").append('<th>Invoice No</th>');
                $("#header").append('<th>Customer Code</th>');
                $("#header").append('<th>Customer Name</th>');
                for(i=0; i < question.length; i++){
                    $("#header").append('<th> ' + question[i]['SurveyQuestion'] +'</th>');
                }
                $("#header").append('<th>Comment</th>');

                $("#a_ExportToExcel").attr("href", base_url + "report/loadInvoiceSurveyReportExcelFile/?" + datasting + "&excelfilename=" + excelfilename);

            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            } 
        }); 
}




 