function changeQty(name) {
    var qty = $("#"+name).val();
    console.log(qty);
    //$.post("start-forder.php", {qty: qty});

    $.ajax({
        type : 'POST',  //type of method
        url  : 'start-forder.php',  //your page
        data : {qty : qty, name: name}
    });
    

}
