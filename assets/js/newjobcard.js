$(document).ready(function () {
    document.getElementById("chassisno").focus();
    onsubmitfromto("registrationno", "bookingno");
    onsubmitfromto("bookingno", "serial");
    onsubmitfromto("serial", "mileage");
    onsubmitfromto("mileage", "bay");
    
    sparepartsauto();         
    workautocomplete();

    // Disable submit form on keydown
    $(document).on('keydown','.noEnterSubmit',function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }

    });

    
    $('#jobstarttime').timepicker({
        ignoreReadonly: true,    
        'minTime': '09:00am',
        'maxTime': '11:50pm',
        'timeFormat': 'h:i A',
        'step': '10'
    }).on('changeTime', function(e) {
        document.getElementById("jobstarttime").readOnly = true;
        document.getElementById("jobendtime").focus();
        $("#jobendtime").timepicker("option", "minTime", $('#jobstarttime').val());
    });
    
    $('#jobendtime').timepicker({
        'minTime': '09:00am',
        'maxTime': '11:50pm',
        'timeFormat': 'h:i A',
        'step': '10'
    }).on('changeTime', function(e) {
        document.getElementById("jobendtime").readOnly = true;
    });


    $("#chassisno").autocomplete({            
        source: function(request, response){
            ajaxFunction(request.term,response);
        },
        focus: function (event, ui) {
            event.preventDefault();
            //$(this).val(ui.item.label);
        },
        select: function (event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);            
            $("#engineno").val(ui.item.engineno);
            $("#customername").val(ui.item.customername);
            $("#mobileno").val(ui.item.mobileno);
            $("#brandname").val(ui.item.brandname);
            $("#modelname").val(ui.item.productname);
            $("#purchasedate").val(ui.item.invoicedate);
            $("#underwarrenty").val(ui.item.underwarrenty);
            $("#address").val(ui.item.address);
            $("#underwarrenty").prop('disabled', true);
            $("#jobtype").prop('disabled', false);
            $("#submitbutton").prop('disabled', false);
            document.getElementById("registrationno").focus();
        },
        minLength: 1
    }).bind('keypress', function () {
        $(this).autocomplete("search");
    });
    
    
    
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper   	= $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $("#add_field_button"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div class="col-md-12 padding0" style="margin-bottom: 5px;"><div  class="col-md-10 padding0"><input type="text" name="problemdetails[]" class="form-control" placeholder="Problem Details"></div></div>'); //add input box
        }
    });

    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })


    // populate technician Bay
    $("#technician").on('change',function () {
       const defaultBay =$(this).find(':selected').attr('data-default-bay');
        $('#bay option[value="'+defaultBay+'"]').prop('selected', true);
    });
    
});

function doLoadServiceNo(jobtype){
    
    var chassisno   = $("#chassisno").val();
    var jobtypename = $("#jobtype option:selected").text();
    
    if(jobtype == 2){
        $("#serviceno").prop('disabled', false);
        var url = base_url + "service/customerdetails";
        $.ajax ({
            type: "POST",
            url: url,
            data: {chassis: chassisno},
            dataType: "json",
            cache: false,
            success: function (res){
                $('#serviceno').empty();
                $.each(res.data.service, function(i, item) {
                    $('#serviceno')                 
                        .append($("<option "+((i==0) ? 'selected= "selected"':"")+"></option>")
                        .attr("value",item.fid)
                        .text(item.service));
                }); 
            }
        });
    }else{
        $("#serviceno").empty();
        $('#serviceno').append("<option value='"+jobtype+"'>"+jobtypename+"</option>");
        $("#serviceno").prop('disabled', true);        
    }
}

