$(document).ready(function () {    
    $(".chassis").autocomplete({        
        source: function(request, response){
           $.ajax({
               type: "POST",
               url: base_url + "service/bikeregistered",
               data: {search : request.term},               
               dataType: "json",
               cache: false,
               success: function (res) {
                    var transformed = $.map(res.data, function (el) {
                        return {
                            label: el.chassisno,
                            value: el.chassisno,
                            customername: el.customername,
                            productname: el.productname,                            
                            engineno: el.engineno,                            
                            color: el.color,
                            bikeage: el.bikeage,
                            servregid: el.servregid
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
            $('#customername').val(ui.item.customername);
            $('#productname').val(ui.item.productname);
            $('#chassisno').val(ui.item.value);
            $('#engineno').val(ui.item.engineno);
            $('#color').val(ui.item.color);
            $('#bikeage').val(ui.item.bikeage);
            $('input[name="servregid"]').val(ui.item.servregid);
            $('input[name="regaction"]').val('UPDATE');                        
        },
        minLength: 1
    }).bind('keypress', function () {
        $(this).autocomplete("search");
    });
    
    /*add service from list end*/
    
    
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}




