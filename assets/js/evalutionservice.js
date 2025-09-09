$(document).ready(function () {                    
	$('#openingdate').datepicker({
      dateFormat: 'yy-mm-dd',
      startDate: '-3d'
    })
    
    $('#evalutiondate').datepicker({
      dateFormat: 'yy-mm-dd',
      startDate: '-3d'
    })
	        
});
        
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}


function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}

function changescore(target,weight,score,actual){
    var valtarget = parseFloat(document.getElementById(target).value);
    var valweight = parseFloat(document.getElementById(weight).value);
    var valactual = parseFloat(actual);
    var cal = parseFloat(( valactual / valtarget ) * valweight); 
    if(isNaN(cal)){
        cal = 0;
    }
    document.getElementById(score).value = cal;
    //console.log(valtarget,valweight,actual,cal);
    var arr = document.getElementsByName('score[]');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
            tot += parseFloat(arr[i].value);
    }
    document.getElementById('scoretotal').innerHTML = tot;
}