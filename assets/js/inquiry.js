var inquirytable = null;

var windx = 0;
var vndx = 0;
var adndx = 0;
var mondx = 0;
var andx = 0;
var vndx = 0;
var dndx = 0;
var pndx = 0;

$(document).ready(function () {        
    inquirytable = $('#inquirytable').DataTable( {
        "columnDefs": [            
            { "targets": [ 0 ], "visible": false },            
            { "className": "dt-left", "targets": [ "_all" ] }
        ],
        "columns": [
            { "name": "productcode" },
            { "name": "numberofcustomer" },
            { "name": "visitorname" },
            { "name": "address" },
            { "name": "mobileno" },
            { "name": "age" },
            { "name": "days" },
            { "name": "product" },            
            { "name": "action" }
        ],
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching" :false        
    });
 
    windx = inquirytable.column('numberofcustomer:name').index();
    vndx = inquirytable.column('visitorname:name').index();
    adndx = inquirytable.column('address:name').index();
    mondx = inquirytable.column('mobileno:name').index();
    andx = inquirytable.column('age:name').index();
    dndx = inquirytable.column('days:name').index();
    pndx = inquirytable.column('productcode:name').index();    
    
    $('#inquirytable tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        } else {
            inquirytable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
            
    $('#add').on('click', function (event) {
        event.preventDefault();
        var numberofcustomer = parseInt($('#numberofcustomer').val());
        var visitorname = $('#visitorname').val();
        var address = $('#address').val();
        var mobileno = $('#mobileno').val();        
        var age = $('#age').val();
        var days = parseInt($('#days').val());
        var productcode = $('#product').val();
        var productname = $('#product option:selected').text();;
                
        if (isNaN(numberofcustomer)) numberofcustomer = 0;
        if (isNaN(days)) days = 0;
                
        if (numberofcustomer == 0 
            && visitorname == "" 
            && address == "" 
            && mobileno == "" 
            && age == "" 
            && days == ""
            && productcode == "") {
            alert("Atleast one field required to add");
            return;
        }
                        
        var table = inquirytable;
        
        var duplicate = false;
        
        var idx = table
            .columns( 'visitorname:name' )
            .data()
            .eq( 0 ) // Reduce the 2D array into a 1D array of data
            .indexOf( visitorname );
        
        if (idx != -1) {
            idx = table
                .columns( 'productcode:name' )
                .data()
                .eq( 0 ) // Reduce the 2D array into a 1D array of data
                .indexOf( productcode );
            if (idx != -1) {
                duplicate = true;
                $('<div></div>').appendTo('body')
                    .html('<div><h6>Inquiry already exists. Please check.</h6></div>')
                    .dialog({
                        modal: true, title: 'Duplicate', zIndex: 10000, autoOpen: true,
                        width: 'auto', resizable: false,                                        
                        close: function (event, ui) {
                            $(this).remove();
                        }
                });  
            }                                              
        }
                 
        // Draw once all updates are done
        table.draw();
            
        if (!duplicate) {
            inquirytable.row.add([
                productcode,
                numberofcustomer,
                visitorname,
                address,
                mobileno,        
                age,
                days,                
                productname,
                '<input type="button" value="X" class="close"/>'
            ]).draw( false );             
        }
                                            
        clearAll();
    });
    
    $('.addedinquirylist').on('click', '.close', function () {
        $('#inquirytable tbody').click();
        inquirytable.row('.selected').remove().draw( false );
    });

           
});

function clearAll() {
    $('#numberofcustomer').val("");
    $('#visitorname').val("");
    $('#address').val("");
    $('#mobileno').val("");        
    $('#age').val("");
    $('#days').val("");
    $('#product').val("");    
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function SaveInquiry() {
    var inq = [];
                
    inquirytable.rows().eq(0).each( function ( index ) {
        var row = inquirytable.row( index );
        var data = row.data();     
        inq.push({
            numberofcustomer : data[windx],
            visitorname : data[vndx],
            address : data[adndx],
            mobileno : data[mondx],
            age : data[andx],
            days : data[andx],
            productcode : data[pndx],
        });                        
    });
            
    var data = {data : inq};
    
    var actionurl = $("#frminquiry").attr('action');    
    $.ajax({
        type: 'POST',
        url: actionurl,
        dataType: 'json',        
        data: data,        
        success: function( res, textStatus, jQxhr ){            
            if (res.success==1) {
                $('.alert-success').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> '+res.message ).show();
                $('.alert-success').delay(3000).fadeOut('slow');
                inquirytable.clear().draw();
            } else {
                $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+errorThrown ).show();
                $('.alert-danger').delay(3000).fadeOut('slow');
            }                        
        },
        error: function( jqXhr, textStatus, errorThrown ){
            $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+errorThrown ).show();
            $('.alert-danger').delay(3000).fadeOut('slow');
        }
    });
}

