$(document).ready(function () {
    $('#reporttable').hide();
    $("#ReportOrder").submit(function (event) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ProductCode = $("#ProductCode").val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype;
        //alert(base_url);

        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype + "&page=" + page;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype;
        $("#string").val(savestring);

        loadajaxfunction(string, page, searchstring = '');

        return false;
    });

    $("#spareparts_affiliator_discount").submit(function (event) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ProductCode = $("#ProductCode").val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype;
        //alert(base_url);

        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype + "&page=" + page;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode + "&reporttype=" + reporttype;
        $("#string").val(savestring);

        loadajaxfunctionAffiliatorDiscount(string, page, searchstring = '');

        return false;
    });

    $('#DateFrom').datepicker({dateFormat: 'yy-mm-dd'})
    $('#DateTo').datepicker({dateFormat: 'yy-mm-dd'})
    
});

function loadajaxfunction(string, page, searchstring) {
    console.log(string + "&searchstring=" + searchstring);
    if (!searchstring) {
        $("#searchstring").val('');
    }
    datasting = string;
    $.ajax({
        url: base_url + "report/loadinvoice",
        type: "post",
        data: string + "&searchstring=" + searchstring,
        dataType: "json",
        beforeSend: function () {
            $('#loading').show();
            $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");
            $('#reporttable').hide();
        },
        success: function (response) {
            // you will get response from your php page (what you echo or print)  
            //document.getElementById("reporttable").style.display = "block";
            console.log(response);
            $('#reporttable').show();
            $('#ExportToExcel').show();
            $('#loading').hide();
            $('#dataloadtable tbody').empty();

            InvoiceList = response['InvoiceList'];
            Page = response['PagingList'];

            if (InvoiceList.length == 0) {
                $('#dataloadtable tbody').append('<tr><td colspan="13" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');
                $('#ExportToExcel').hide();
            }

            for (var i = 0; i < InvoiceList.length; i++) {
                console.log(InvoiceList[i]);
                var sl = i + 1;

                var string1 = '';
                var string = '<tr><td>' + InvoiceList[i]['SL'] + '</td>\
                    <td><a target="_blank" href="'+base_url+'invoice/invspareprint?invoiceid='+InvoiceList[i]['InvoiceId']+'">' + InvoiceList[i]['InvoiceId'] + '</a></td>\
                    <td>' + InvoiceList[i]['InvoiceDate'] + '</td><td>' + InvoiceList[i]['CustomerType'] + '</td>';
                //if (grpUser == 1) {
                    string1 = '<td>' + InvoiceList[i]['DealerCode'] + ' - ' + InvoiceList[i]['DealerName'] + '</td>';
                //}
                var string2 = '<td>' + InvoiceList[i]['DealerAddress'] + '</td>\
					<td>' + InvoiceList[i]['CustomerName'] + '</td>\
					<td>' + InvoiceList[i]['PresentAddress'] + '</td>\
                    <td>' + InvoiceList[i]['MobileNo'] + '</td>\
                    <td>' + InvoiceList[i]['ProductCode'] + ' - ' + InvoiceList[i]['ProductName'] + '</td>\\n\
                    <td style="text-align: right;">' + InvoiceList[i]['Quantity'] + '</td>\
                    <td style="text-align: right;">' + InvoiceList[i]['Discount'] + '</td>\
                    <td style="text-align: right;">' + InvoiceList[i]['Buying_Price'] + '</td>\
					<td style="text-align: right;">' + InvoiceList[i]['Selling_Price'] + '</td>\
					<td style="text-align: right;">' + InvoiceList[i]['Profit'] + '</td>\
					<td style="text-align: right;">' + InvoiceList[i]['Total_Profit'] + '</td>\
					<td style="text-align: right;">' + InvoiceList[i]['YRCisKnown'] + '</td>\
                    </tr>';
                string = string + string1 + string2; //.concat(string1, string2);

                $('#dataloadtable tbody').append(string);
                //t.rows.add($(string)).draw();
            }
            $('#pagination').empty();

            previous = page - 1;
            next = page + 1;

            if (Page.length != 0 && page != 1) {
                $('#pagination').append('<li><a onclick="load(' + previous + ')" href="#" id="PageNo">Previous</a></li>');
            }
            for (var i = 0; i < Page.length; i++) {
                if (page == Page[i]['PageNo']) {
                    classcontent = ' class="active"';
                } else {
                    classcontent = '';
                }
                $('#pagination').append('<li' + classcontent + '><a onclick="load(' + Page[i]['PageNo'] + ')" href="#" id="PageNo">' + Page[i]['PageNo'] + '</a></li>');
            }
            if (Page.length != 0 && page != Page.length) {
                $('#pagination').append('<li><a onclick="load(' + next + ')" href="#" id="PageNo">Next</a></li>');
            }

            $("#a_ExportToExcel").attr("href", base_url + "report/invoiceexcelexport/?" + datasting + "&excelfilename=" + excelfilename);



        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function loadajaxfunctionAffiliatorDiscount(string, page, searchstring) {
    console.log(string + "&searchstring=" + searchstring);
    if (!searchstring) {
        $("#searchstring").val('');
    }
    datasting = string;
    $.ajax({
        url: base_url + "report/loadAffiliatorDiscountInvoice",
        type: "post",
        data: string + "&searchstring=" + searchstring,
        dataType: "json",
        beforeSend: function () {
            $('#loading').show();
            $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");
            $('#reporttable').hide();
        },
        success: function (response) {
            // you will get response from your php page (what you echo or print)  
            //document.getElementById("reporttable").style.display = "block";
            console.log(response);
            $('#reporttable').show();
            $('#ExportToExcel').show();
            $('#loading').hide();
            $('#dataloadtable tbody').empty();

            InvoiceList = response['InvoiceList'];
            Page = response['PagingList'];

            if (InvoiceList.length == 0) {
                $('#dataloadtable tbody').append('<tr><td colspan="13" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');
                $('#ExportToExcel').hide();
            }

            for (var i = 0; i < InvoiceList.length; i++) {
                console.log(InvoiceList[i]);
                var sl = i + 1;

                var string1 = '';
                var string = '<tr><td>' + InvoiceList[i]['SL'] + '</td>\
                    <td><a target="_blank" href="'+base_url+'invoice/invspareprint?invoiceid='+InvoiceList[i]['InvoiceId']+'">' + InvoiceList[i]['InvoiceId'] + '</a></td>\
                    <td>' + InvoiceList[i]['InvoiceDate'] + '</td><td>' + InvoiceList[i]['CustomerType'] + '</td>';
                //if (grpUser == 1) {
                    string1 = '<td>' + InvoiceList[i]['DealerCode'] + ' - ' + InvoiceList[i]['DealerName'] + '</td>';
                //}
                var string2 = '<td>' + InvoiceList[i]['DealerAddress'] + '</td>\
					<td>' + InvoiceList[i]['CustomerName'] + '</td>\
					<td>' + InvoiceList[i]['PresentAddress'] + '</td>\
                    <td>' + InvoiceList[i]['MobileNo'] + '</td>\
                    <td>' + InvoiceList[i]['ProductCode'] + ' - ' + InvoiceList[i]['ProductName'] + '</td>\\n\
                    <td style="text-align: right;">' + InvoiceList[i]['Quantity'] + '</td>\
                    <td style="text-align: right;">' + InvoiceList[i]['Discount'] + '</td>\
                    <td style="text-align: right;">' + InvoiceList[i]['Affiliator'] + '</td>\
                    <td style="text-align: right;">' + InvoiceList[i]['AffiliatorDiscount'] + '</td>\
                    <td style="text-align: right;">' + InvoiceList[i]['Buying_Price'] + '</td>\
					<td style="text-align: right;">' + InvoiceList[i]['Selling_Price'] + '</td>\
					<td style="text-align: right;">' + InvoiceList[i]['Profit'] + '</td>\
					<td style="text-align: right;">' + InvoiceList[i]['Total_Profit'] + '</td>\
					<td style="text-align: right;">' + InvoiceList[i]['YRCisKnown'] + '</td>\
                    </tr>';
                string = string + string1 + string2; //.concat(string1, string2);

                $('#dataloadtable tbody').append(string);
                //t.rows.add($(string)).draw();
            }
            $('#pagination').empty();

            previous = page - 1;
            next = page + 1;

            if (Page.length != 0 && page != 1) {
                $('#pagination').append('<li><a onclick="load(' + previous + ')" href="#" id="PageNo">Previous</a></li>');
            }
            for (var i = 0; i < Page.length; i++) {
                if (page == Page[i]['PageNo']) {
                    classcontent = ' class="active"';
                } else {
                    classcontent = '';
                }
                $('#pagination').append('<li' + classcontent + '><a onclick="load(' + Page[i]['PageNo'] + ')" href="#" id="PageNo">' + Page[i]['PageNo'] + '</a></li>');
            }
            if (Page.length != 0 && page != Page.length) {
                $('#pagination').append('<li><a onclick="load(' + next + ')" href="#" id="PageNo">Next</a></li>');
            }

            $("#a_ExportToExcel").attr("href", base_url + "report/loadAffiliatorDiscountInvoice/?" + datasting + "&excelfilename=" + excelfilename+"&excel=yes");



        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function load(pageno) {
    savestring = $("#string").val();
    searchstring = $("#searchstring").val();
    ajaxstring = savestring + "&page=" + pageno;
    //console.log(ajaxstring);
    loadajaxfunction(ajaxstring, pageno, searchstring);
} 