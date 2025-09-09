$(document).ready(function () {
    $('#reporttable').hide();
    $('#excelsearch').hide();
    $('#a_ExportToExcel').hide();
    $("#ReportOrder").submit(function (event) {
        var DateFrom        = $("#DateFrom").val();
        var DateTo          = $("#DateTo").val();
        var CustomerCode    = $("#CustomerCode").val();
        var JobStatus       = $("#JobStatus").val();
        var JobType         = $("#JobType").val();
        var string          = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&JobStatus=" + JobStatus + "&JobType=" + JobType;
        var savestring      = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&JobStatus=" + JobStatus + "&JobType=" + JobType;
        $("#string").val(savestring);
        loadajaxfunction(string, 1, searchstring = '');
        return false;
    });
    $('#DateFrom').datepicker({dateFormat: 'yy-mm-dd'})
    $('#DateTo').datepicker({dateFormat: 'yy-mm-dd'})

    
$("#exportToExcel").on('click',function() {
    var DateFrom        = $("#DateFrom").val();
        var DateTo          = $("#DateTo").val();
        var CustomerCode    = $("#CustomerCode").val();
        var JobStatus       = $("#JobStatus").val();
        var JobType         = $("#JobType").val();
        var string          = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&JobStatus=" + JobStatus + "&JobType=" + JobType;
        var savestring      = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&JobStatus=" + JobStatus + "&JobType=" + JobType;
        $("#string").val(savestring);

        document.location = base_url + "jobcard/loadreport?"+string + "&searchstring="+ searchstring+"&page=&exportable="+'yes';
        // loadajaxfunction(string, '', searchstring = '',true);
});

});
function load(pageno) {
    savestring = $("#string").val();
    searchstring = $("#searchstring").val();
    ajaxstring = savestring + "&page=" + pageno;
    // console.log("Data=====",ajaxstring,"=======", pageno, "======",searchstring);
    loadajaxfunction(ajaxstring, pageno, searchstring);
}


function loadajaxfunction(string, page, searchstring,exportable=false) {
    console.log(string);
    if (!searchstring) {
        $("#searchstring").val('');
    }
    datasting = string;
    $.ajax({
        url: base_url + "jobcard/loadreport",
        type: "post",
        data: string + "&searchstring="+ searchstring+"&page="+page+"&exportable="+exportable,
        dataType: "json",
        beforeSend: function () {
            $('#loading').show();
            $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");
            $('#reporttable').hide();
        },
        success: function (response) {
            if(exportable == true) {
                $('#loading').hide();
                location.reload();
                return true;
            }
            // you will get response from your php page (what you echo or print)  
            //document.getElementById("reporttable").style.display = "block";
            $('#reporttable').show();
            $('#ExportToExcel').show();
            $('#excelsearch').show();
            $('#loading').hide();
            $('#damdtaloadtable tbody').empty();

            $('#dataloadtable tbody').empty();

            // response = JSON.parse(response);
            receivedata = response['result'];
            console.log(receivedata);
             Page = response['paging'];
            if (receivedata.length == 0) {
                $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');
                $('#ExportToExcel').hide();
            }

            var keys = Object.keys(receivedata[0]);
            $("#header").empty();
            // $("#header").append('<th>SL</th>');
            for (i = 0; i < keys.length; i++) {
                $("#header").append('<th>' + keys[i].replace('_', ' ') + '</th>');
            }
            
            $("#header").append('<th>Print</th>');
            
            for (j = 0; j < receivedata.length; j++) {
                var string = '';
                // var SL = '<td>' + parseInt(parseInt(j) + parseInt(1)) + '</td>'
                for (i = 0; i < keys.length; i++) {
                    var format = receivedata[j][keys[i]];
                    string = string + '<td>' + format + '</td>';
                }
                var jobcardnostr = receivedata[j]['Job_Card_No'];
                var token = encodeURIComponent(window.btoa(jobcardnostr));
                 
                string = string + '<td><a target="_blank" href="'+base_url+'jobcard/jobcardprint/?jobcardno='+token+'"><i class="fa fa-print"></i></a></td>';
                $('#dataloadtable tbody').append('<tr>' + string + '</tr>');
                
            }
            $('#a_ExportToExcel').show();



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


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}




 