function doCheckChassisNo(chassisno){
    $.ajax({
        type: "POST",
        url: base_url + "jobcard/bikelistcheck",
        data: {search : chassisno},               
        dataType: "json",
        cache: false,
        success: function (res) {
            console.log(res);
            if(res['data'].length == 1){
                $("#engineno").val(res['data'][0]['engineno']);
                $("#customername").val(res['data'][0]['customername']);
                $("#mobileno").val(res['data'][0]['mobileno']);
                $("#brandname").val(res['data'][0]['brandname']);
                $("#modelname").val(res['data'][0]['productname']);
                $("#purchasedate").val(res['data'][0]['invoicedate']);
                $("#underwarrenty").val(res['data'][0]['underwarrenty']);
                $("#underwarrentyvalue").val(res['data'][0]['underwarrenty']);
                $("#address").val(res['data'][0]['Address']);
                $("#underwarrenty").prop('disabled', true);
                $("#jobtype").prop('disabled', false);
                $("#submitbutton").prop('disabled', false);
                document.getElementById("registrationno").focus();
            }else{
                $("#engineno").val('');
                $("#customername").val('');
                $("#mobileno").val('');
                $("#brandname").val('');
                $("#modelname").val('');
                $("#purchasedate").val('');
                $("#underwarrenty").val('');
                $("#address").val('');
                $("#underwarrenty").prop('disabled', false);
                $("#jobtype").prop('disabled', false);
                $("#submitbutton").prop('disabled', true);
                document.getElementById("chassisno").focus();
            }
        },
        error: function (msg) {
            response([]);
        }
    })
}

function ajaxFunction(request,response){
    $.ajax({
        type: "POST",
        url: base_url + "jobcard/bikelist",
        data: {search : request},               
        dataType: "json",
        cache: false,
        success: function (res) {
            var transformed = $.map(res.data, function (el) {
                return {
                    label: el.chassisno,
                    value: el.chassisno,
                    customername: el.customername,
                    engineno: el.engineno,
                    mobileno: el.mobileno,
                    brandname: el.brandname,                            
                    productname: el.productname,
                    invoicedate: el.invoicedate,
                    underwarrenty: el.underwarrenty,
                    address: el.Address
                };
            });
            response(transformed);                   
        },
        error: function (msg) {
            response([]);
        }
    })
}

function onsubmitfromto(from, to){
    $( "#" + from ).keydown(function (e){
        if(e.keyCode == 13){
            document.getElementById(to).focus();
        }
    })
}

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
                            label:      el.productname,
                            value:      el.productcode,
                            unitprice:  parseFloat(el.mrp),
                            vat:        el.vat
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
            $(parentvalue).parents()[0]['children'][1]['children'][0]['value'] = 1;
            $(parentvalue).parents()[0]['children'][2]['children'][0]['value'] = ui.item.unitprice;
            $(parentvalue).parents()[0]['children'][3]['children'][0]['value'] = ui.item.unitprice;
            $(parentvalue).parents()[0]['children'][4]['children'][0]['value'] = 0;
            $(parentvalue).parents()[0]['children'][5]['children'][0]['value'] = 0;
            calculateTotal();
            $(this).attr('readonly', true);
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
        console.log('focus');
    });
}


