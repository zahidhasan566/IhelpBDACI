

function loadajaxfunction(page, searchstring) {
    if (!searchstring) {
        $("#search").val('');
    }
    //datasting = string;
    $.ajax({
        url: base_url + "dealerdocument/loadreport",
        type: "post",
        data: "page=" + page + "&searchstring=" + searchstring,
        dataType: "json",
        beforeSend: function () {
            
        },
        success: function (response) {
            $('#dataloadtable tbody').empty();    
            $("#header").empty();
                              
            receivedata = response;
            if(receivedata.length == 0){               
                $('#dataloadtable tbody').append('<tr><td colspan="8" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
               // $('#ExportToExcel').hide();  
            }             
            if(receivedata.length > 0){ 
                var keys = Object.keys(receivedata[0]); 
                                                            
                for(i=1; i < keys.length; i++){
                     $("#header").append('<th>' + keys[i].replace('_',' ') + '</th>');    
                } 
                $("#header").append('<th>Download</th>');
                                                          
                for(j=0; j < receivedata.length; j++){
                     var string = '';
                     var SL = '<td>'+ parseInt(parseInt(j) + parseInt(1)) +'</td>'
                     for(i = 1; i < keys.length; i++){
                        var format = receivedata[j][keys[i]].replace('_',' ');
                        if(i == 3){
                            string = string + '<td><a href="https://bucket-motors-content-management.s3.ap-southeast-1.amazonaws.com/'+format+'" target="_blank">' + format + '</a></td>';                        
                            //string = string + '<td><a href="http://192.168.100.96/Yamaha DMS/'+format+'" target="_blank">' + format + '</a></td>';                        
                        }else{
                            string = string + '<td>' + format + '</td>';                            
                        } 
                                                                                                                                                                                           
                     }     
                     string = string + '<td><a href="https://bucket-motors-content-management.s3.ap-southeast-1.amazonaws.com/'+receivedata[j][keys[3]].replace('_',' ')+'" target="_blank"><button class="btn btn-default">Download</button></a></td>';                    
                     $('#dataloadtable tbody').append('<tr>' + string + '</tr>'); 
                } 
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}


loadajaxfunction(1, "");

function searchdata(){
    var searchstring = $("#search").val();
    loadajaxfunction(1, searchstring);
    return false;
}