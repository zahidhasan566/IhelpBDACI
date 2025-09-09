$(document).ready(function () {
    var pagelimit   = 50;
    var pagenumber  = 1;
    var searchvalue = '';
    var datastring = "pagelimit=" + pagelimit + "&pagenumber=" + pagenumber + "&searchvalue=" + searchvalue;
    ajaxfunction(datastring, pagenumber);
    $("#searchstring").val(datastring);
})

function load(page)
{
    datastring = $("#searchstring").val();
    ajaxfunction(datastring, page);
}

function doLoadSearchValue(){
    var pagelimit   = 50;
    var pagenumber  = 1;
    var searchvalue = $("#search").val();
    var datastring = "pagelimit=" + pagelimit + "&pagenumber=" + pagenumber + "&searchvalue=" + searchvalue;
    ajaxfunction(datastring, pagenumber);
    $("#searchstring").val(datastring);
}

function ajaxfunction(datastring, page){
    //console.log(datastring);
     $.ajax({
        type: "POST",
        url: base_url + "bankloan/getbankloanlist/",
        data: datastring,               
        dataType: "json",
        cache: false,
        beforeSend: function( xhr ) {
            $("#dataloadtable tbody").empty();
            $("#dataloadtable tbody").append('<tr><td colspan="16" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">Loading..</td></tr>');
        },
        success: function (response) {
            $('#reporttable').show();
            $('#loading').hide();
            $('#dataloadtable tbody').empty();
            $('#dataloadtable thead').empty();
             
             bankfiledata = response['bankfiledata'];
             pagingdata = response['pagingdata'];
             if(bankfiledata.length == 0){               
                 $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                 $('#pagination').empty();
                 return;
             }
             var keys = Object.keys(bankfiledata[0]); 
             var thead = '<tr>';
             for(i = 1; i < keys.length; i++){
                 tdname = keys[i];
                 thead = thead + "<th>"+tdname.replace("_",' ').replace("_",' ').replace("_",' ').replace("_",' ')+"</th>";
             }
             thead = thead + '<th>Action</th></tr>';

             $('#dataloadtable thead').append(thead);
             for(j=0; j < bankfiledata.length; j++){
                 var string = '';
                 var SL = '<td>'+ parseInt(parseInt(j) + parseInt(1)) +'</td>'
                 for(i = 2; i < keys.length; i++){
                     var format = bankfiledata[j][keys[i]]; 
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
                 } 
                
                var loanid = bankfiledata[j]['Load_ID'];
                var token = encodeURIComponent(window.btoa(loanid));
                string = string + '<td class="text-center" style="font-size: 16px;"><a href="'+base_url+'bankloan/newloanfile/?loanid='+token+'"><i class="fa fa-edit"></i></a> </td>';                        
                $('#dataloadtable tbody').append('<tr>' + SL + string + '</tr>'); 
                
            }
            $('#pagination').empty();
            if(pagingdata.length > 0){                    
                previous = page - 1;
                next = page + 1;
                if(pagingdata.length != 0 && page != 1){
                    $('#pagination').append('<li><a onclick="load(' + previous + ')" href="#" id="PageNo">Previous</a></li>');        
                }
                for(var i=0; i<pagingdata.length; i++){
                    if(page == pagingdata[i]['PageNo']){ classcontent = ' class="active"'; }else{ classcontent = ''; }
                     $('#pagination').append('<li'+classcontent+'><a onclick="load(' + pagingdata[i]['PageNo'] + ')" href="#" id="PageNo">' + pagingdata[i]['PageNo'] + '</a></li>');    
                }  
                if(pagingdata.length != 0 && page != pagingdata.length){
                     $('#pagination').append('<li><a onclick="load(' + next + ')" href="#" id="PageNo">Next</a></li>');        
                }
            }
             
        },
        error: function(ts) { alert(ts.responseText) }
    })
 }
 
 function doChangeStatus(nextstep,jobcardno){
     datastring = "jobstatus=" + nextstep + "&jobcardno=" + jobcardno;
     $.ajax({
        type: "POST",
        url: base_url + "jobcard/jobcardupdate/",
        data: datastring,               
        dataType: "json",
        cache: false,
        beforeSend: function( xhr ) {
        },
        success: function (response) {
            if(response == true){
                datastring = $("#searchstring").val();
                ajaxfunction(datastring, 1);
            }else{
                alert("Something wrong!");
            }
        },
        error: function(ts) { alert(ts.responseText) }
    })
 }


