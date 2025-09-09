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
    $("#chargeamt").empty();
    $("#unitprice").empty();
    $("#vat").empty();
    $("#total").empty();    
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
                document.getElementById("submitbutton").disabled = false;
                document.getElementById("search").disabled = true;       
            }
        });
    });
    
    $(".chassis").autocomplete({        
        source: function(request, response){
           $.ajax({
               type: "POST",
               url: base_url + "service/bikelist/1",
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
                    document.getElementById("submitbutton").disabled = false;
                    document.getElementById("search").disabled = true;                    
                }
            });            
        },
        minLength: 1
    }).bind('keypress', function () {
        $(this).autocomplete("search");
    });
    
    svrpartstable = $('#svrpartstable').DataTable( {
        "columnDefs": [
            { "targets": [ 0 , 1, 4 ], "visible": false },
            { "className": "text-right", "targets": [ 3, 4, 5, 6 ] },
            { "className": "dt-center", "targets": [ "_all" ] }
        ],
        "columns": [
            { "name": "sptype" },
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
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
             
            // Update footer
            $( api.column( 6 ).footer() ).html(total.toFixed(2)+'/-');
        }
    });
 
    spndx = svrpartstable.column('sptype:name').index();
    pndx = svrpartstable.column('productcode:name').index();
    pnndx = svrpartstable.column('productname:name').index();
    undx = svrpartstable.column('unitprice:name').index();
    vndx = svrpartstable.column('vat:name').index();
    qndx = svrpartstable.column('qty:name').index();
    tndx = svrpartstable.column('total:name').index();
    
    $('#svrpartstable tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        } else {
            svrpartstable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
        $(".services").autocomplete({        
        source: function(request, response){
           $.ajax({
               type: "POST",
               url: base_url + "service/servicelist",
               data: {search : request.term},               
               dataType: "json",
               cache: false,
               success: function (res) {
                    var transformed = $.map(res.services, function (el) {
                        return {
                            label: el.servicename,
                            value: el.serviceid
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
            $("#serviceid").val(ui.item.value);
            $.ajax ({
                type: "POST",
                url:  base_url + "service/servicecharge",
                dataType: "json",
                data: {servicetype: 2, packsize: packsize, serviceid: ui.item.value},
                cache: false,
                success: function (res){
                    var data = res.data[0];
                    $("#chargeamt").html(data.chargeamt);
                }
            });
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
    });
    
    $('#addservice').on('click', function (event) {
        event.preventDefault();
        var serviceid = $('#serviceid').val();
        var services = $('#services').val();
                
        var chargeamt = parseFloat($("#chargeamt").html());
        if (isNaN(chargeamt)) chargeamt = 0;
                
        if (serviceid == "") {
            alert("Please select a serice.");
            return;
        }
                
        var table = svrpartstable;
        
        var duplicate = false;
        
        var idx = table
            .columns( 'productcode:name' )
            .data()
            .eq( 0 ) // Reduce the 2D array into a 1D array of data
            .indexOf( serviceid );
        
        if (idx != -1) {
            duplicate = true;
            $('<div></div>').appendTo('body')
                .html('<div><h6>Service already exists. Add with existing?</h6></div>')
                .dialog({
                    modal: true, title: 'Confirm', zIndex: 10000, autoOpen: true,
                    width: 'auto', resizable: false,
                    buttons: {
                        Yes: function () {
                            $( this ).dialog( "close" );
                            var row = table.row(idx).node();
                            var udata = table.cell(row, undx).data();
                            var vdata = table.cell(row, vndx).data();
                            var qdata = table.cell(row, qndx).data();
                            table.cell(row, qndx).data(parseFloat(qdata)+1);                         
                            table.cell(row, tndx).data((parseFloat(qdata)+1) * (parseFloat(udata)+parseFloat(vdata))+"/-" );
                            table.draw();
                        },
                        No: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
            });                                         
        }
                 
        // Draw once all updates are done
        table.draw();
            
        if (!duplicate) {
            svrpartstable.row.add([
                'service', serviceid, services, chargeamt, 0, 1, chargeamt,
                '<input type="button" value="X" class="close spclose"/>'
            ]).draw( false );             
        }
                                    
        //calculateTotal();
        clearAll();
    });
    
    
    $('#add').on('click', function (event) {
        event.preventDefault();
        var productcode = $('#productcode').val();
        var spareparts = $('#spareparts').val();
        
        var spqty = parseInt($("#qty").val());
        if (isNaN(spqty)) spqty = 0;
        
        var unitprice = parseFloat($("#unitprice").html());
        if (isNaN(unitprice)) unitprice = 0;
        
        var vat = parseFloat($("#vat").html());
        if (isNaN(vat)) vat = 0;
        
        var totalprice = parseFloat($("#total").html());
        if (isNaN(totalprice)) totalprice = 0;
                
        if (productcode == "") {
            alert("Please select a spare parts");
            return;
        }
        
        if (spqty == 0) {
            alert("Please select a quantity");
            return;
        }
        
        if (unitprice == 0) {
            alert("Unit price can not be empty or 0.");
            return;
        }
        
        var table = svrpartstable;
        
        var duplicate = false;
        
        var idx = table
            .columns( 'productcode:name' )
            .data()
            .eq( 0 ) // Reduce the 2D array into a 1D array of data
            .indexOf( productcode );
        
        if (idx != -1) {
            duplicate = true;
            $('<div></div>').appendTo('body')
                .html('<div><h6>Service already exists. Add with existing?</h6></div>')
                .dialog({
                    modal: true, title: 'Confirm', zIndex: 10000, autoOpen: true,
                    width: 'auto', resizable: false,
                    buttons: {
                        Yes: function () {
                            $( this ).dialog( "close" );
                            var row = table.row(idx).node();
                            var udata = table.cell(row, undx).data();
                            var vdata = table.cell(row, vndx).data();
                            var qdata = table.cell(row, qndx).data();
                            table.cell(row, qndx).data(parseFloat(qdata)+parseFloat(spqty));                         
                            table.cell(row, tndx).data((parseFloat(qdata)+parseFloat(spqty)) * (parseFloat(udata))+"/-" );
                            table.draw();
                        },
                        No: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
            });                                                
        }
                 
        // Draw once all updates are done
        table.draw();
            
        if (!duplicate) {
            svrpartstable.row.add([
                'spare',                
                productcode,
                spareparts,                
                unitprice,
                vat,
                spqty,
                totalprice,
                '<input type="button" value="X" class="close spclose"/>'
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
    $('.addedsvrsplist').on('click', '.spclose', function () {
        $('#svrpartstable tbody').click();
        svrpartstable.row('.selected').remove().draw( false );
    });

    $(".spareparts").autocomplete({        
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
            $("#unitprice").html(ui.item.mrp);
            $("#vat").html(ui.item.vat);
            $("#qty").val(0);
            $("#productcode").val(ui.item.value);
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
    });         
});

function calculateTotal() {
    var totalprice = 0;

    var unitprice = parseFloat($("#unitprice").html());
    var vat = parseFloat($("#vat").html());
    if (isNaN(unitprice)) unitprice = 0;
    if (isNaN(vat)) vat = 0;
    var quantity = parseFloat($("#qty").val());
    if (isNaN(quantity)) quantity = 0;
    totalprice += Math.round (((unitprice) * quantity)*100) / 100;        

    $("#total").html(totalprice + "/-");                
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

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function SaveRepairService() {
    var servicetype         = $("#servicetype").val();
    var chassisno           = $('#search').val();
    var freesscheduleid     = 0;
    var schecked            = [];
    var servicename         = [];
    var chargeamt           = [];
    var schange             = [];
    var serviceid           = [];
    var productcode         = [];
    var productname         = [];
    var unitprice           = [];
    var qnty                = [];
    var jobcardno           = $("#jobcardno").val();
    var customerentrytime   = $("#customerentrytime").val();
    var servicestarttime    = $("#servicestarttime").val();
    var serviceendttime     = $("#serviceendttime").val();
    
    var technicianname      = $("#technicianname").val();
    var mileage             = $("#mileage").val();
    var problemdetails      = $("#problemdetails").val();
    var failureanalysis     = $("#failureanalysis").val();
    var remedyresult        = $("#remedyresult").val();
    
        
    svrpartstable.rows().eq(0).each( function ( index ) {
        var row = svrpartstable.row( index );
     
        var data = row.data();
        if (data[spndx] == 'service') {
            serviceid.push(data[pndx]);
            servicename.push(data[pnndx]);
            chargeamt.push(data[undx]);            
        } else {
            productcode.push(data[pndx]);
            productname.push(data[pnndx]);
            unitprice.push(data[undx]);
            qnty.push(data[qndx]);
        }                
    });
            
    var data = {
        servicetype : servicetype,
        chassisno : chassisno, 
        freesscheduleid : 0,
        checked : schecked,
        servicename : servicename,
        changed : schange,
        serviceid : serviceid,
        servicecharge : chargeamt,
        productcode : productcode,
        productname : productname,
        unitprice : unitprice,
        qnty : qnty,
        jobcardno : jobcardno,
        customerentrytime : customerentrytime,
        servicestarttime : servicestarttime,
        serviceendttime : serviceendttime,
        technicianname : technicianname,
        mileage : mileage,
        problemdetails : problemdetails,
        failureanalysis : failureanalysis,
        remedyresult : remedyresult
    };
    
    var actionurl = $("#frmrepair").attr('action');    
    console.log(data);
    $.ajax({
        type: 'POST',
        url: actionurl,
        dataType: 'json',        
        data: data,        
        success: function( res, textStatus, jQxhr ){        
            console.log(res); 
            
            if (res.success==1) {                
                if (res.redirect !== '') window.open(res.redirect, '_blank', 'width=650,height=600,location=no,left=200px');                
                $('#frmrepair')[0].reset();
                $("#getresult").html('');                                 
                svrpartstable.clear().draw();
            } else {
                $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+res.message ).show();
                $('.alert-danger').delay(3000).fadeOut('slow');
            }
                        
        },
        error: function( jqXhr, textStatus, errorThrown ){
            console.log(jqXhr, textStatus, errorThrown);
            $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+errorThrown ).show();
            $('.alert-danger').delay(3000).fadeOut('slow');
        }
    });
}

