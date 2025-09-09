function doCheckChassisNo(chassisno){
    $.ajax({
        type: "POST",
        url: base_url + "invoice/bikelistcheck",
        data: {search : chassisno},               
        dataType: "json",
        cache: false,
        success: function (res) {
            if(res['data'].length == 1){
                $("#engineno").val(res['data'][0]['EngineNo']);
                $("#model").val(res['data'][0]['ProductName']);
                $("#unitprice").val(res['data'][0]['UnitPrice']+'/-');
                $("#color").val(res['data'][0]['Color']);
            }else{
                $("#engineno").val('');
                $("#model").val('');
                $("#unitprice").val('');
                $("#color").val('');
            }
            doupdatetotalpayable();
        },
        error: function (msg) {
            response([]);
        }
    })
}

function ajaxFunction(request,response){
    $.ajax({
        type: "POST",
        url: base_url + "invoice/bikelist",
        data: {search : request},               
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
                    unitprice: el.unitprice
                };
            });
            response(transformed);                   
        },
        error: function (msg) {
            response([]);
        }
    })
}

function doupdatetotalpayable(){
    var unitprice           = parseInt($("#unitprice").val());          if(isNaN(unitprice)){       unitprice = 0; }
    var discount            = parseInt($("#discount").val());           if(isNaN(discount)){        discount = 0; }
    var interestpayable     = parseInt($("#interestpayable").val());    if(isNaN(interestpayable)){ interestpayable = 0; }    
    var totalpayable        = (unitprice - discount) + interestpayable; 
    $("#totalpayable").val(totalpayable);
}

$(document).ready(function () {                    
    
    $('#deteofbirth').datepicker({
      dateFormat: 'yy-mm-dd',
      startDate: '-3d',
      changeYear: true
    })
    
    $('#marriageday').datepicker({
      dateFormat: 'yy-mm-dd',
      startDate: '-3d',
      changeYear: true
    })
	
    $(".chassis").autocomplete({            
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
            $("#productcode").val(ui.item.productcode);
            $("#model").val(ui.item.productname);
            $("#engineno").val(ui.item.engineno);            
            $("#color").val(ui.item.color);
            $("#unitprice").val(ui.item.unitprice+'/-');    
            doupdatetotalpayable();
        },
        minLength: 1
    }).bind('keypress', function () {
        $(this).autocomplete("search");
    });

    $("#invoice-form").submit(function(e) {
        var msg = '';
        if ($.trim($('#isemi').val())== "1") {
            var emiamount    = parseInt($.trim($('#emiamount').val()));
            if(isNaN(emiamount)){ emiamount = 0; }
            var unitprice    = parseInt($.trim($('#unitprice').val()));
            if(isNaN(unitprice)){ unitprice = 0; }
            if(emiamount > (unitprice / 2) ){
                var halfamount = unitprice / 2;
                alert('EMI Amount can\'t be more then ' + halfamount);
                return false;
            }
        }
        var totalpayable    = parseInt($.trim($('#totalpayable').val()));
            if(isNaN(totalpayable)){ totalpayable = 0; }
        var totalamount     = parseInt($.trim($('#totalamount').val()));
            if(isNaN(totalamount)){ totalamount = 0; }
            
            if(totalpayable != totalamount){
                alert('Some problem in payment. Please update Payment\n');
                return false;
            }
        
        if($.trim($('#chassisno').val())==""){
             msg += 'Chassisno can not be blank.\n';
        }
        if($.trim($('#customername').val())==""){
            msg += 'Customer name can not be blank.\n';
        }
        
        if($.trim($('#fathername').val())==""){
            msg += "Father's Name can not be blank.\n" ;
        }
        
        if($.trim($('#mothername').val())==""){
            msg += "Mother's Name can not be blank.\n" ;
        }
        
        if($.trim($('#peraddress').val())==""){
            msg += "Permanent Address can not be blank.\n" ;
        }
        
        if($.trim($('#mobileno').val())==""){
            msg += "Mobile can not be blank.\n" ;
        }
        
        if($.trim($('#nid').val())==""){
            msg += "N.I.D/Passport No./Birth Certificate.\n" ;
        }
        
        if($.trim($('#deteofbirth').val())==""){
            msg += "Date of Birth can not be blank.\n" ;
        }
        
        if ($.trim($('#isemi').val())== "1") {
            if($.trim($('#installmentsize').val())==""){
                msg += "Select an installment size.\n" ;
            }
             
            if($.trim($('#emibank').val())==""){
                msg += "Select EMI bank.\n" ;
            }  
            if($.trim($('#emiamount').val())==""){
                msg += "Select enter Amount.\n" ;
            } 
        }
        if ($.trim($('#isexchange').val())== "1") {
            if($.trim($('#exchangebrand').val())==""){
                msg += "Select exchange brand.\n" ;
            }
             
            if($.trim($('#exchangeengineno').val())==""){
                msg += "Enter exchange engine no.\n" ;
            }  
            if($.trim($('#exchangechassisno').val())==""){
                msg += "Enter exchange chassis no.\n" ;
            } 
        }
        
        if (msg != "") {
            alert(msg);
            return false;
        }
    });
           
});

