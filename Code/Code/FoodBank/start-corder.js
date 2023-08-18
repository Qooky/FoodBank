function changeQty(type, size, gender) {
    var qty = $("#"+type+"-"+size+"-"+gender).val();
    console.log(qty);
    //$.post("start-forder.php", {qty: qty});

    $.ajax({
        type : 'POST',  //type of method
        url  : 'start-corder.php',  //your page
        data : {qty : qty, type: type, size: size, gender: gender}
    });
    

}
