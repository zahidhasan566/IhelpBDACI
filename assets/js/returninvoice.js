$(document).ready(function () {

    calculateTotal();

    $("input").keyup(function () {
        var currentRow = $(this).closest('tr');
        var unitprice = parseFloat($('.unitprice', currentRow).text());
        var quantity = parseInt($('.qty', currentRow).val());
        var discount = parseFloat($('.discount', currentRow).val());
        var vat = parseFloat($('.vat', currentRow).text());
        var discountAmount = 0;

        if (!isNaN(quantity) && !isNaN(discount)) {
            discountAmount = ((unitprice * quantity) * discount) / 100;
            $('.discountAmount', currentRow).text(discountAmount.toFixed(2));
            $('.total', currentRow).text((((unitprice * quantity) - discountAmount) + vat).toFixed(2));
        }

        calculateTotal();

    });

    $(".qty").change(function () {
        var currentRow = $(this).closest('tr');
        var unitprice = parseFloat($('.unitprice', currentRow).text());
        var quantity = parseInt($('.qty', currentRow).val());
        var discount = parseFloat($('.discount', currentRow).val());
        var vat = parseFloat($('.vat', currentRow).text());
        var discountAmount = 0;

        if(isNaN(quantity)){
            quantity = 0;
        }

        if (!isNaN(quantity) && !isNaN(discount)) {
            discountAmount = ((unitprice * quantity) * discount) / 100;
            $('.discountAmount', currentRow).text(discountAmount.toFixed(2));
            $('.total', currentRow).text((((unitprice * quantity) - discountAmount) + vat).toFixed(2));
        }

        calculateTotal();

    });

    function calculateTotal() {
        var total = 0;
        var totalDiscount = 0;
        $(".total").each(function () {
            total += parseFloat($(this).text().replace(/[,]/g,''));
        });

        $(".discountAmount").each(function () {
            totalDiscount += parseFloat($(this).text().replace(/[,]/g,''));
        });

        if(!isNaN(total) && !isNaN(totalDiscount)){
            $('#totaldiscountamount').text(totalDiscount.toFixed(2) + '/-');
            $('#grandtotal').text(total.toFixed(2) + '/-');

            var words = numberToEnglish(Math.round(total));
            $('#num2word').text('Tk ' + words + ' only');
        }
    }

});

$("#return-invoice").click(function (e) {

    e.preventDefault();

    var form_action = $("#returninvoice").find("input[name='action']").val();
    var invoiceid = $("#returninvoice").find("input[name='invoiceid']").val();
    var invoiceDetailsId = $("#returninvoice").find("input[name='invoiceDetailsId[]']").map(function(){return $(this).val();}).get();
    var invoiceno_string = $("#returninvoice #invoiceno").text();
    var invoiceno = invoiceno_string.substring(13);
    var currentqty = $("#returninvoice").find("select[name='qty[]']").map(function(){return $(this).val();}).get();
    var prevqty = $("#returninvoice").find("input[name='prevqty[]']").map(function(){return $(this).val();}).get();
    var currentdiscount = $("#returninvoice").find("input[name='discount[]']").map(function(){return $(this).val();}).get();
    var prevdiscount = $("#returninvoice").find("input[name='prevdiscount[]']").map(function(){return $(this).val();}).get();
    var productcode = $("#returninvoice").find("input[name='productcode[]']").map(function(){return $(this).val();}).get();

    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: form_action + "invoice/returnInvoiceOperation",
        cache: false,
        data: {
            InvoiceID:invoiceid,
            InvoiceDetailsID:invoiceDetailsId,
            InvoiceNo:invoiceno,
            CurrentQty:currentqty,
            Currentdiscount:currentdiscount,
            PrevQty:prevqty,
            PrevDiscount:prevdiscount,
            ProductCode:productcode
        },
        success: function( data, textStatus, jQxhr ){
            var res = data;
            if (res.success==1) {
                $('.alert-success').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> '+res.message ).show();
                $('.alert-success').delay(3000).fadeOut('slow');                
                if (res.redirect !== '') window.open(res.redirect, '_blank', 'width=650,height=600,location=no,left=200px');
                $(".loadcontent").html('');
                $(".chassis").val('');
            } else {
                $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+res.message ).show();
                $('.alert-danger').delay(3000).fadeOut('slow');
            }
                        
        },
        error: function( jqXhr, textStatus, errorThrown ){
            $('.alert-danger').html( '<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> '+errorThrown ).show();
            $('.alert-danger').delay(3000).fadeOut('slow');
        }
    });
});