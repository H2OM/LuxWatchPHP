document.querySelectorAll('.add-to-cart-link').forEach(link=>{
    link.addEventListener('click',e=>{
        e.preventDefault();
        let id = e.target.dataset.id,
            qty = document.querySelector('.quantity > input')?.value ?? 1,
            mod = document.querySelector('.available select')?.value;
            fetch("/Projects/LuxuryWatchesPHP/public/cart/add", {
                body:JSON.stringify({id, qty, mod}),
                method: 'POST'
            }).then(data=>data.text())
            .then((data)=>{
                showCart(data);
            })
            .catch((e)=>{
                alert(`Seems error with server connection, ${e}`);
            });          

    });
})

function showCart(cart) {
    console.log(cart);
}

$('#currency').change(function(){
    window.location = 'currency/change?curr=' + $(this).val();
});

document.querySelector('.available select')?.addEventListener('change', e=>{
    let option = e.target.options[e.target.selectedIndex],
    modId = option.value,
    color = option.dataset.title,
    price = option.dataset.price,
    bacePrice = document.querySelector('[data-base]');
    if(price) {
        bacePrice.innerHTML = price;
    } else {
        bacePrice.innerHTML = bacePrice.dataset.base;
    }

});