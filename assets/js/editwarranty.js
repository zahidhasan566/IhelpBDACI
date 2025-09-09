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
            $(this).attr('readonly', true);
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
        console.log('focus');
    });
}

function removeRow(x) {
    var i = x.parentNode.parentNode.rowIndex;
    document.getElementById("invoicetable").deleteRow(i);
    calculateTotal()
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

function doChangeQuantity(x){
    quantity = $(x).parents().parents()[0]['children'][2]['children'][0]['value'];
    unitprice = $(x).parents().parents()[0]['children'][3]['children'][0]['value'];
    $(x).parents().parents()[0]['children'][4]['children'][0]['value'] = quantity * unitprice;
    calculateTotal();
}