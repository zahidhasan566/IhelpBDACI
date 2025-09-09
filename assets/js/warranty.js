var svrpartstable = null;
var packsize = '';
var spndx = 0;
var spqty = 0;
var pndx = 0;
var pnndx = 0;
var undx = 0;
var vndx = 0;
var qndx = 0;
var tndx = 0;

$(document).ready(function () {
    //$('#submit').disable(true);
    $('textarea[rel="txtTooltip"]').tooltip({html:true});
    
    $("#submit").attr("disabled", true);
    $("#chargeamt").empty();
    $("#unitprice").empty();
    $("#vat").empty();
    $("#total").empty();
    $('#occurancedate').datepicker({  dateFormat: 'yy-mm-dd',maxDate: new Date()  })         
    
	$('#uploadpic1').on("change", function(e){ 
		$('#preview1').empty();
		$('#preview1').append('<div class="col-md-3 col-sm-4 col-xs-6">'                                
				+ '<img class="img-responsive" src="'+URL.createObjectURL(e.target.files[0])+'">'                                                            
				+ '</div>');
	})
	$('#uploadpic2').on("change", function(e){ 
		$('#preview2').empty();
		$('#preview2').append('<div class="col-md-3 col-sm-4 col-xs-6">'                                
				+ '<img class="img-responsive" src="'+URL.createObjectURL(e.target.files[0])+'">'                                                            
				+ '</div>');
	})
	$('#uploadpic3').on("change", function(e){ 
		$('#preview3').empty();
		$('#preview3').append('<div class="col-md-3 col-sm-4 col-xs-6">'                                
				+ '<img class="img-responsive" src="'+URL.createObjectURL(e.target.files[0])+'">'                                                            
				+ '</div>');
	})
	
	/*
	$('input[type=file]').on("change", function(e){  
        var total_file= $('input[type=file]')[0].files.length;
        for(var i=0;i<total_file;i++) {
            $('#preview').append('<div class="col-md-3 col-sm-4 col-xs-6">'                                
                                + '<img class="img-responsive" src="'+URL.createObjectURL(e.target.files[i])+'">'                                                            
                                + '</div>');
        }
    });
	*/
    $("#btnsearchall").on('click', function () {
        var chassis = $("#search").val();
        if (chassis === "") {
            alert("Please input chassis no.")
            return;
        }
        var url = base_url + "service/customerdetails";
        $.ajax ({
            type: "POST",
            url: url,
            data: {chassis: chassis},
            dataType: "json",
            cache: false,
            success: function (res){
                $("#getresult").html(res.content);    
                $("#mileage").attr({"min" : res.maxMileage});    
		$("#submit").attr("disabled", false);	
            }
        });
    });
    
//===================Search by JobCardNo===========================
    $("#btnJobcard").on('click', function () {
        console.log('hi');
        var jobCardNo = $("#jobCard").val();
        if (jobCardNo === "") {
            alert("Please input job card no.")
            return;
        }
        var url = base_url + "service/jobCardDetails";
        // console.log(jobCardNo);
        $.ajax ({
            type: "POST",
            url: url,
            data: {jobCardNo: jobCardNo},
            // dataType: "json",
            cache: false,
            success: function (res){
                console.log("=========",res);
                $("#getresult").html(res.content);    
                $("#mileage").attr({"min" : res.maxMileage}); 
                if(res.jobCardDetails.length>0){
                    $("#search").val(res.jobCardDetails[0].ChassisNo);   
                    $("#mileage").val(res.jobCardDetails[0].Mileage);   
                    $("#problemname").val(res.jobCardDetails[0].ProblemDetails);   
                    $("#technicianname").val(res.jobCardDetails[0].TechnicianName);   
                    $("#partsInfo").html(res.partsString);
                }

		$("#submit").attr("disabled", false);	
            }
        });
    });
//================================================================

    $(".chassis").autocomplete({        
        source: function(request, response){
            $.ajax({
                type: "POST",
                url: base_url + "service/bikelist/0",
                data: {search : request.term},               
                dataType: "json",
                cache: false,
                success: function (res) {
                    var transformed = $.map(res.data, function (el) {
                        return {
                            label: el.chassisno,
                            value: el.chassisno,
                            productcode: el.productcode,
                            productname: el.productname,
                            engineno: el.engineno,                            
                            color: el.color,
                            unitprice: el.unitprice,
                            packsize: el.packsize
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
        select: function (event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);
            packsize = ui.item.packsize;
            if (ui.item.label.indexOf('R15') != -1) {
                packsize = 'R15';
            }
            var url = base_url + "service/customerdetails";
            $.ajax ({
                type: "POST",
                url: url,
                data: {chassis: ui.item.value},
                dataType: "json",
                cache: false,
                success: function (res){
                    $("#getresult").html(res.content);
                    $("#submit").attr("disabled", false);
                    $('#search').attr('readonly', 'true');
                    $("#mileage").attr({"min" : res.maxMileage}); 
                }
            });            
        },
        minLength: 1
    }).bind('keypress', function () {
        $(this).autocomplete("search");
    });

    sparepartsauto();         
});


function sparepartsauto(){
    $(".spareparts").autocomplete({        
        source: function(request, response){
            $.ajax({
                type: "POST",
                url: base_url + "orders/sparepartslist/1",
                data: {search : request.term},               
                dataType: "json",
                cache: false,
                success: function (res) {
                    var transformed = $.map(res.data, function (el) {
                        return {
                            label: el.productname,
                            value: el.productcode,
                            unitprice: parseFloat(el.mrp),
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
            console.log('focus');
        },
        select: function (event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);            
            parentvalue = $(this).parent();
            parentvalue[0]['children'][0]['value'] = ui.item.value;
            parentvalue[0]['children'][1]['value'] = ui.item.unitprice;
            $(parentvalue).parents()[0]['children'][2]['children'][0]['value'] = 1;
            $(parentvalue).parents()[0]['children'][3]['children'][0]['value'] = ui.item.unitprice;
            $(parentvalue).parents()[0]['children'][4]['children'][0]['value'] = ui.item.unitprice;
            $(parentvalue).parents()[0]['children'][5]['children'][0]['value'] = 0;
            calculateTotal();
            //$(this).attr('readonly', true);
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
        console.log('focus');
    });
}

function calculateTotal(){
    var arr = document.getElementsByName('totalprice[]');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('totalpricesum').innerHTML = tot;
    
    var arr = document.getElementsByName('servicecharge[]');
    var tot2=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot2 += parseInt(arr[i].value);
    }
    document.getElementById('servicechargetotal').innerHTML = tot2;
    document.getElementById('grandtotal').innerHTML = tot + tot2;
}

//function removeRow(x){
function removeRow(x) {
    console.log(x);
    var i = x.parentNode.parentNode.rowIndex;
    document.getElementById("invoicetable").deleteRow(i);
    calculateTotal()
}

function doChangeQuantity(x){
    quantity = $(x).parents().parents()[0]['children'][2]['children'][0]['value'];
    unitprice = $(x).parents().parents()[0]['children'][3]['children'][0]['value'];
    $(x).parents().parents()[0]['children'][4]['children'][0]['value'] = quantity * unitprice;
    calculateTotal();
}

function clearAll() {
    $("#services").val("");
    $("#chargeamt").html("");
    $("#spareparts").val("");
    $("#productcode").val("");
    $("#vat").empty();
    $("#thistotal").html("0");
    $("#qty").val("0");
    $("#unitprice").empty();
    $("#total").empty();    
}

function validation(){
    var occurancedate = $("#occurancedate").val();
    if(!occurancedate){
        alert('Please select occurance date');
        $("#occurancedate").focus();
        return false;
    }
    var typeofwarranty = $("#typeofwarranty").val();
    if(!typeofwarranty){
        alert('Please select type of warranty');
        $("#typeofwarranty").focus();
        return false;
    }
    var sourceofinformation = $("#sourceofinformation").val();
    if(!sourceofinformation){
        alert('Please select source of information');
        $("#sourceofinformation").focus();
        return false;
    }
    var seriousness = $("#seriousness").val();
    if(!seriousness){
        alert('Please select seriousness');
        $("#seriousness").focus();
        return false;
    }
    var mileage = $("#mileage").val();
    if(!mileage){
        alert('Please enter mileage');
        $("#mileage").focus();
        return false;
    }
    var technicianname = $("#technicianname").val();
    if(!technicianname && technicianname.length < 2){
        alert('Please enter technician name');
        $("#technicianname").focus();
        return false;
    }
    
    var serviceschedule = $("#serviceschedule").val();
    if(!serviceschedule){
        alert('Please select service schedule');
        $("#serviceschedule").focus();
        return false;
    }
    
    var problemname = $("#problemname").val();
    if(!problemname && problemname.length < 10){
        alert('Please enter problem name');
        $("#problemname").focus();
        return false;
    }
    
    var invoicetype = document.getElementsByName("invoicetype[]");
    var spareparts = document.getElementsByName("spareparts[]");
    var quantity = document.getElementsByName("quantity[]");
    var servicecharge = document.getElementsByName("servicecharge[]");
    
    for(i = 0; i < invoicetype.length; i++){
	if(invoicetype[i].value == ""){
            alert("Please select invoice type");
            invoicetype[i].focus(); 
            return false;
        }
        if(spareparts[i].value == ""){
            alert("Please enter parts name");
            spareparts[i].focus(); 
            return false;
        }
        if(quantity[i].value == ""){
            alert("Please enter quantity");
            quantity[i].focus(); 
            return false;
        }
        if(servicecharge[i].value == ""){
            alert("Please enter quantity");
            servicecharge[i].focus(); 
            return false;
        }
	
    }
}
/*
function validation(){
    var productcode = $("#productcode").val();
    if(productcode){
        //alert('done');
        var mileage = $("#mileage").val();  
        if(mileage){
            //return true;
            var problemdetails = $("#problemdetails").val();  
            if(problemdetails){
                return true
            }else{
                alert('Please enter problem details');
                return false;    
            }
        }else{
            alert('Please enter mileage');
            return false;
        }  
    }else{
        alert('Please select spare parts.');    
        return false;
    }
}
*/

function otherproductinsert(){
    var url = base_url + 'service/createotherproduct';
    var smscode = $("#smscode").val();
    if(!smscode){
        alert('Please enter Sl Number.');
        $( "#smscode" ).focus();        
        return false;
    }
    var productname = $("#productname").val();
    if(!productname){
        alert('Please enter product name.');
        $( "#productname" ).focus();        
        return false;
    }
    
    var unitprice = parseInt($("#unitprice").val());
    if(!unitprice){
        alert('Please enter unitprice.');
        $( "#unitprice" ).focus();        
        return false;
    }
    var datastring = "smscode=" + smscode + "&productname=" + productname + "&unitprice=" + unitprice;
    $.ajax ({
        type: "POST",
        url: url,
        data: datastring,  
        success: function (res){
            if(res == 1){
                $("#smscode").val('');
                $("#productname").val('');
                $("#unitprice").val('');
                $( "#successmsg" ).html("Successfully add.");
            }                                           
        }
    });
    return false;
}