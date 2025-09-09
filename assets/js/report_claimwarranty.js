$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ApprovedType = $('#ApprovedType').val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&reporttype=" + reporttype + "&page=" + page + "&ApprovedType=" + ApprovedType;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&reporttype=" + reporttype;
        console.log(ApprovedType);
        $("#string").val(savestring);
        
        loadajaxfunction(string,page,searchstring=''); 
        
        return false;    
    });
        
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })     
    /*
    $(".allproduct").autocomplete({        
        source: function(request, response){
           $.ajax({
               type: "POST",
               url: base_url + "orders/allproductlist",
               data: {search : request.term},               
               dataType: "json",
               cache: false,
               
               success: function (res) {
                    var transformed = $.map(res.data, function (el) {
                        return {
                            label: el.productname,
                            value: el.productcode,
                            unitprice: el.unitprice,
                            vat: el.vat
                        };
                    });
                    response(transformed);                   
               },
               error: function (msg) {
                   response([]);
               }
           })
       },
        focus: function (event, ui) {
            event.preventDefault();
            //$(this).val(ui.item.label);
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
    });*/     
});  
 


function loadajaxfunction(string,page,searchstring){
    if(!searchstring){ $("#searchstring").val(''); }
    datasting = string;        
    // console.log(string  + "&searchstring=" + searchstring);
    $.ajax({
            url: base_url + "report/loadclaimwarranty",
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
            //    console.log(response);
               $('#reporttable').show();
               $('#ExportToExcel').show();
               $('#loading').hide();
               $('#dataloadtable tbody').empty(); 
               
               WarrantyReport = response['WarrantyReport'];
               Page = response['PagingList'];
               partsUserExists = response['partsUserExists'];
               warrantyJudgeUserExists = response['warrantyJudgeUserExists'];
               factoryQAUserExists = response['factoryQAUserExists'];
               console.log(WarrantyReport);
               if(WarrantyReport.length == 0){               
                    $('#dataloadtable tbody').append('<tr><td colspan="7" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                    $('#ExportToExcel').hide();  
               } 
               //var t = $('#dataloadtable').DataTable();
               
               
               $('#dataloadtable tbody').empty(); 
               string1 = '';                
               string2 = '';
               string3 = '';                
               string10 = '';                
               string11 = '';                
               for(var i=0; i<WarrantyReport.length; i++){
                   var sl = i + 1;
                   string = '<tr><td>' + WarrantyReport[i]['SL'] + '</td>';                        
                        string1 = '<td>' + WarrantyReport[i]['RegionName']+ '</td><td>' + WarrantyReport[i]['ServiceType']+ '</td>';                        
                        string2 = '<td>' + WarrantyReport[i]['DCWarrantyId'] + '</td>\
                        <td>' + WarrantyReport[i]['JobCardNo'] + '</td>\
                        <td>' + WarrantyReport[i]['WCDate'] + '</td>\
                        <td>' + WarrantyReport[i]['OccuranceDate'] + '</td>\
                        <td>' + WarrantyReport[i]['MasterCode'] + '</td>\
                        <td>' + WarrantyReport[i]['DealarName'] + '</td>\
                        <td>' + WarrantyReport[i]['CustomerName'] + '</td>\
                        <td>' + WarrantyReport[i]['BikeModel'] + '</td>\
                        <td>' + WarrantyReport[i]['ChassisNo'] + '</td>\
                        <td>' + WarrantyReport[i]['InvoiceDate'] + '</td>\\n\
                        \n\<td>' + WarrantyReport[i]['Mileage'] + '</td>\\n\
                        \n\<td>' + WarrantyReport[i]['ProblemDetails'] + '</td>\\n\
                        \n\<td>' + WarrantyReport[i]['PartNo'] + '</td>\\n\
                        \n\<td>' + WarrantyReport[i]['ProductName'] + '</td>\\n\
                        \n\\n\<td>' + WarrantyReport[i]['TotalCost'] + '</td>\\n\
                        \n\\n\\n\<td>' + WarrantyReport[i]['Approved_Status'] + '</td>\\n\
                        <td><a target="_blank" href="'+base_url + 'service/printwarranty?insertid=' + WarrantyReport[i]['DCWarrantyId'] +'  "><font class="btn btn-success">Print</font></a></td>';
                        
                        if(partsUserExists && (WarrantyReport[i]['PartsReceivingStatus']=='Not Received') && WarrantyReport[i]['Approved_Status']=='Approved'){
                            string3 = '<td id="partsReceivedText'+WarrantyReport[i]['DCWarrantyId']+WarrantyReport[i]['ProductCode']+'"><button class="btn btn-primary" id="partsReceived'+WarrantyReport[i]['DCWarrantyId']+WarrantyReport[i]['ProductCode']+'" onclick=changePartsReceivingStatus('+WarrantyReport[i]['DCWarrantyId']+',"'+ WarrantyReport[i]['ProductCode'] +'")>Receive</button></td>';
                        }else if(WarrantyReport[i]['PartsReceivingStatus']=='Received'){
                                string3 = '<td>' + WarrantyReport[i]['PartsReceivingStatus'] + '</td>';
                        }else{
                            string3 = '<td></td>';
                        }
                        string7 = '<td>' + WarrantyReport[i]['PartsReceivingTime'] + '</td>';

                        if(warrantyJudgeUserExists && WarrantyReport[i]['WarrantyJudgementByService']=='Not Approved' && (WarrantyReport[i]['PartsReceivingStatus']=='Received')){
                            string4 = '<td id="warrantyJudgeText'+WarrantyReport[i]['DCWarrantyId']+WarrantyReport[i]['ProductCode']+'"><button class="btn btn-primary" id="warrantyJudge'+WarrantyReport[i]['DCWarrantyId']+WarrantyReport[i]['ProductCode']+'" onclick=changeWarrantyJudgementStatus('+WarrantyReport[i]['DCWarrantyId']+',"'+ WarrantyReport[i]['ProductCode'] +'")>Approve</button>';
                            string10 = '<button class="btn btn-danger" id="warrantyJudgeReject'+WarrantyReport[i]['DCWarrantyId']+WarrantyReport[i]['ProductCode']+'" onclick=changeWarrantyJudgementRejectStatus('+WarrantyReport[i]['DCWarrantyId']+',"'+ WarrantyReport[i]['ProductCode'] +'")>Reject</button></td>';
                        }else if(WarrantyReport[i]['WarrantyJudgementByService']=='Approved'){  
                            string4 = '<td>' + WarrantyReport[i]['WarrantyJudgementByService'] + '</td>';
                        }else if(WarrantyReport[i]['WarrantyJudgementByService']=='Rejected'){  
                            string4 = '<td style="color:red;">' + WarrantyReport[i]['WarrantyJudgementByService'] + '</td>';
                        }else{
                            string4 = '<td>Not Approved</td>';
                        }
                        string8 = '<td>' + WarrantyReport[i]['WarrantyJudgementTime'] + '</td>';

                        if(factoryQAUserExists && WarrantyReport[i]['FactoryQA']=='Not Approved' && (WarrantyReport[i]['PartsReceivingStatus']=='Received') && (WarrantyReport[i]['WarrantyJudgementByService']=='Approved' || WarrantyReport[i]['WarrantyJudgementByService']=='Rejected')){
                            string5 = '<td id="factoryQAText'+WarrantyReport[i]['DCWarrantyId']+WarrantyReport[i]['ProductCode']+'"><button class="btn btn-primary" id="factoryQA'+WarrantyReport[i]['DCWarrantyId']+WarrantyReport[i]['ProductCode']+'" onclick=changeFactoryQAStatus('+WarrantyReport[i]['DCWarrantyId']+',"'+ WarrantyReport[i]['ProductCode'] +'")>Approve</button>';
                            string11 = '<button class="btn btn-danger" id="factoryQAReject'+WarrantyReport[i]['DCWarrantyId']+WarrantyReport[i]['ProductCode']+'" onclick=changeFactoryQARejectStatus('+WarrantyReport[i]['DCWarrantyId']+',"'+ WarrantyReport[i]['ProductCode'] +'")>Reject</button></td>';
                        }else if(WarrantyReport[i]['FactoryQA']=='Approved'){
                            string5 = '<td>' + WarrantyReport[i]['FactoryQA'] + '</td>';
                        }else if(WarrantyReport[i]['FactoryQA']=='Rejected'){
                            string5 = '<td style="color:red;">' + WarrantyReport[i]['FactoryQA'] + '</td>';
                        }else{
                            string5 = '<td>Not Approved</td>';
                        }
                        string9 = '<td>' + WarrantyReport[i]['FactoryQATime'] + '</td>';

                        string6 = '</tr>';
                        
         
                   string = string + string1 + string2 + string3 + string7 + string4 + string10 + string8 + string5 + string11 + string9 + string6;
                   $('#dataloadtable tbody').append(string); 
                   //t.rows.add($(string)).draw(); 
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
                   
                   $("#a_ExportToExcel").attr("href", base_url + "report/warrantyreportexport/?" + datasting  + "&excelfilename=" + excelfilename);    
                         
            
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
    // console.log(ajaxstring);
    loadajaxfunction(ajaxstring,pageno,searchstring);
} 

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

function changePartsReceivingStatus(DCWarrantyId,ProductCode){
    $.ajax({
        url: base_url + "report/changePartsReceivingStatus",
        type: "post",
        data: {DCWarrantyId : DCWarrantyId, ProductCode: ProductCode},
        dataType: "json",               
        success: function (response) {
            if(response==true){
                $('#partsReceived'+DCWarrantyId+ProductCode).hide();
                $('#partsReceivedText'+DCWarrantyId+ProductCode).text('Received');
                toastr["success"]("Parts Received Successfully");
            }
        }
    });
}

function changeWarrantyJudgementStatus(DCWarrantyId,ProductCode){
    $.ajax({
        url: base_url + "report/changeWarrantyJudgementStatus",
        type: "post",
        data: {DCWarrantyId : DCWarrantyId, ProductCode: ProductCode},
        dataType: "json",               
        success: function (response) {
            if(response==true){
                $('#warrantyJudge'+DCWarrantyId+ProductCode).hide();
                $('#warrantyJudgeReject'+DCWarrantyId+ProductCode).hide();
                $('#warrantyJudgeText'+DCWarrantyId+ProductCode).text('Approved');
                toastr["success"]("Warranty Judgement by Service Approved Successfully");
            }
        }
    });
}

function changeWarrantyJudgementRejectStatus(DCWarrantyId,ProductCode){
    $.ajax({
        url: base_url + "report/changeWarrantyJudgementRejectStatus",
        type: "post",
        data: {DCWarrantyId : DCWarrantyId, ProductCode: ProductCode},
        dataType: "json",               
        success: function (response) {
            if(response==true){
                $('#warrantyJudge'+DCWarrantyId+ProductCode).hide();
                $('#warrantyJudgeReject'+DCWarrantyId+ProductCode).hide();
                $('#warrantyJudgeText'+DCWarrantyId+ProductCode).text('Rejected');
                toastr["success"]("Warranty Judgement by Service Rejected Successfully");
            }
        }
    });
}

function changeFactoryQAStatus(DCWarrantyId,ProductCode){
    $.ajax({
        url: base_url + "report/changeFactoryQAStatus",
        type: "post",
        data: {DCWarrantyId : DCWarrantyId, ProductCode: ProductCode},
        dataType: "json",               
        success: function (response) {
            if(response==true){
                $('#factoryQA'+DCWarrantyId+ProductCode).hide();
                $('#factoryQAReject'+DCWarrantyId+ProductCode).hide();
                $('#factoryQAText'+DCWarrantyId+ProductCode).text('Approved');
                toastr["success"]("Factory QA Approved Successfully");
            }
        }
    });
}

function changeFactoryQARejectStatus(DCWarrantyId,ProductCode){
    $.ajax({
        url: base_url + "report/changeFactoryQARejectStatus",
        type: "post",
        data: {DCWarrantyId : DCWarrantyId, ProductCode: ProductCode},
        dataType: "json",               
        success: function (response) {
            if(response==true){
                $('#factoryQA'+DCWarrantyId+ProductCode).hide();
                $('#factoryQAReject'+DCWarrantyId+ProductCode).hide();
                $('#factoryQAText'+DCWarrantyId+ProductCode).text('Rejected');
                toastr["success"]("Factory QA Rejected Successfully");
            }
        }
    });
}