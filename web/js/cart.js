/**
 * Created with JetBrains PhpStorm.
 * User: Grizzly
 * Date: 29/07/15
 * Time: 14:29
 * To change this template use File | Settings | File Templates.
 */

var cart = getCookie('cart')!="" ? JSON.parse(getCookie('cart')) : new Array();

for(var i = 0; i < cart.length; i++) {
    updateCart(cart[i].id);
};


function updateCart(id){
    var don = document.getElementById('name'+id);
    var name = don.innerText || don.textContent;

    don = document.getElementById('description'+id);
    var description = don.innerText || don.textContent;

    don = document.getElementById('price'+id);
    var price = don.innerText || don.textContent;

    var qty = document.getElementById('qty'+id).value;
    qty = parseInt(qty);

    don = document.getElementById('stock'+id);
    var stock = don.innerText || don.textContent;
    stock = parseInt(stock);

    don = document.getElementById('seller'+id);
    var seller = don.innertText || don.textContent;

    var totaux = 0;

    for(var i = 0; i < cart.length; i++) {
        var total = 0;
        if (cart[i].id == id) {
            cart[i].name        = name;
            cart[i].description = description;
            cart[i].price       = price;
            if (qty >= stock) {
                if (qty == stock) {
                    cart[i].qty = qty;
                }
                document.getElementById('errorqty'+id).style.display="block";
            } else {
                cart[i].qty = qty;
                document.getElementById('errorqty'+id).style.display="none";
            }
            cart[i].stock  = stock;
            cart[i].seller = seller;
            total = cart[i].price * cart[i].qty;
            document.getElementById('total'+id).innerHTML = total;
        }
        totaux = totaux + (cart[i].qty * cart[i].price);
    }
    document.getElementById('totaux').innerHTML = totaux;

    setCookie('cart',JSON.stringify(cart),3);
};

function deleteCart(id) {
    for(var i = 0; i < cart.length; i++) {
        if (cart[i].id == id) {
            cart.splice(i,1);
        }
    }
    setCookie('cart',JSON.stringify(cart),3);
    location.href=urlCart;

};


function addCart(id){
    var trouve = false;

    var don = document.getElementById('name'+id);
    var name = don.innerText || don.textContent;

    don = document.getElementById('description'+id);
    var description = don.innerText || don.textContent;

    don = document.getElementById('price'+id);
    var price = don.innerText || don.textContent;

    don = document.getElementById('stock'+id);
    var stock = don.innerText || don.textContent;
    stock = parseInt(stock);

    don = document.getElementById('seller'+id);
    var seller = don.innertText || don.textContent;

    for(var i = 0; i < cart.length; i++) {
        if (cart[i].id == id) {
            cart[i].name        = name;
            cart[i].description = description;
            cart[i].price       = price;
            if (cart[i].qty < stock) {
                cart[i].qty = cart[i].qty + 1;
            } else {
                document.getElementById('errorqty'+id).style.display="block";
            }
            cart[i].stock  = stock;
            cart[i].seller = seller;
            trouve = true;
        }
    }

    if (!trouve) {
        cart.push({id: id, name: name, description: description, price: price, qty: 1, stock: stock, seller: seller});
    }

    setCookie('cart',JSON.stringify(cart),3);
};


function formOrder(){
    var form = document.getElementById('order');
    var button = document.getElementById('command');
    form.style.display = "block";
    button.style.display = "none";
};


$('select').change(function(){
    var category = $("select option:selected").text();
    var id = document.getElementById(category).getAttribute("name");
    if (id == "0") {
        location.href=redirUrl;
    } else {
        location.href=redirUrl+market+'category/'+id;
    }
})
