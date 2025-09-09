$(document).ready(function () {
    
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
                $('#freesscheduleid').empty();
                $.each(res.data.service, function(i, item) {
                    $('#freesscheduleid')
                        .append($("<option "+((i==0) ? 'selected= "selected"':"")+"></option>")
                            .attr("value",item.fid)
                            .text(item.service));
                });                                
            }
        });
    });
    
    $(".chassis").autocomplete({        
        source: function(request, response){
           $.ajax({
               type: "POST",
               url: base_url + "service/bikelist",
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
                            unitprice: el.unitprice
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
            var url = base_url + "service/customerdetails";
            $.ajax ({
                type: "POST",
                url: url,
                data: {chassis: ui.item.value},
                dataType: "json",
                cache: false,
                success: function (res){
                    $("#getresult").html(res.content);
                    $('#freesscheduleid').empty();
                    $.each(res.data.service, function(i, item) {
                        $('#freesscheduleid')                 
                            .append($("<option "+((i==0) ? 'selected= "selected"':"")+"></option>")
                            .attr("value",item.fid)
                            .text(item.service));
                    }); 
                }
            });            
        },
        minLength: 1
    }).bind('keypress', function () {
        $(this).autocomplete("search");
    });
    
    /*add service from list end*/
    $(".parts").autocomplete({        
        source: function(request, response){
           $.ajax({
               type: "POST",
               url: base_url + "service/sparepartslist",
               data: {search : request.term},               
               dataType: "json",
               cache: false,
               success: function (res) {
                    var transformed = $.map(res.data, function (el) {
                        return {
                            label: el.productname,
                            value: el.productcode,
                            unitprice: el.unitprice,
                            vat: el.vat, 
                            mrp: el.mrp
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
            
            var unitprice = ui.item.mrp;            
            var productcode = ui.item.value;             
            var id = $(this).attr('id');                        
            var serviceid = $(this).attr('id').split('_')[1];
            var servicesl = $("#servicesl_"+serviceid).html();
            
            $('#partstable tbody').append('<tr class="child"><td>'+ servicesl +'</td>'
                + '<td>' + ui.item.label + '</td><td>1</td>'
                + '<td>' + unitprice + '</td><td><input type="button" value="X" class="close sparts"/></td>'
                + '<input type="hidden" value="' + serviceid + '" name="serviceid[]"/>'
                + '<input type="hidden" value="' + productcode + '" name="productcode[]"/>'
                + '<input type="hidden" value="' + ui.item.label + '" name="productname[]"/>'
                + '<input type="hidden" value="' + unitprice + '" name="unitprice[]"/>'
                + '</tr>'
                );        
        },
        minLength: 1
    }).bind('keypress', function () {
        $(this).autocomplete("search");
    });
    
    $("#addedfreeproducts").on('click', ".sparts", function () {
        $(this).closest(".child").remove();
        $(".parts").val("");
    });

    $('.checkchange').click(function () {
        var index = $(this).val();
        if ($('#changed_' + index).is(":checked")) {
            $('#parts_' + index).prop("disabled", false);
        } else {
            $('#parts_' + index).prop("disabled", true);
            $('#parts_' + index).val("");
            $(".addedfreepart_" + index).remove();
        }
    });
    
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}




