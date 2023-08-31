localStorage.removeItem('modCount');
document.querySelector('#mod__btn')?.addEventListener('click',(e)=>{
    e.preventDefault();
    const mod = document.querySelector('#mod').value,
    price = document.querySelector('#mod__price').value;
    let count = 0;
    if(localStorage.getItem("modCount")) count = localStorage.getItem('modCount');

    document.querySelector('#mod__output > tbody').innerHTML += 
    '<tr style="margin-top:10px; background-color:rgba(40, 167, 69, 0.60);">' +
    '<td><input style="border:none; background-color: unset; width: auto; " type="text" name="mod['+count+'][mod]" value="'+ mod +'" readonly></td>'
    + '<td><input style="border:none; background-color: unset; width: auto; " type="number" name="mod['+count+'][price]" value="'+ price +'" readonly></td>'
    + '<td style="text-align: center; cursor:pointer;" class="mod__delete">&#x26CC;</td>'
    + '</tr>';
    localStorage.setItem("modCount", ++count);
});
document.querySelector('#mod__output > tbody')?.addEventListener('click',elem=>{
    if(elem.target.classList.contains("mod__delete") && elem.target.parentElement.dataset.modid) {
        fetch(adminpath + "/product/modDelete?modId=" + elem.target.parentElement.dataset.modid, {
            method: "GET"
        }).then((data)=>{
            if(!data.ok) {
                throw Error();
            }
            elem.target.parentElement.remove();
        }).catch(()=>{
            elem.target.closest(".form-group").prepend(createError("", "Error deleting modification"));
            setTimeout(()=>elem.target.closest(".form-group").querySelector(".fileError").remove(),3000);
        });
    } else if(elem.target.classList.contains("mod__delete")) {
        elem.target.parentElement.remove();
    }
});

function createError (errorName, errorMeassage = "") {
    const errorBar = document.createElement("div");
    errorBar.setAttribute("class", "fileError callout callout-danger");
    if(errorMeassage) {
        errorMeassage = errorMeassage
            .replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '')
            .replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
            if(errorMeassage.length > 43) errorMeassage = errorMeassage.substring(0, 40) + "...";
    }
    
    errorBar.innerHTML = '<h6> ' + errorName  + errorMeassage + '</h6>';
    return errorBar;
}