document.querySelectorAll('#single, #multi').forEach(loader=>{
    
    loader.addEventListener('change', ()=>{
        loader.parentElement.parentElement.previousElementSibling.querySelector('.fileError')?.remove();
        if(loader.files.length > 0) {
            let data = new FormData();
            for(let file in loader.files) {
                if(typeof(loader.files[file]) === "object") {
                    switch(loader.files[file].type) {
                        case "image/jpeg": 
                        case "image/png":
                        case "image/pjpeg":
                            data.append(loader.dataset.name + "[]", loader.files[file]);
                            data.append("name", loader.dataset.name);
                            continue;
                        default: {
                            loader.parentElement.parentElement.previousElementSibling.append(createError("File type encorect: ", loader.files[file].name));
                            return(2);
                        }   
                    }
                }
            }
            loader.parentElement.parentElement.parentElement.querySelector('.overlay.dark').setAttribute("style", "");
            fetch(adminpath + "/" + loader.dataset.url,{
                method: "POST",
                body : data
            }).then(data=>{
                if(!data.ok) {
                    throw new Error();
                }
                return data.json();
            }).then(data=>{
                if(data.error) {
                    throw new Error(data.error);
                }
                loader.parentElement.parentElement.querySelectorAll('.' + loader.dataset.name + '> .image-card')?.forEach(card=>card.remove());
                loader.parentElement.parentElement.querySelector('.' + loader.dataset.name).innerHTML = "";
                data.forEach(name=>{
                    let imageCard = document.createElement("div");
                    imageCard.classList.add("image-card");
                    let img = new Image();
                    img.src = '../images/' + name.servName;
                    img.classList.add('image-card__image');
                    imageCard.innerHTML +="<div class='image-card__image__wrap'></div><hr class='image-card__hr'><span class='image-card__desc'>" + name.uploadName + "</span>";
                    imageCard.querySelector('.image-card__image__wrap').appendChild(img);
                    img.onload = ()=> {
                        loader.parentElement.parentElement.querySelector('.' + loader.dataset.name).appendChild(imageCard);
                        loader.parentElement.parentElement.querySelector('.' + loader.dataset.name).setAttribute("style", "");
                    }
                });
            }).catch((e)=>{
                loader.parentElement.parentElement.previousElementSibling.append(createError("", e.message));
            }).finally(()=>{
                loader.parentElement.parentElement.parentElement.querySelector('.overlay.dark').setAttribute("style", "display:none;");
            });
        } 
    });
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