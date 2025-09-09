$(document).ready(function () {
    var pagelimit   = 50;
    var pagenumber  = 1;
    var searchvalue = '';
    var datastring = "pagelimit=" + pagelimit + "&searchvalue=" + searchvalue;
    //if(page        != "newexpense"){
        ajaxfunction(datastring, pagenumber);
    //}
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
    var datastring = "pagelimit=" + pagelimit + "&searchvalue=" + searchvalue;
    ajaxfunction(datastring, pagenumber);
    $("#searchstring").val(datastring);
}

function ajaxfunction(datastring, page){
    //console.log(datastring);
     $.ajax({
        type: "POST",
        url: base_url + "transportnotification/getnotificationlist/",
        data: datastring + "&pagenumber=" + page,               
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
             
             expensedata = response['expensedata'];
             pagingdata = response['pagingdata'];
             
             if(expensedata.length == 0){               
                 $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                 $('#pagination').empty();
                 return;
             }
             var keys = Object.keys(expensedata[0]); 
             var thead = '<tr>';
             for(i = 1; i < keys.length; i++){
                 tdname = keys[i];
                 thead = thead + "<th>"+tdname.replace("_",' ').replace("_",' ').replace("_",' ').replace("_",' ')+"</th>";
             }
             thead = thead + '<th>Action</th></tr>';

             $('#dataloadtable thead').append(thead);
             for(j=0; j < expensedata.length; j++){
                 var string = '';
                 var SL = '<td>'+ parseInt(parseInt(j) + parseInt(1)) +'</td>'
                 for(i = 2; i < keys.length; i++){
                     var format = expensedata[j][keys[i]]; 
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
                
                var loanid = expensedata[j]['NotificationID'];
                var token = encodeURIComponent(window.btoa(loanid));

                if(grpuser == '1'){
                    string = string + '<td class="text-center" style="font-size: 16px;"><a href="'+base_url+'transportnotification/newnotification/?notificationid='+token+'"><i class="fa fa-edit"></i></a> </td>';
                }else{
                    string = string + '<td></td>';
                }

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

function doLoadCustomerMobile(customercode){
    $.ajax({
        type: "POST",
        url: base_url + "transportnotification/getcustomermobile/",
        data: "customercode=" + customercode,
        dataType: "json",
        cache: false,
        beforeSend: function( xhr ) {
            $("#dealermobile").val('');
        },
        success: function (response) {
            //console.log(response['mobilenumber'][0]['Phone']);
            $("#dealermobile").val(response['mobilenumber'][0]['Phone']);
            $("#motmnumber").val(response['mobilenumber'][0]['MOTMNumber']);
        },
        error: function(ts) { alert(ts.responseText) }
    })
}

function loadChallanInformation(){
    var challanno = $("#challanno").val();
    $.ajax({
        type: "POST",
        url: base_url + "transportnotification/getchallaninformation/",
        data: "challanno=" + challanno,
        dataType: "json",
        cache: false,
        beforeSend: function( xhr ) {
            $("#customercode").val('');
            $("#drivername").val('');
            $("#drivercontactno").val('');
            $("#truckno").val('');
            $("#dealermobile").val('');
        },
        success: function (response) {
            $("#customercode").val(response['data'][0]['CustomerCode']);
            $("#drivername").val(response['data'][0]['DriverName']);
            $("#drivercontactno").val(response['data'][0]['DriverPhoneNo']);
            $("#truckno").val(response['data'][0]['TransportNo']);
            doLoadCustomerMobile(response['data'][0]['CustomerCode']);
        },
        error: function(ts) { alert(ts.responseText) }
    })
}

function validateinput(){
    var validatereturn = false;
    validatereturn = validate("customercode");      if(validatereturn == false){ return false; }
    validatereturn = validate("dealermobile");      if(validatereturn == false){ return false; }
    validatereturn = validate("transportid");       if(validatereturn == false){ return false; }
    validatereturn = validate("deliverytime");      if(validatereturn == false){ return false; }
}

function validate(input){
    var inputvalue = $("#" + input).val();
    if(!inputvalue){
        $("#" + input).focus();
        $("#" + input).css("border", "1px solid red");
        return false;
    }else{
        $("#" + input).css("border", "1px solid #ccc");
    }
}

function validate_fileupload(fileName)
{
    var allowed_extensions = new Array("jpg","png","gif", "doc", "docx", "pdf", "xlsx", "xls");
    var file_extension = fileName.split('.').pop().toLowerCase(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

    for(var i = 0; i <= allowed_extensions.length; i++)
    {
        if(allowed_extensions[i]==file_extension)
        {
            return true; // valid file extension
        }
    }

    return false;
}