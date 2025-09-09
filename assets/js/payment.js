$(document).ready(function() {
    // alert("OKKK");
    $("#customerCode").on('change',function() {
        var business = $(this).find(':selected').attr('data-business');
        var salesType = $(this).find(':selected').attr('data-salesType');
        var depotCode = $(this).find(':selected').attr('data-depotCode');
        $("#business").val(business);
        $("#salesType").val(salesType);
        $("#depotCode").val(depotCode);

        $.ajax({
            url: base_url+'Payment/getBankByDepoCode/'+depotCode,
            method:'GET',
            // dataType:'json',
            success: function(data) {
                data = JSON.parse(data);
                if(data.length > 0) {
                    $(data).each(function(index,item) {
                        console.log(index,item);
                        $("#bank").append("<option value='"+item.BankCode+"'>"+item.BankName+"</option>");

                    });
                }

            }
        });        
    });

    $("#paymentMode").on('change',function() {
        if($(this).val() == 'Online') {            
            $("#chequeNo").attr('required',false);
        } else {
            $("#chequeNo").attr('required',true);
        }
    });

    $("#chequeImage").on('change',function() {
        const [file] = chequeImage.files
        if (file) {
            imagePreview.src = URL.createObjectURL(file)
        }
    });

    function checkSubmit(){
        var btn = document.getElementById('submitBtn');
        btn.disabled = true;
    }
});