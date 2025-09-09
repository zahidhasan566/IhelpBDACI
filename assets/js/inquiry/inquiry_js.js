$(document).ready(function () {

    $('#getresult').on('click', '#walkin', function () {

        var status = $("#walkin").is(":checked");
        if (status) {
            $("#getresultwalkin").css("display", "block");
            $("#getresultgenuine").css("display", "none");
        } else {
            $("#getresultwalkin").css("display", "none");
            $("#getresultgenuine").css("display", "block");
        }
    });
    $('#btnaddinq').click(function () {
        var name = $("#name").val();
        var mobile = $("#mobile").val();
        var address = $("#address").val();
        var age = $("#age").val();
        var days = $("#days").val();
        var model = $("#model").val();
        var color = $("#color").val();


        /*if ((!name) || (!mobile) || (!address) || (!age) || (!days) || (!model) || (!color)) {
            alert("Enter all values correctly");
            return;
        }*/
        var inqstr = "<div class='form-group'>"
                +"<div class='col-md-2 col-xs-12'>"
                + "<input type='text' id='product' placeholder='name' name='name[]' value='' maxlength='256' minlength='1' required='required' class='form-control col-xs-12'>"
                + "</div>"

                + "<div class='col-md-2 col-xs-12'>"
                + "<input type='text' id='mobile' placeholder='Mobile No.'  name='mobile[]' value='' maxlength='16' minlength='11' required='required' class='form-control col-xs-12' onkeypress='return isNumberKey(event)' >"
                + "</div>"

                + "<div class='col-md-2 col-xs-12'>"
                + "<input type='text' id='address' placeholder='Address'  name='address[]' value=''  maxlength='256' minlength='1' required='required' class='form-control col-xs-12'>"
                + "</div>"

                + "<div class='col-md-1 col-xs-12'>"
                + "<input type='text' id='age' placeholder='Age'  name='age[]' value='' required='required'  maxlength='2' minlength='2' class='form-control col-xs-12' onkeypress='return isNumberKey(event)' >"
                + "</div>"

                + "<div class='col-md-1 col-xs-12'>"
                + "<input type='text' id='days' placeholder='Days'  name='days[]' value='' required='required'  maxlength='5' minlength='1' class='form-control col-xs-12' onkeypress='return isNumberKey(event)' >"
                + "</div>"
        
                + "<div class='col-md-2 col-xs-12'>"
                + "<input type='text' id='model' placeholder='Model'  name='model[]' value='' required='required'  maxlength='64' minlength='1' class='form-control col-xs-12'>"
                + "</div>"
                + "<div class='col-md-1 col-xs-12'>"
                + "<input type='text' id='color' placeholder='Color'  name='color[]' value='' required='required'  maxlength='64' minlength='1' class='form-control col-xs-12'>"
                + "</div>"
        
                + "<div class='col-md-1 col-xs-12'>"
                + "<input type='button' value='X' class='closeinq btn btn-danger pull-right' />";
                + "</div>"
                + "</div>";
        $('#inquiryrow').append(inqstr);
    });
    
     $('#inquiryrow').on('click', '.closeinq', function () {
        $(this).closest(".form-group").remove();
    });
});
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}