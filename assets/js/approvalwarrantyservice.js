 function cancelwarranty(warrantyid){
    var r = confirm("Are you sure you want to cance this warranty..??");
    if (r == true) {
        //ajax....
        $.ajax({
            type: "POST",
            url: base_url + "approval/cancelwarranty",
            data: {warrantyid: warrantyid},
            dataType: "json",
            cache: false,
            success: function (res) {
                if(res == true){
                    $("#div" + warrantyid).html("Canceled");
                }else{
                    alert("Something worng!")
                }
                console.log(res)
            },
            error: function (msg) {
                response([]);
            }
        })
    }else{
        return false;
    }
    
 }