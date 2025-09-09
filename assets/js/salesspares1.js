var numArray = [];
var orderdetails = null;
var pndx = 0;
var undx = 0;
var vndx = 0;
var qndx = 0;
var dndx = 0;
var dpndx = 0;
var tdpndx = 0;
var tndx = 0;

$(document).ready(function () {
    $("#unitprice").empty();
    $("#vat").empty();
    $("#discount").empty();
    $("#discountprice").empty();    
    $("#total").empty();
    
    orderdetails = $('#biketable').DataTable( {
        "columnDefs": [
            { "targets": [ 0, 3 ], "visible": false },
            { "className": "text-right", "targets": [ 2, 3, 4, 5, 6, 7 ] },
            { "className": "dt-center", "targets": [ "_all" ] }
        ],
        "columns": [
            { "name": "productcode" },
            { "name": "productname" },
            { "name": "unitprice" },
            { "name": "vat" },
            { "name": "qty" },
            { "name": "discount" },
            { "name": "discountprice" },
            { "name": "total" },
            { "name": "delete" }
        ],
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching" :false,                
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,\/=-]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
             
            // Update footer
            $( api.column( 7 ).footer() ).html(total.toFixed(2)+'/-');

            // Total discount price over all pages
            totaldiscountprice = api
                                .column( 6 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
             
            // Update footer for total discount price
            $( api.column( 6 ).footer() ).html(totaldiscountprice.toFixed(2)+'/-');
        }
    } );
    
    pndx = orderdetails.column('productcode:name').index();
    undx = orderdetails.column('unitprice:name').index();
    vndx = orderdetails.column('vat:name').index();
    qndx = orderdetails.column('qty:name').index();
    dndx = orderdetails.column('discount:name').index();
    dpndx = orderdetails.column('discountprice:name').index();
    tdpndx = orderdetails.column('totaldiscountprice:name').index();
    tndx = orderdetails.column('total:name').index();

    $('#biketable tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        } else {
            orderdetails.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
    
    $('#add').on('click', function (event) {
        event.preventDefault();
        var productcode = $('#productcode').val();
        var spareparts = $('#spareparts').val();
        
        var spqty = parseFloat($("#qty").val());
        if (isNaN(spqty)) spqty = 0;

        var spdiscount = parseFloat($("#discount").val());
        if (isNaN(spdiscount)) spdiscount = 0;

        var spdiscountprice = parseFloat($("#discountprice").html());
        if (isNaN(spdiscountprice)) spdiscountprice = 0;
        
        var unitprice = parseFloat($("#unitprice").html());
        if (isNaN(unitprice)) unitprice = 0;
        
        var vat = parseFloat($("#vat").html());
        if (isNaN(vat)) vat = 0;
        
        var totalprice = parseFloat($("#total").html());
        if (isNaN(totalprice)) totalprice = 0;

        var totaldiscountprice = parseFloat($("#discountprice").html());
        if (isNaN(totaldiscountprice)) totaldiscountprice = 0;
                
        if (productcode == "") {
            alert("Please select a spareparts");
            return;
        }
        
        if (spqty == 0) {
            alert("Please select parts quantity");
            return;
        }
        
        if (unitprice == 0) {
            alert("Unit price can not be empty or 0.");
            return;
        }
        
        var table = orderdetails;
        
        var duplicate = false;
        
        var idx = table
            .columns( 'productcode:name' )
            .data()
            .eq( 0 ) // Reduce the 2D array into a 1D array of data
            .indexOf( productcode );
        
        if (idx != -1) {
            duplicate = true;
            $( "#dialog-confirm" ).dialog({
                  resizable: false,
                  height: "auto",
                  width: 400,
                  modal: true,
                  buttons: {
                    "Yes": function() {
                        $( this ).dialog( "close" );
                        var row = table.row(idx).node();
                        var udata = table.cell(row, undx).data();
                        var vdata = table.cell(row, vndx).data();
                        var qdata = table.cell(row, qndx).data();
                        var discountdata = table.cell(row, dndx).data();
                        var discountpricedata = table.cell(row, dpndx).data();
                        var totaldiscountpricedata = table.cell(row, tdpndx).data();
                        table.cell(row, qndx).data(parseFloat(qdata)+parseFloat(spqty));
                        table.cell(row, dndx).data(parseFloat(discountdata));
                        table.cell(row, dpndx).data(parseFloat(discountpricedata));
                        table.cell(row, tdpndx).data(parseFloat(totaldiscountpricedata)+"/-" );                         
                        table.cell(row, tndx).data((parseFloat(qdata)+parseFloat(spqty)) * (parseFloat(udata))+"/-" );
                        table.draw();
                    },
                    Cancel: function() {
                      $( this ).dialog( "close" );
                    }
                  }
             });                                      
        }
                 
        // Draw once all updates are done
        table.draw();
            
        if (!duplicate) {
            orderdetails.row.add([
                productcode,
                spareparts,                
                unitprice,
                vat,
                spqty,
                spdiscount,
                spdiscountprice,
                totalprice,
                '<input type="button" value="X" class="close bikeclose"/>'
            ]).draw( false );             
        }
                                    
        //calculateTotal();
        clearAll();
    });

    $('#product-container').on('keyup', '.qty', function () {
        //var id = $(this).attr("id");
        calculateTotal();
    });
    
    $('#product-container').on('keyup', '.discount', function () {
        calculateTotal();
    });

    $('#product-container').on('click', '.minus', function () {
        var id = $(this).attr("id");        
        var qty = parseFloat($('#qty').val());
        if (qty) {
            qty -= 1;
        } else {
            qty = 0;
        }
        $('#qty').val(qty);
        calculateTotal();
    });
    $('#product-container').on('click', '.plus', function () {
        var id = $(this).attr("id");        
        var qty = parseFloat($('#qty').val());
        if (qty) {
            qty += 1;
        } else if (qty === 0) {
            qty += 1;
        } else {
            qty = 0;
        }
        $('#qty').val(qty);
        calculateTotal();
    });
    $('.addedbikelist').on('click', '.close', function () {
        $('#biketable tbody').click();
        orderdetails.row('.selected').remove().draw( false );
    });
    
    $(".paidparts").autocomplete({        
        source: function(request, response){
           $.ajax({
               type: "POST",
               url: base_url + "orders/sparepartslist",
               data: {search : request.term},               
               dataType: "json",
               cache: false,
               success: function (res) {
                    var transformed = $.map(res.data, function (el) {
                        return {
                            label: el.productname,
                            value: el.productcode,
                            rackname: el.rackname,
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
            $("#unitprice").html(ui.item.mrp);
            $("#vat").html(ui.item.vat);
            $("#qty").val(0);
            $("#productcode").val(ui.item.value);
            $("#rackname").html(ui.item.rackname);
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
    }); 
});

function calculateTotal() {
    var totalprice = 0;
    var totalDiscountprice = 0;

    var unitprice = parseFloat($("#unitprice").html());
    var vat = parseFloat($("#vat").html());
    if (isNaN(unitprice)) unitprice = 0;
    if (isNaN(vat)) vat = 0;
    var quantity = parseFloat($("#qty").val());
    if (isNaN(quantity)) quantity = 0;
    var discount = parseFloat($("#discount").val());
    if(isNaN(discount)) discount = 0;
    
    totalprice += Math.round (((unitprice) * quantity)*100) / 100;        

    var discountprice = (totalprice*discount)/100;

    totalDiscountprice += Math.round(discountprice);

    var afterDiscountTotal = totalprice-discountprice;

    $("#discountprice").html(discountprice + "/-");
    $("#total").html(afterDiscountTotal + "/-");                
}

function clearAll() {
    $("#spareparts").val("");
    $("#productcode").val("");
    $("#vat").empty();
    $("#thistotal").html("0");
    $("#rackname").html("");
    $("#qty").val("0");
    $("#unitprice").empty();
    $("#discount").val("");
    $("#discountprice").empty(); 
    $("#total").empty();   
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function SavePartsInvoice() {
    var bus = $("#business").val();
    var customername = $("#customername").val();
    var address = $("#address").val();
    var mobileno = $("#mobileno").val();
    var mechanicscode = $("#mechanicscode").val();
    var pros = [];    
    
    if (customername=='') {
        alert('Customer name can not be blank.');
        return;
    }
    orderdetails.rows().eq(0).each( function ( index ) {
        var row = orderdetails.row( index );
     
        var data = row.data();
        
        pros.push({
            business : bus,
            productcode : data[pndx],
            unitprice : data[undx],
            vat : data[vndx],
            qnty : data[qndx],
            discount : data[dndx]
        });
    });
    var post = {
        customername: customername,
        address: address,
        mobileno: mobileno,
        mechanicscode : mechanicscode,
        data: pros 
    };
    
    var actionurl = $("#salesspare").attr('action');    
    $.ajax({
        type: 'POST',
        url: actionurl,
        dataType: 'json',        
        data: post,        
        success: function( data, textStatus, jQxhr ){
            var res = data;
            if (res.success==1) {
                $('.alert-success').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> '+res.message ).show();
                $('.alert-success').delay(3000).fadeOut('slow');                
                if (res.redirect !== '') window.open(res.redirect, '_blank', 'width=650,height=600,location=no,left=200px');
                $("#customername").val('');
                $("#address").val('');
                $("#mobileno").val('');
                orderdetails.clear().draw();
            } else {
                $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+res.message ).show();
                $('.alert-danger').delay(3000).fadeOut('slow');
            }
                        
        },
        error: function( jqXhr, textStatus, errorThrown ){
            $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+errorThrown ).show();
            $('.alert-danger').delay(3000).fadeOut('slow');
        }
    });
}

