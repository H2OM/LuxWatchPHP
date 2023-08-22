document.body.addEventListener('change', e=>{
    if(e.target.classList.contains('filter_input')) {
        let checked = document.querySelectorAll('.filter_input');
            data ='';
        checked.forEach(each=>{
            if(each.checked) data += each.value + ',';
        });
        if(data) {
            document.querySelector('.preloader').style.display = "block";
            fetch(location.pathname + `?filter=${data}`,{
                method: "GET"
            }).then((answer)=>{
                return answer.text();

            }).then(answer=>{
                document.querySelector('.product-one').innerHTML = answer;
                let url = location.search.replace(/filter(.+?)(&|$)/g, '');
                let newUrl = location.pathname + url + (location.search ? "&" : "?") + "filter=" + data;
                newUrl = newUrl.replace('&&', '&');
                newUrl = newUrl.replace('?&', '?');
                history.pushState({}, '', newUrl);
            })
            .finally(()=>{
                document.querySelector('.preloader').style.display = ""; 
            });
        }else {
            window.location = location.pathname;
        }
    }
});

const products = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: path + '/search/typeahead?query=%QUERY'
    }
});

products.initialize();

$("#typeahead").typeahead({
    // hint: false,
    highlight: true
},{
    name: 'products',
    display: 'title',
    limit: 10,
    source: products
});

$('#typeahead').bind('typeahead:select', function(ev, suggestion) {
    // console.log(suggestion);
    window.location = path + '/search/?s=' + encodeURIComponent(suggestion.title);
});


document.querySelectorAll('.add-to-cart-link').forEach(link=>{
    link.addEventListener('click',e=>{
        e.preventDefault();
        let id = link.dataset.id,
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
});

$('#cart .modal-body, .content').on('click', '.del-item', function(){
    var id = $(this).data('id');
    $.ajax({
        url: '/Projects/LuxuryWatchesPHP/public/cart/delete',
        data: {id: id},
        type: 'GET',
        success: function(res){
            showCart(res);
        },
        error: function(){
            alert('Error!');
        }
    });
});
    
function showCart(cart) {
    if(cart.trim() == '<h3>Basket is empty</h3>') {
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'none');
    } else {
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'inline-block');   
    }
    $('#cart .modal-body').html(cart);
    $('#cart').modal();
    if($('.cart-sum').text()) {
        $('.simpleCart_total').html($('#cart .cart-sum').text());
    } else {
        $('.simpleCart_total').text('Empty basket');   
    }
}
function getCart() {
    fetch("/Projects/LuxuryWatchesPHP/public/cart/show", {
        method: 'GET'
    }).then(data=>data.text())
    .then((data)=>{
        showCart(data);
    })
    .catch((e)=>{
        alert(`Seems error with server connection, ${e}`);
    }); 
}
function clearCart(){
    fetch("/Projects/LuxuryWatchesPHP/public/cart/clear", {
        method: 'GET'
    }).then(data=>data.text())
    .then((data)=>{
        showCart(data);
    })
    .catch((e)=>{
        alert(`Seems error with server connection, ${e}`);
    }); 
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