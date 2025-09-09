var numArray = [];
var orderdetails = null;
var receivable = null;
var salestable = null;
var servicetable = null;
var warrantytable = null;
var g_dataFull = [];
var g_invdataFull = [];
var g_salesdataFull = [];
var g_srvdataFull = [];
var g_wcdataFull = [];

function doLoadCustomerBalance(customercode){
    $.ajax({
        url: baseurl + "home/customerbalance",
        type: "post",
        data: "customercode=" + customercode,
        dataType: "json",
        beforeSend: function(){
            $("#customerduetable tbody").empty();
        },               
        success: function (response) {
           //console.log(response);
            if(response.length > 0){
                for(i = 0; i < response.length; i++){
                    amount = 0 - parseInt(response[i]['DueAmount']);
                    if(isNaN(amount)){
                        amount = 0;
                    }                        
                    $("#customerduetable tbody").append("<tr><td>"+response[i]['DueType']+"</td><td>"+amount+"</td></tr>");
                }
            }               
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        } 
    }); 
}

$(document).ready(function () {
    $('.nav-tabs a').click(function () {
        $(this).tab('show');
    });
   receiveData();
    $("#unitprice").empty();
    $("#vat").empty();
    $("#total").empty();

    function receiveData(){
        receivable = $('#receivabletable').DataTable({
            "processing": false,
            "serverSide": false,
            "ajax": {
                "type": "POST",
                "url": baseurl + "orders/myreceivable",
                "dataSrc": function (d) {
                    g_invdataFull = d.data;
                    console.log(invoice_receive_survey_config)
                    var dataParent = [];
                    var found = false;
                    $.each(d.data, function (i, data) {
                        if (dataParent.length == 0) {
                            dataParent.push(data);
                        }
                        found = false;
                        $.each(dataParent, function (i, v) {
                            if (data.invoiceno == v.invoiceno) {
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
                {"targets": [7, 8, 9, 10, 11, 12], "visible": false},
                {"className": "", "targets": [5, 6]},
                {"className": "dt-center", "targets": ["_all"]},
                {"className": "dt-center", "targets": ["_all"]},
            ],
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {"data": "invoiceno"},
                {"data": "invoicedate",
                    render: function (data, type, row) {
                        // If display or filter data is requested, format the date
                        if (type === 'display' || type === 'filter') {
                            return (moment(data).format("DD/MM/YYYY"));
                        }
                        return data;
                    }
                },
                {"data": "deliverydate",
                    render: function (data, type, row) {
                        // If display or filter data is requested, format the date
                        if (type === 'display' || type === 'filter') {
                            return (moment(data).format("DD/MM/YYYY"));
                        }
                        return data;
                    }
                },
                {"data": "orderdate",
                    render: function (data, type, row) {
                        // If display or filter data is requested, format the date
                        if (type === 'display' || type === 'filter') {
                            return (moment(data).format("DD/MM/YYYY"));
                        }
                        return data;
                    }
                },
                {"data": "discount"},
                {"data": "net"},
                {"data": "productcode"},
                {"data": "productname"},
                {"data": "chassisno"},
                {"data": "engineno"},
                {"data": "quantity"},
                {"data": "unitprice"},
                {"data": "action"},
                // {"data": "survey"}
                {"data": "survey",        render: function (data, type, row) {
                        // If display or filter data is requested, format the date
                        if (!invoice_receive_survey_config) {
                            return null;
                        }
                        else{
                            return data;
                        }

                    }}
            ],
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false,
            "bDestroy": true
        });
    }


    $('#receivabletable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            receivable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    // Add event listener for opening and closing details
    $('#receivabletable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = receivable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(recformat(row.data())).show();
            tr.addClass('shown');
        }
    });

    /* Formatting function for row details - modify as you need */
    function recformat(d) {
        var html = '<table cellpadding="5" cellspacing="0" border="0" style="padding:10px; width:100%">';
        html += '<tr style="border-bottom: 1px solid #ddd;">' +
                '<th>Model</th>' +
                '<th style="text-align: center; width: 20%;">Chassis No.</th>' +
                '<th style="text-align: center; width: 20%;">Engine No.</th>' +
                '<th class="text-center" style="width: 10%;">Quantity</th>' +
                '<th class="text-center" style="width: 15%;">Unit Price</th>' +
                '</tr>'
        var dataChild = [];
        var hasChildren = false;
        $.each(g_invdataFull, function () {
            if (this.invoiceno === d.invoiceno) {
                html +=
                        '<tr style="border-bottom: 1px solid #ddd;">' +
                        '<td>' + this.productname + '</td>' +
                        '<td style="text-align: center">' + this.chassisno + '</td>' +
                        '<td style="text-align: center">' + this.engineno + '</td>' +
                        '<td class="text-right">' + this.quantity + '</td>' +
                        '<td class="text-right">' + this.unitprice + '</td>' +
                        '</tr>';
                hasChildren = true;
            }
        });

        if (!hasChildren) {
            html += '<tr><td>There are no items in this view.</td></tr>';
        }

        html += '</table>';
        return html;
    }
    $('.receivablelist').on('click', '.receiveinvoice', function () {
        var invno = $(this).attr('rel');

        var message = "Are you sure to receive? You can not change later."
        $('<div></div>').appendTo('body')
                .html('<div><h6>' + message + '</h6></div>')
                .dialog({
                    modal: true, title: 'Confirm Receive', zIndex: 10000, autoOpen: true,
                    width: 'auto', resizable: false,
                    buttons: {
                        Yes: function () {
                            $.ajax({
                                type: 'POST',
                                url: baseurl + 'orders/receive',
                                dataType: 'json',
                                data: {invoiceno: invno},
                                success: function (data, textStatus, jQxhr) {
                                    var res = $.parseJSON(data);
                                    if (res.success == 1) {
                                        $('.alert-success').html('<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> ' + res.message).show();
                                        $('.alert-success').delay(2000).fadeOut('slow');
                                        receivable.ajax.reload();
                                        receivable.draw();
                                    } else {
                                        $('.alert-danger').html('<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> ' + res.message).show();
                                        $('.alert-danger').delay(2000).fadeOut('slow');
                                    }
                                },
                                error: function (jqXhr, textStatus, errorThrown) {
                                    console.log(errorThrown);
                                }
                            });
                        },
                        No: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
                });
    });

    $('.receivablelist').on('click', '.receiveinvoicesurvey', function () {
        var invno = $(this).attr('rel');
        $.ajax({
            type: 'POST',
            url: baseurl + 'orders/receiveSurveyData',
            data: {invoiceno: invno},
            success: function (data) {
                // console.log(data)
                $('<div id="survey-main-modal"></div>').appendTo('body')
                    .html('<div>' + data + '</div>')
                    .dialog({
                        modal: true, title: 'Invoice Receive Survey Form', zIndex:99999, autoOpen: true,
                        width: 'auto', resizable: false,
                        buttons: {
                            // Yes: function () {
                            //     console.log(data)
                            // },
                            No: function () {
                                $(this).dialog("close");
                            }
                        },
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });

                // var res = $.parseJSON(data);
                // if (res.success == 1) {
                //     $('.alert-success').html('<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> ' + res.message).show();
                //     $('.alert-success').delay(2000).fadeOut('slow');
                //     receivable.ajax.reload();
                //     receivable.draw();
                // } else {
                //     $('.alert-danger').html('<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> ' + res.message).show();
                //     $('.alert-danger').delay(2000).fadeOut('slow');
                // }
            },
            error: function (jqXhr, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    });


    $(document).on('submit','#question-answer',function (e) {
        e.preventDefault();
        var invno = $(this).attr('rel');
        let surveyForm =  $("#question-answer").serialize();
        $.ajax({
                url: base_url + 'orders/addSurveyData',
                type:'Post',
                dataType: 'json',
                data: surveyForm,
                success: function (data, textStatus, jQxhr){
                    var res = $.parseJSON(data);
                    if(res.success ==1){
                        $('.ui-dialog-content').dialog('close');
                        // window.location.href = baseurl + 'orders/receive';
                        receiveData();
                        // $("#receivabletable").html();
                        $('.alert-success').html('<a href="#" class="close" style="z-index: 10000" data-dismiss="alert">&times;</a><strong>Success!</strong> ' + res.message).show();
                        $('.alert-success').delay(2000).fadeOut('slow');

                    }
                    else{
                        $('.ui-dialog-content').dialog('close');
                        $('.alert-danger').html('<a href="#" style="z-index: 10000" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> ' + res.message).show();
                        $('.alert-danger').delay(2000).fadeOut('slow');
                    }
                },
                error: function (jqXhr, textStatus, errorThrown) {
                console.log(errorThrown);
            }
            }

        );
    });

    /**
     * My Orders    
     */
    orderdetails = $('#biketable').DataTable({
        "processing": false,
        "serverSide": false,
        "ajax": {
            "type": "POST",
            "url": baseurl + "orders/myorders",
            "dataSrc": function (d) {
                g_dataFull = d.data;
                var dataParent = [];
                var found = false;
                $.each(d.data, function (i, data) {
                    if (dataParent.length == 0) {
                        dataParent.push(data);
                    }
                    found = false;
                    $.each(dataParent, function (i, v) {
                        if (data.invoiceno == v.invoiceno) {
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
            {"targets": [1, 6, 7, 8, 9, 10], "visible": false},
            {"className": "text-center", "targets": ["_all"]}
        ],
        "columns": [
            {
                "className": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": ''
            },
            {"data": "productcode"},
            {"data": "orderno"},
            {"data": "orderdate",
                render: function (data, type, row) {
                    // If display or filter data is requested, format the date
                    if (type === 'display' || type === 'filter') {
                        return (moment(data).format("DD/MM/YYYY"));
                    }
                    return data;
                }
            },
            {"data": "ordertime",
                render: function (data, type, row) {
                    // If display or filter data is requested, format the date
                    if (type === 'display' || type === 'filter') {
                        return (moment(data).format("HH:mm:ss"));
                    }
                    return data;
                }
            },
            {"data": "gtotal"},
            {"data": "productname"},
            {"data": "unitprice"},
            {"data": "vat"},
            {"data": "quantity"},
            {"data": "totalprice"},
            {"data": "action"}
        ],
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false
    });

    $('#biketable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            orderdetails.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    // Add event listener for opening and closing details
    $('#biketable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = orderdetails.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(bikeformat(row.data())).show();
            tr.addClass('shown');
        }
    });

    $('.addedbikelist').on('click', '.deleteorder', function () {
        var message = "Are you sure to delete? You can not change later."
        $('<div></div>').appendTo('body')
                .html('<div><h6>' + message + '</h6></div>')
                .dialog({
                    modal: true, title: 'Confirm', zIndex: 10000, autoOpen: true,
                    width: 'auto', resizable: false,
                    buttons: {
                        Yes: function () {
                            $('#biketable tbody').click();
                            var data = orderdetails.row('.selected').data();
                            $.ajax({
                                type: 'POST',
                                url: baseurl + 'orders/delete',
                                dataType: 'json',
                                data: {orderno: data.orderno},
                                success: function (data, textStatus, jQxhr) {
                                    var res = $.parseJSON(data);
                                    if (res.success == 1) {
                                        $('.alert-success').html('<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> ' + res.message).show();
                                        $('.alert-success').delay(2000).fadeOut('slow');
                                        orderdetails.ajax.reload();
                                        orderdetails.draw();
                                    } else {
                                        $('.alert-danger').html('<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> ' + res.message).show();
                                        $('.alert-danger').delay(2000).fadeOut('slow');
                                    }

                                },
                                error: function (jqXhr, textStatus, errorThrown) {
                                    console.log(errorThrown);
                                }
                            });
                            $(this).dialog("close");
                        },
                        No: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
                });

    });

    /* Formatting function for row details - modify as you need */
    function bikeformat(d) {
        var html = '<table cellpadding="5" cellspacing="0" border="0" style="margin-left:60px; width:90%">';
        html += '<tr style="border-bottom: 1px solid #ddd;">' +
                '<th>Model</th>' +
                '<th class="text-center" style="width: 15%;">Unit Price</th>' +
                '<th class="text-center" style="width: 15%;">VAT</th>' +
                '<th class="text-center" style="width: 15%;">Quantity</th>' +
                '<th class="text-center" style="width: 25%;">Total Price</th>' +
                '</tr>'
        var dataChild = [];
        var hasChildren = false;
        $.each(g_dataFull, function () {
            if (this.orderno === d.orderno) {
                html +=
                        '<tr style="border-bottom: 1px solid #ddd;">' +
                        '<td>' + this.productname + '</td>' +
                        '<td class="text-right">' + this.unitprice + '</td>' +
                        '<td class="text-right">' + this.vat + '</td>' +
                        '<td class="text-right">' + this.quantity + '</td>' +
                        '<td class="text-right">' + this.totalprice + '</td>' +
                        '</tr>';
                hasChildren = true;
            }
        });

        if (!hasChildren) {
            html += '<tr><td>There are no items in this view.</td></tr>';
        }

        html += '</table>';
        return html;
    }

    /**
     * Invoice    
     */
    salestable = $('#salestable').DataTable({
        "processing": false,
        "serverSide": false,
        "ajax": {
            "type": "POST",
            "url": baseurl + "invoice/mysales",
            "dataSrc": function (d) {
                g_salesdataFull = d.data;
                var dataParent = [];
                var found = false;
                $.each(d.data, function (i, data) {
                    if (dataParent.length == 0) {
                        dataParent.push(data);
                    }
                    found = false;
                    $.each(dataParent, function (i, v) {
                        if (data.invoiceid == v.invoiceid) {
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
            {"targets": [0], "visible": false},
            {"className": "dt-left", "targets": ["_all"]}
        ],
        "columns": [
            {"data": "invoiceid"},
            {"data": "invoicedate",
                render: function (data, type, row) {
                    // If display or filter data is requested, format the date
                    if (type === 'display' || type === 'filter') {
                        return (moment(data).format("DD/MM/YYYY"));
                    }
                    return data;
                }
            },
            {"data": "invoiceno"},
            {"data": "customername"},
            {"data": "mobileno"},
            {"data": "productname"},
            {"data": "chassisno"},
            {"data": "engineno"},
            {"data": "color"},
            {"data": "action"}
        ],
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false
    });
    $('#salestable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            salestable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    // Add event listener for opening and closing details
    $('#salestable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = salestable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(salesformat(row.data())).show();
            tr.addClass('shown');
        }
    });

    /* Formatting function for row details - modify as you need */
    function salesformat(d) {
        var html = '<table cellpadding="5" cellspacing="0" border="0" style="padding:10px; width:100%">';
        html += '<tr style="border-bottom: 1px solid #ddd;">' +
                '<th style="width: 75%;">Checked</th>' +
                '<th>Changed</th>' +
                '</tr>'
        var dataChild = [];
        var hasChildren = true;
        html +=
                '<tr style="border-bottom: 1px solid #ddd;">' +
                '<td>' + d.checkedservice + '</td>' +
                '<td>' + d.changedparts + '</td>' +
                '</tr>';

        if (!hasChildren) {
            html += '<tr><td>There are no items in this view.</td></tr>';
        }

        html += '</table>';
        return html;
    }

    $('#salestable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            salestable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    $("#preview").dialog({
        autoOpen: false,
        modal: true,
        title: "Details",
        resizable: true,
        width: 500,
        height: 450,
        buttons: {
            Close: function () {
                $(this).dialog('close');
            }
        }
    });

    $('.invoicelist').on('click', '.invoiceprint', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        window.open(url, '_blank', 'width=850,height=700,location=no,left=75px');
    });


    /**
     * My Service    
     */
    page = 1;
    servicetable = $('#servicetable').DataTable({
        "processing": false,
        "serverSide": false,
        "ajax": {
            "type": "POST",
            "url": baseurl + "service/myservice/" + page,
            "dataSrc": function (d) {
                g_srvdataFull = d.data;
                var servicepageinglist = d.paging;
                var dataParent = [];
                var found = false;
                $.each(d.data, function (i, data) {
                    if (dataParent.length == 0) {
                        dataParent.push(data);
                    }
                    found = false;
                    $.each(dataParent, function (i, v) {
                        if (data.dsmasterid == v.dsmasterid) {
                            found = true;
                            return;
                        }
                    });
                    dataParent.push(data);
                });

                previous = page - 1;
                next = page + 1;

                $('#pagination').empty();
                if (servicepageinglist.length != 0 && page != 1) {
                    $('#pagination').append('<li><a onclick="chanageservicetable(' + previous + ')" href="#" id="PageNo">Previous</a></li>');
                }

                get_pagination_links(1, servicepageinglist.length, url = '');
                /*		
                 for(i = 0; i < servicepageinglist.length; i++){
                 if(page == servicepageinglist[i]['PageNo']){ classcontent = ' class="active"'; }else{ classcontent = ''; }	
                 $('#pagination').append('<li'+classcontent+'><a href="#" onclick="chanageservicetable(' + servicepageinglist[i]['PageNo'] + ')" id="PageNo">' + servicepageinglist[i]['PageNo'] + '</a></li>');    
                 }*/
                if (servicepageinglist.length != 0 && page != servicepageinglist.length) {
                    $('#pagination').append('<li><a onclick="chanageservicetable(' + next + ')" href="#" id="PageNo">Next</a></li>');
                }
                dataParent.shift();
                return dataParent;
            }
        },
        "columnDefs": [
            {"targets": [1, 8, 9], "visible": false},
            {"className": "dt-left", "targets": ["_all"]}
        ],
        "columns": [
            {
                "className": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": ''
            },
            {"data": "dsmasterid"},
            {"data": "servicedate",
                render: function (data, type, row) {
                    // If display or filter data is requested, format the date
                    if (type === 'display' || type === 'filter') {
                        return (moment(data).format("DD/MM/YYYY"));
                    }
                    return data;
                }
            },
            {"data": "chassisno"},
            {"data": "productname"},
            {"data": "engineno"},
            {"data": "customername"},
            {"data": "servicetype"},
            {"data": "serviceno"},
            {"data": "checkedservice"},
            {"data": "changedparts"},
            {"data": "action"}
        ],
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false
    });

    /**
     * My Service    
     */


    $('#servicetable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            servicetable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    // Add event listener for opening and closing details
    $('#servicetable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = servicetable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(srvformat(row.data())).show();
            tr.addClass('shown');
        }
    });

    /* Formatting function for row details - modify as you need */
    function srvformat(d) {
        var html = '<table cellpadding="5" cellspacing="0" border="0" style="padding:10px; width:100%">';
        html += '<tr style="border-bottom: 1px solid #ddd;">' +
                '<th style="width: 75%;">Checked</th>' +
                '<th>Changed</th>' +
                '</tr>'
        var dataChild = [];
        var hasChildren = true;
        html +=
                '<tr style="border-bottom: 1px solid #ddd;">' +
                '<td>' + d.checkedservice + '</td>' +
                '<td>' + d.changedparts + '</td>' +
                '</tr>';

        if (!hasChildren) {
            html += '<tr><td>There are no items in this view.</td></tr>';
        }

        html += '</table>';
        return html;
    }




    /*
     $('.servicelist').on('click', '.serviceprint', function (e) {
     e.preventDefault();
     $('#servicetable tbody').click();
     var tr = $(this).closest('tr');
     var row = servicetable.row( tr );
     
     var data = servicetable.row('.selected').data();
     data = row.data();         
     $.ajax({
     type: 'POST',
     url: baseurl+'service/preview',
     dataType: 'json',        
     data: {dsmasterid : data.dsmasterid},        
     success: function( data, Status, jQxhr ){                
     $(".modal-body").html(data);
     $("#preview").dialog("open");                                           
     },
     error: function( jqXhr, textStatus, errorThrown ){
     console.log( errorThrown );
     }
     });                                           
     });
     */
    $('#servicetable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            servicetable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    /**
     * Warranty Claim              
     */
    warrantytable = $('#warrantytable').DataTable({
        "processing": false,
        "serverSide": false,
        "ajax": {
            "type": "POST",
            "url": baseurl + "service/mywarrantyclaim",
            "dataSrc": function (d) {
                g_wcdataFull = d.data;
                var dataParent = [];
                var found = false;
                $.each(d.data, function (i, data) {
                    if (dataParent.length == 0) {
                        dataParent.push(data);
                    }
                    found = false;
                    $.each(dataParent, function (i, v) {
                        if (data.dcwarrantyid == v.dcwarrantyid) {
                            found = true;
                            return;
                        }
                    });
                    dataParent.push(data);
                });

                return dataParent;
            }
        },
        "columnDefs": [
            {"targets": [1, 8, 9, 10, 11], "visible": false},
            {"className": "text-left", "targets": ["_all"]}
        ],
        "columns": [
            {
                "className": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": ''
            },
            {"data": "dcwarrantyid"},
            {"data": "mastercode"},
            {"data": "wcdate",
                render: function (data, type, row) {
                    // If display or filter data is requested, format the date
                    if (type === 'display' || type === 'filter') {
                        return (moment(data).format("DD/MM/YYYY"));
                    }
                    return data;
                }
            },
            {"data": "customername"},
            {"data": "mobileno"},
            {"data": "model"},
            {"data": "chassisno"},
            {"data": "engineno"},
            {"data": "productcode"},
            {"data": "productname"},
            {"data": "proimagepath"},
            {"data": "action"}
        ],
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false
    });
    $('#warrantytable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            receivable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    // Add event listener for opening and closing details
    $('#warrantytable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = warrantytable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(wcformat(row.data())).show();
            tr.addClass('shown');
        }
    });

    /* Formatting function for row details - modify as you need */
    function wcformat(d) {
        var html = '<table cellpadding="5" cellspacing="0" border="0" style="padding:10px; width:100%">';
        html += '<tr style="border-bottom: 1px solid #ddd;">'
                + '<th>Spare parts</th>'
                + '</tr>';
        var dataChild = [];
        var hasChildren = false;
        var procode = '';
        var prohtml = '';
        var imghtml = '';
        $.each(g_wcdataFull, function () {
            if (this.dcwarrantyid === d.dcwarrantyid) {
                if (procode != d.productcode) {
                    procode = d.productcode;
                    prohtml = '<td>' + d.productname + '</td>';
                }
                if (d.proimagepath != '') {
                    imghtml += '<div class="col-md-3 col-sm-4 col-xs-6">'
                            + '<img class="img-responsive" src="' + baseurl + 'upload/warrantyclaim/' + d.proimagepath + '">'
                            + '</div>';
                }
                hasChildren = true;
            }
        });
        html += '<tr>' + prohtml + '</tr><tr><td>' + imghtml + '</td>' + '</tr>';
        if (!hasChildren) {
            html += '<tr><td>There are no items in this view.</td></tr>';
        }

        html += '</table>';
        return html;
    }
    $('.warrantyclaimlist').on('click', '.warranty', function () {
        var message = "Are you sure to approve? You can not change later."
        $('<div></div>').appendTo('body')
                .html('<div><h6>' + message + '</h6></div>')
                .dialog({
                    modal: true, title: 'Confirm', zIndex: 10000, autoOpen: true,
                    width: 'auto', resizable: false,
                    buttons: {
                        Yes: function () {
                            $('#biketable tbody').click();
                            var data = warrantytable.row('.selected').data();
                            $.ajax({
                                type: 'POST',
                                url: baseurl + 'service/warrantyapprove',
                                dataType: 'json',
                                data: {dcwarrantyid: data.dcwarrantyid},
                                success: function (data, textStatus, jQxhr) {
                                    var res = $.parseJSON(data);
                                    if (res.success == 1) {
                                        $('.alert-success').html('<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> ' + res.message).show();
                                        $('.alert-success').delay(2000).fadeOut('slow');
                                        receivable.ajax.reload();
                                        receivable.draw();
                                    } else {
                                        $('.alert-danger').html('<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> ' + res.message).show();
                                        $('.alert-danger').delay(2000).fadeOut('slow');
                                    }
                                },
                                error: function (jqXhr, textStatus, errorThrown) {
                                    console.log(errorThrown);
                                }
                            });

                            $(this).dialog("close");
                        },
                        No: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
                });
    });
});

function preview(url) {
    window.open(url, '_blank', 'width=650,height=600,location=no,left=200px');
}

function serviceajax(pagenumber) {
    servicetable = $('#servicetable').DataTable({
        "processing": false,
        "serverSide": false,
        "ajax": {
            "type": "POST",
            "url": baseurl + "service/myservice/" + pagenumber,
            "dataSrc": function (d) {
                g_srvdataFull = d.data;
                var servicepageinglist = d.paging;
                var dataParent = [];
                var found = false;
                $.each(d.data, function (i, data) {
                    if (dataParent.length == 0) {
                        dataParent.push(data);
                    }
                    found = false;
                    $.each(dataParent, function (i, v) {
                        if (data.dsmasterid == v.dsmasterid) {
                            found = true;
                            return;
                        }
                    });
                    dataParent.push(data);
                });

                previous = pagenumber - 1;
                next = pagenumber + 1;

                $('#pagination').empty();
                if (servicepageinglist.length != 0 && pagenumber != 1) {
                    $('#pagination').append('<li><a onclick="chanageservicetable(' + previous + ')" href="#" id="PageNo">Previous</a></li>');
                }
                get_pagination_links(pagenumber, servicepageinglist.length, url = '');

                if (servicepageinglist.length != 0 && pagenumber != servicepageinglist.length) {
                    $('#pagination').append('<li><a onclick="chanageservicetable(' + next + ')" href="#" id="PageNo">Next</a></li>');
                }

                return dataParent;
            }
        },
        "columnDefs": [
            {"targets": [1, 8, 9], "visible": false},
            {"className": "dt-left", "targets": ["_all"]}
        ],
        "columns": [
            {
                "className": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": ''
            },
            {"data": "dsmasterid"},
            {"data": "servicedate",
                render: function (data, type, row) {
                    // If display or filter data is requested, format the date
                    if (type === 'display' || type === 'filter') {
                        return (moment(data).format("DD/MM/YYYY"));
                    }
                    return data;
                }
            },
            {"data": "chassisno"},
            {"data": "productname"},
            {"data": "engineno"},
            {"data": "customername"},
            {"data": "servicetype"},
            {"data": "serviceno"},
            {"data": "checkedservice"},
            {"data": "changedparts"},
            {"data": "action"}
        ],
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false
    });
}
function chanageservicetable(pagenumber) {
    servicetable.destroy();
    serviceajax(pagenumber)
}

function get_pagination_links(currentpage, totalpages, url)
{
    if (totalpages >= 1 && currentpage <= totalpages) {
        if (currentpage == 1) {
            classcontent = ' class="active"';
        } else {
            classcontent = '';
        }
        $('#pagination').append('<li' + classcontent + '><a onclick="chanageservicetable(' + 1 + ')" href="#" id="PageNo">1</a></li>');

        i = Math.max(2, currentpage - 5);
        if (i > 2) {
            $('#pagination').append('<li><a>...</a></li>');
        }
        for (i; i < Math.min(currentpage + 6, totalpages); i++) {
            if (currentpage == i) {
                classcontent = ' class="active"';
            } else {
                classcontent = '';
            }
            $('#pagination').append('<li' + classcontent + '><a onclick="chanageservicetable(' + i + ')" href="#" id="PageNo">' + i + '</a></li>');
        }
        if (i != totalpages) {
            $('#pagination').append('<li><a>...</a></li>');
            $('#pagination').append('<li><a onclick="chanageservicetable(' + totalpages + ')" href="#" id="PageNo">' + totalpages + '</a></li>');
        }
    }
}


function doReceive(chassisno, action) {
    var receivedate = $("#receivedate" + chassisno).val();
    if(chassisno){
        $.ajax({
            url: baseurl + "logistics/changedocumentstatus",
            type: "post",
            data: "chassisno=" + chassisno + "&action=" + action + "&receivedate=" + receivedate,
            dataType: "json",
            beforeSend: function(){

            },               
            success: function (response) {
                if(response == true){
                    $("#chassis_" + chassisno).empty();
                    $("#chassis_" + chassisno).html("<td colspan='7'>Successfully update</td>")
                }else{
                    alert("Something wrong.")
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            } 
        });
    }
}

function toggle(source) {
  checkboxes = document.getElementsByName("chassisno[]");
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}

function validatesubmit(){
    var elements = document.getElementsByName('chassisno[]');
    for(var i=0; i< elements.length; i++){
        if(elements[i].checked == true){
            return true;
        }
    }
    alert('Please select at least one item.')
    return false;
}