function workautocomplete(){
    $(".work").autocomplete({        
        source: function(request, response){
            $.ajax({
                type: "POST",
                url: base_url + "jobcard/loadworklist",
                data: {search : request.term},               
                dataType: "json",
                cache: false,
                success: function (res) {
                    var transformed = $.map(res, function (el) {
                        return {
                            label: el.WorkName,
                            value: el.WorkCode,
                            workname: el.WorkName,
                            workcode: el.WorkCode,
                            workrate: parseInt(el.WorkRate)
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
        },
        select: function (event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);            
            parentvalue = $(this).parent();
            parentvalue[0]['children'][0]['value'] = ui.item.workcode;
            parentvalue[0]['children'][1]['value'] = ui.item.workrate;
            $(parentvalue).parents()[0]['children'][1]['children'][0]['value'] = ui.item.workrate;
            $(parentvalue).parents()[0]['children'][2]['children'][0]['value'] = 0;
            calculatetotalforwork();
            $(this).attr('readonly', true);
        },
        minLength: 1
    }).bind('focus', function () {
        console.log('a');
        $(this).autocomplete("search");
    });
}

function doChangeDiscountType(discounttype){
    if(discounttype == 'ACI Employee'){
        $("#discount").val(0);
        $("#staffid").val('');
        document.getElementById("discount").readOnly = true;
        document.getElementById("staffid").readOnly = true;
    }else if(discounttype == 'Campaign'){
        $("#discount").val(0);
        $("#staffid").val();
        document.getElementById("discount").readOnly = true;
        document.getElementById("staffid").readOnly = true;
    }else{
        document.getElementById("discount").readOnly = true;
        document.getElementById("staffid").readOnly = true;
    }
}


function calculatetotalforwork(){
    var arr = document.getElementsByName('worktotalprice[]');
    var discountarr = document.getElementsByName('workdiscount[]');
    var tot=0;
    var discountprice = 0;
    var afterdiscounttotal = 0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
            if(!isNaN(parseInt(discountarr[i].value))){
                workvalue = parseInt(arr[i].value);
                workvaluepercentage = parseInt(discountarr[i].value);
                if(isNaN(workvalue)){ workvalue = 0; }
                if(isNaN(workvaluepercentage)){ workvaluepercentage = 0; }
                discountprice += (workvalue * workvaluepercentage)/100;
            }
    }
    document.getElementById('workdiscountprice').innerHTML = discountprice.toFixed(2);
    document.getElementById('workgrandtotal').innerHTML = (tot-discountprice).toFixed(2);
}

function removeRowforwork(x) {
    var i = x.parentNode.parentNode.rowIndex;
    document.getElementById("worktable").deleteRow(i);
    calculatetotalforwork();
}

function calculateTotal(){
    var arr = document.getElementsByName('totalprice[]');
    var discountarr = document.getElementsByName('partsdiscount[]');
    var tot=0;
    var discountprice = 0;
    var afterdiscounttotal = 0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
            if(!isNaN(parseInt(discountarr[i].value))){
                salesvalue = parseInt(arr[i].value);
                salesdiscountpercentage = parseInt(discountarr[i].value);
                if(isNaN(salesvalue)){ salesvalue = 0; }
                if(isNaN(salesdiscountpercentage)){ salesdiscountpercentage = 0; }
                discountprice += (salesvalue*salesdiscountpercentage)/100;
            }
    }
    document.getElementById('totalpricesum').innerHTML = tot.toFixed(2);
    document.getElementById('discountprice').innerHTML = 'Discount Price: '+discountprice.toFixed(2);
    
    var arr = document.getElementsByName('servicecharge[]');
    var tot2=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot2 += parseInt(arr[i].value);
    }
    document.getElementById('servicechargetotal').innerHTML = tot2.toFixed(2);
    document.getElementById('grandtotal').innerHTML = ((tot+tot2)-discountprice).toFixed(2);
}

//function removeRow(x){
function removeRow(x) {
    var i = x.parentNode.parentNode.rowIndex;
    document.getElementById("invoicetable").deleteRow(i);
    calculateTotal();
}

function doChangeQuantity(x){
    productcode = $(x).parents().parents()[0]['children'][0]['children'][0]['value'];
    quantity = $(x).parents().parents()[0]['children'][1]['children'][0]['value'];
    unitprice = $(x).parents().parents()[0]['children'][2]['children'][0]['value'];
    $(x).parents().parents()[0]['children'][3]['children'][0]['value'] = quantity * unitprice;
    checkPartStock(productcode, quantity, x);
    calculateTotal();
}

function checkPartStock(pcode, qty, index) {
    var qtyIndex    = $(index).parents().parents()[0]['children'][1]['children'][0];
    var totalIndex  = $(index).parents().parents()[0]['children'][3]['children'][0];
    $.ajax({
        type: "POST",
        url: base_url + "jobcard/checkPartStock",
        data: { productcode: pcode },
        dataType: "json",
        cache: false,
        success: function (res) {
            if (res.success == 1) {
                if (parseInt(qty) > parseInt(res.partstock)) {
                    qtyIndex.value = 0;
                    qtyIndex.style.borderColor = 'red';
                    totalIndex.value = 0;
                    calculateTotal();
                    $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+res.message ).show();
                    $('.alert-danger').delay(3000).fadeOut('slow'); 
                }else{
                    qtyIndex.style.borderColor = 'rgb(11, 11, 105)';
                }
            }
        },
        error: function (msg) {
            response([]);
        }
    });
}