var receivable = null;

$(document).ready(function () {    
    
    servicetable = $('#servicetable').DataTable({
        "processing": false,
        "serverSide": false,        
        "ajax": {
            "type"   : "POST",
            "url"    : baseurl+"service/myservice",
            "dataSrc": function(d){            
                 g_invdataFull = d.data;
                 var dataParent = [];
                 var found = false;             
                 $.each(d.data, function(i, data){
                    if (dataParent.length == 0) {                        
                        dataParent.push(data);
                    }
                    found = false;                                
                    $.each(dataParent, function(i, v) {                    
                        if (data.dsmasterid == v.dsmasterid) {
                            found = true;
                            return;
                        }                        
                    });
                    if (!found) {
                        dataParent.push(data);                        
                    }
                 });
                
                 return dataParent;
            }
        },        
        "columnDefs": [            
            { "targets": [ 0 ], "visible": false },            
            { "className": "dt-center", "targets": [ "_all" ] }
        ],         
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },                    
            {"data" : "dsmasterid"},
            {"data" : "servicedate",
                render: function ( data, type, row ) {
                    // If display or filter data is requested, format the date
                    if ( type === 'display' || type === 'filter' ) {                        
                        return (moment(data).format("DD/MM/YYYY"));                           
                    }
                    return data;
                }
            },                                
            {"data" : "chassisno"},   
            {"data" : "productname"},       //6  
            {"data" : "customername"},                
            {"data" : "servicetype"},
            {"data" : "serviceno"},
            {"data" : "action"}
        ],
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching" :false
    });
    $('#servicetable tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        } else {
            servicetable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    // Add event listener for opening and closing details
    $('#servicetable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = servicetable.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( srvformat(row.data()) ).show();
            tr.addClass('shown');
        }
    });
    
    /* Formatting function for row details - modify as you need */
    function srvformat ( d ) {
        var html = '<table cellpadding="5" cellspacing="0" border="0" style="padding:10px; width:100%">';
        html +='<tr style="border-bottom: 1px solid #ddd;">'+                                            
                    '<th>Model</th>'+                                                            
                    '<th style="text-align: center; width: 20%;">Chesses No.</th>'+
                    '<th style="text-align: center; width: 20%;">Engine No.</th>'+
                    '<th class="text-center" style="width: 10%;">Quantity</th>'+                
                    '<th class="text-center" style="width: 15%;">Total Price</th>'+
                '</tr>'          
        var dataChild = [];
        var hasChildren = false;
        $.each(g_invdataFull, function(){
           if(this.invoiceno === d.invoiceno){
              html += 
                '<tr style="border-bottom: 1px solid #ddd;">'+
                    '<td>' + this.productname + '</td>' +                 
                    '<td style="text-align: center">' + this.chessesno + '</td>' +                 
                    '<td style="text-align: center">' + this.engineno + '</td>' +                 
                    '<td class="text-right">' +  this.quantity + '</td>' +
                    '<td class="text-right">' +  this.unitprice +'</td>' +                                                  
                '</tr>';             
              hasChildren = true;
           }
        });
      
        if(!hasChildren){
            html += '<tr><td>There are no items in this view.</td></tr>';         
        }      
     
        html += '</table>';
        return html;
    } 
    $('.servicetablelist').on('click', '.print', function () {
        $('#biketable tbody').click();
        var data = servicetable.row('.selected').data();        
        $.ajax({
            type: 'POST',
            url: baseurl+'orders/receive',
            dataType: 'json',        
            data: {invoiceno : data.invoiceno},        
            success: function( data, textStatus, jQxhr ){
                var res = $.parseJSON( data );
                if (res.success==1) {
                    $('.alert-success').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> '+res.message).show();
                    $('.alert-success').delay(2000).fadeOut('slow');                    
                    servicetable.ajax.reload();
                    servicetable.draw();
                } else {
                    $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+res.message).show();
                    $('.alert-danger').delay(2000).fadeOut('slow');
                }                                            
            },
            error: function( jqXhr, textStatus, errorThrown ){
                console.log( errorThrown );
            }
        });                                           
    });
                       
    $('#servicetable tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        } else {
            orderdetails.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });                 
});