function invPreview() {    
    $.ajax ({
        type: "POST",
        url:  base_url + "invoice/invview",
        data: $("#invoice-form").serialize(),
        cache: false,
        success: function (res){            
            var popupWin = window.open('', '_blank', 'width=650,height=600,location=no,left=200px');
            popupWin.document.open();
            popupWin.document.write('<html><head><title>::Preview::</title><link rel="stylesheet" type="text/css" href="print.css" /></head><body>')
            popupWin.document.write(res);
            popupWin.document.write('</body></html>');
            popupWin.document.close();            
        }
    });        
}
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function hidecolumn(val){
    //alert(val);
    if(val == '1'){
        $('#installmentsize').prop('disabled', false);
        $('#emibank').prop('disabled', false);   
        $('#emiamount').prop('readonly', false);   
    }else{
        $('#installmentsize').prop('disabled', true);
        $('#emibank').prop('disabled', true);   
        $('#emiamount').prop('readonly', true);   
        $('#emiamount').val('');
    }
    //calculatePayableInterest();
}

function calculatePayableInterest(){
    var emibank             = $("#emibank").val();
    var installmentsize     = $("#installmentsize").val();
    var emiamount           = $("#emiamount").val();
    var string = "emibank=" + emibank + "&installmentsize=" + installmentsize + "&emiamount=" + emiamount;
    $.ajax({
        type: "GET",
        url: base_url + "invoice/getpayableinterest",
        data: "emibank=" + emibank + "&installmentsize=" + installmentsize + "&emiamount=" + emiamount,               
        dataType: "json",
        cache: false,
        success: function (res) {
            console.log(res);
            $("#interestpayable").val('');
            $("#interestrate").val('');
            if(res.length > 0){
                $("#interestpayable").val(res[0]['Interest']);
                $("#interestrate").val(res[0]['InterestRate']);
            }
            doupdatetotalpayable();
        },
        error: function (msg) {
            response([]);
        }
    })
}

function hidecolumnexchange(val){
    //alert(val);
    if(val == '1'){
        $('#exchangebrand').prop('disabled', false);   
        $('#exchangeengineno').prop('readonly', false);   
        $('#exchangechassisno').prop('readonly', false); 
    }else{
        $('#exchangebrand').prop('disabled', true);   
        $('#exchangeengineno').prop('readonly', true);   
        $('#exchangechassisno').prop('readonly', true);   
        $('#exchangebrand').val('');
        $('#exchangeengineno').val('');
        $('#exchangechassisno').val('');
    }
}

function enablecashamount(val){
    if(val == 'Cash'){
        currentstatus = document.getElementById("cashamount").readOnly;
        if(currentstatus == false){
            document.getElementById("cashamount").readOnly = true;
            document.getElementById("cashamount").value = '';
        }else{
            document.getElementById("cashamount").readOnly = false;
        }  
        //calculateTotal();
    }else if(val == 'Card'){
        var inputs = document.getElementsByName('tendertypeselect[]');
        for(var i = 0; i < inputs.length; i++) {
            currentstatus = inputs[i].disabled;
            if(currentstatus == false){
                inputs[i].disabled = true;
            }else{
                inputs[i].disabled = false;
            }            
        }
        if(document.getElementById('paymenttypecard').checked == false){
            var cardamount = document.getElementsByName('cardamount[]');
            for(var i = 0; i < cardamount.length; i++) {
                inputs[i].checked = false;
                cardamount[i].readOnly = true;
                cardamount[i].value = '';
            }
            document.getElementById('cardamount').value = '';
        }
        
    }
    calculateTotal();
}

function enableinput(inputid){    
    currentstatus = document.getElementById(inputid).readOnly;
    if(currentstatus == false){
        document.getElementById(inputid).readOnly = true;
        document.getElementById(inputid).value=''; 
        doCalculateCardAmount();
    }else{
        document.getElementById(inputid).readOnly = false;
    }
    calculateTotal();
}

function doCalculateCardAmount(){
    var cardamount = document.getElementsByName('cardamount[]');
    var totalvalue = 0;
    for(var i = 0; i < cardamount.length; i++) {
        caramountvalue = parseInt(cardamount[i].value);
        if(isNaN(caramountvalue)){ caramountvalue = 0; }
        totalvalue = totalvalue + caramountvalue;
    }
    document.getElementById("cardamount").value = totalvalue;
    calculateTotal();
}

function calculateTotal(){
    cashamount = parseInt(document.getElementById("cashamount").value);
        if(isNaN(cashamount)){ cashamount = 0; }
    cardamount = parseInt(document.getElementById("cardamount").value);
        if(isNaN(cardamount)){ cardamount = 0; }
    document.getElementById("totalamount").value = parseInt(cashamount +  cardamount);
}