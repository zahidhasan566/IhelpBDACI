$(document).ready(function () {
    if(category == "newbay"){
        document.getElementById("bayname").focus();
    }else if(category == "newwork"){
        document.getElementById("workname").focus();
    }else if(category == "newtechnician"){
        document.getElementById("technicianname").focus();
    }
    $('#joiningdate').datepicker({  dateFormat: 'yy-mm-dd'  })     
})


function doLoadUpazila(DistrictCode, UpazilaCode) {
    //console.log(DistrictCode);
    $.ajax({
        url: base_url + "jobcard/loadupazila",
        type: "post",
        data: "DistrictCode=" + DistrictCode,
        dataType: "json",
        beforeSend: function () {
        },
        success: function (response) {
            $("#upazillacode").empty();
            if(response.length > 0){
                for(i = 0; i < response.length; i++){
                    $("#upazillacode").append(new Option(response[i]['UpazillaName'], response[i]['UpazillaCode']));
                }
                $("#upazillacode").val(UpazilaCode);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}