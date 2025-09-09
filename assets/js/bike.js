var numArray = [];
var orderdetails = null;
var pndx = 0;
var undx = 0;
var vndx = 0;
var qndx = 0;
var tndx = 0;

$(document).ready(function () {
    $("#unitprice").empty();
    $("#vat").empty();
    $("#total").empty();    
    
    orderdetails = $('#biketable').DataTable( {
        "columnDefs": [
            { "targets": [ 0 ], "visible": false },
            { "className": "text-right", "targets": [ 2, 3, 4, 5 ] },
            { "className": "dt-center", "targets": [ "_all" ] }
        ],
        "columns": [
            { "name": "productcode" },
            { "name": "productname" },
            { "name": "unitprice" },
            { "name": "vat" },
            { "name": "qty" },
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
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
             
            // Update footer
            $( api.column( 5 ).footer() ).html(total+'/-');
        }
    } );
    
    pndx = orderdetails.column('productcode:name').index();
    undx = orderdetails.column('unitprice:name').index();
    vndx = orderdetails.column('vat:name').index();
    qndx = orderdetails.column('qty:name').index();
    tndx = orderdetails.column('total:name').index();
    
    
    function calculateTotal() {
        var totalprice = 0;

        var unitprice = parseFloat($("#unitprice").html());
        var vat = parseFloat($("#vat").html());
        if (isNaN(unitprice)) unitprice = 0;
        if (isNaN(vat)) vat = 0;
        var quantity = parseFloat($("#qty").val());
        if (isNaN(quantity)) quantity = 0;
        totalprice += ((unitprice+vat) * quantity);        

        $("#total").html(totalprice + "/-");                
    }
    
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
        var productcode = $('#products').val();
        var bikemodel = $('#products').find('option:selected').text();
                
        var bikeqty = parseFloat($("#qty").val());
        if (isNaN(bikeqty)) bikeqty = 0;
        
        var unitprice = parseFloat($("#unitprice").html());
        if (isNaN(unitprice)) unitprice = 0;
        
        var vat = parseFloat($("#vat").html());
        if (isNaN(vat)) vat = 0;
        
        var totalprice = parseFloat($("#total").html());
        if (isNaN(totalprice)) totalprice = 0;
                
        if (productcode == "") {
            alert("Please select bike model");
            return;
        }
        
        if (bikeqty == 0) {
            alert("Please select bike quantity");
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
                        table.cell(row, qndx).data(parseFloat(qdata)+parseFloat(bikeqty));                         
                        table.cell(row, tndx).data((parseFloat(qdata)+parseFloat(bikeqty)) * (parseFloat(udata)+parseFloat(vdata))+"/-" );
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
                bikemodel,                
                unitprice,
                vat,
                bikeqty,
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
    
    $('#products').change(function () {        
        $("#thistotal").html("0");
        $("#qty").val("0");
        $("#vat").empty();
        $("#unitprice").empty();
        $("#total").empty();

        var id = $(this).attr("id");        
        var itsList = "#products";
        var selectedprod = $(itsList).val();
        if (!selectedprod) {
            $("#unitprice").html("0.0");
            $("#vat").html("0.0");
        }        
        productcode = selectedprod;
        bus = $('#business').val();
        $.ajax({
            type: "POST",
            url: base_url + "orders/prodetails",
            data: {business: bus, productcode: productcode},
            dataType:"json",
            cache: false,
            success: function (response) {
                if(response.success === 2) {
                    window.location.href = response.redirect;
                } else if(response.success === 1) {
                    $("#unitprice").html(response.data[0].unitprice);
                    $("#vat").html(response.data[0].vat);                    
                }
            }            
        });        
    });         
});

function clearAll() {
    $("#products").val("");
    $("#vat").empty();
    $("#thistotal").html("0");
    $("#qty").val("0");
    $("#unitprice").empty();
    $("#total").empty();    
}

function calcTotalPrice() {
    var totalqty = $("#paidpartsqty").val();
    if (totalqty == "") {
        totalqty = 1;
    }
    var unitprice = $("#paidunitprice").val();
    var totalprice = 0;

    unitprice = parseFloat(unitprice);
    totalqty = parseFloat(totalqty);
    totalprice += (unitprice * totalqty);

    $("#thistotal").html(totalprice);
    //alert()
}
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function SaveOrder() {
    var bus = $("#business").val();
    var pros = [];
        
    orderdetails.rows().eq(0).each( function ( index ) {
        var row = orderdetails.row( index );
     
        var data = row.data();
        pros.push({
            business : bus,
            productcode : data[pndx],
            qnty : data[qndx] 
        });
    });
    var post = {
        data: pros 
    };
    var actionurl = $("#frmbike").attr('action');
    
    $.ajax({
        type: 'POST',
        url: actionurl,
        dataType: 'json',        
        data: post,        
        success: function( data, textStatus, jQxhr ){
            var res = $.parseJSON( data );
            if (res.success==1) {
                $('.alert-success').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> '+res.message ).show();
                $('.alert-success').delay(3000).fadeOut('slow');
                orderdetails.clear().draw();
            } else {
                $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+res.message ).show();
                $('.alert-danger').delay(3000).fadeOut('slow');
            }
                        
        },
        error: function( jqXhr, textStatus, errorThrown ){
            console.log( errorThrown );
        }
    });
}

