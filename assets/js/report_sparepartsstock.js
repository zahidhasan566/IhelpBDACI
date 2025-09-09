page = 1;
$(document).ready(function () {
    //$('#dataloadtable').DataTable();   
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        
        var CustomerCode = $("#CustomerCode").val();
        var ProductCode = $("#ProductCode").val();
        var string = "CustomerCode=" + CustomerCode + "&reporttype=" + reporttype + "&page=" + page;
        var savestring = "CustomerCode=" + CustomerCode  + "&reporttype=" + reporttype;
        //alert(base_url);
        //console.log(string);
        $("#string").val(savestring);
        
        loadajaxfunction(string,page,searchstring='');
        return false;    
    });
        
       
});  
 

function loadajaxfunction(string,page,searchstring){
    //$("#string").val(string + "&searchstring=" + searchstring);
    if(!searchstring){ $("#searchstring").val(''); }
    datasting = string; 
    $.ajax({
        url: base_url + "report/loadStock",
        type: "post",
        data: string + "&searchstring=" + searchstring,
        dataType: "json",
        
        beforeSend: function(){
            $('#loading').show();
            $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");    
            $('#reporttable').hide();     
        },               
        success: function (response) {
           //console.log(response);
           
           $('#reporttable').show();
           $('#ExportToExcel').show();
           $('#excelsearch').show();
           $('#loading').hide();
           $('#dataloadtable tbody').empty(); 
           
           StockList = response['StockList'];
           Page = response['PagingList'];
           
           if(StockList.length == 0){               
                $('#dataloadtable tbody').append('<tr><td colspan="7" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                $('#ExportToExcel').hide();  
           } 
           
           for(var i=0; i<StockList.length; i++){
               var sl = i + 1;
               var string1 = '';
               var string = '<tr><td>' + StockList[i]['SL'] + '</td>';                       
            //    if(grpUser == 1){
            //         string1 = '<td>' + StockList[i]['CustomerCode'] + ' - ' +  StockList[i]['CustomerName'] + '</td>';                        
            //    }
               var string2 = '<td>' + StockList[i]['MasterCode'] + '</td>\
                    <td>' + StockList[i]['ProductCode'] + ' - ' +  StockList[i]['ProductName'] + '</td>\
                    <td>' + StockList[i]['PartNo'] + '</td>\
                    <td>' + StockList[i]['CountQty'] + '</td>\
					<td>' + StockList[i]['RackName'] + '</td>\
					<td>' + StockList[i]['UnitPrice'] + '</td>\
                    </tr>';
            //    string = string + string1 + string2;
               string = string + string2;
               $('#dataloadtable tbody').append(string);
               //t.rows.add($(string)).draw();
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
           
           $("#a_ExportToExcel").attr("href", base_url + "report/sparePartStockexcelexport/?" + datasting + "&excelfilename=" + excelfilename);
           
        
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