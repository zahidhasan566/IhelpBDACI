$(document).ready(function () {
    $('#reporttable').hide();
    $("#ReportOrder").submit(function (event) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();

        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode;
        //alert(base_url);

        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode;
        $("#string").val(savestring);

        loadajaxfunction(string, page, searchstring = '');

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
        url: base_url + "report/loadcsidetails",
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
            $('#loading').hide();
            $('#dataloadtable tbody').empty();


            if (response['summerydata'].length == 0) {
                $('#dataloadtable tbody').append('<tr><td colspan="17" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');
                $('#ExportToExcel').hide();
            }

            for (var i = 0; i < response['summerydata'].length; i++) {
                var sl = i + 1;
                var ratio = parseFloat(response['summerydata'][i]['CSI_Percetage']);

                var string = '<tr><td>' + sl + '</td>\\n\
                                <td>' + response['summerydata'][i]['RegionName'] + '</td>\
                                <td>' + response['summerydata'][i]['DealerCode'] + '</td>\
                                <td class="text-left">' + response['summerydata'][i]['DealerName'] + '</td>\
                                <td class="text-left">' + ratio.toFixed(2);
                                +'</td></tr>';

                $('#dataloadtable tbody').append(string);
                //t.rows.add($(string)).draw();
            }
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

