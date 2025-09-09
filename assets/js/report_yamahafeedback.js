$(document).ready(function () {
    $('#reporttable').hide();
    $('#excelsearch').hide();
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo+ "&page=" + page ;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo ;
        console.log(string);
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
            url: base_url + "report/yamahaFeedbackReportData",
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
                Page = response['PagingList'];


                if(receivedata.length == 0){               
                    $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                    $('#ExportToExcel').hide();  
                } 
               
                var keys = Object.keys(receivedata[0]);
                $("#header").empty();
                $("#header").append('<th>SL</th>');
                // for(i=0; i < keys.length; i++){
                //      $("#header").append('<th>' + keys[i].replace('_',' ') + '</th>');
                // }
                for(var i=0; i<receivedata.length; i++){
                    var sl = i + 1;
                    var link = 'http://hearme.yamahabd.com/public/feedback/'+ receivedata[i]['Attachment'];
                    string = '<tr><td>'+receivedata[i]['Id']+'</td>\
                        <td>' + receivedata[i]['ChassisNo'] + '</td>\
                        <td>' + receivedata[i]['Name'] + '</td>\
                        <td>' + receivedata[i]['Email'] + '</td>\
                        <td>' + receivedata[i]['Phone'] + '</td>\
						<td>' + receivedata[i]['Address'] + '</td>\
                        <td>' + receivedata[i]['Category'] + '</td>\
                        <td>' + receivedata[i]['Feedback'] + '</td>\
                        <td> <a target="_blank\" href='+link+'>'+ receivedata[i]['Attachment'] +'</a></td>\
                        <td>' + receivedata[i]['CreatedAt']  + '</td>\
                        </tr>';

                    string = string;
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

                $("#a_ExportToExcel").attr("href", base_url + "report/yamahafeedbackexcelexport/?" + datasting + "&excelfilename=" + excelfilename);

            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            } 
        }); 
}




 