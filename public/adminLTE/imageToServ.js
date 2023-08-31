localStorage.removeItem("imgDelete");
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
                if(loader.dataset.name == "single") {
                    loader.parentElement.parentElement.querySelectorAll('.' + loader.dataset.name + '> .image-card')?.forEach(card=>card.remove());
                    loader.parentElement.parentElement.querySelector('.' + loader.dataset.name).innerHTML = "";
                }
                data.forEach(name=>{
                    let imageCard = document.createElement("div");
                    imageCard.classList.add("image-card");
                    imageCard.setAttribute("style", "box-shadow:0px 0px 5px #28a745;");
                    let img = new Image();
                    img.src = '../images/' + name.servName;
                    img.classList.add('image-card__image');
                    imageCard.innerHTML +="<button class='image-card__delete' data-name='"+name.servName+"' data-path='"+loader.dataset.name+"'>&#x26CC;</button><div class='image-card__image__wrap'></div><hr class='image-card__hr'><span class='image-card__desc'>" + name.uploadName + "</span>";
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
document.querySelectorAll('.selectedImages').forEach(block=>{
    block.addEventListener('click', (e)=>{
        const target = e.target;
        e.preventDefault();
        if(target.classList.contains("image-card__delete")) {
            if(target.dataset.type) {
                if(confirm("Confirm deleting")) {
                    if(target.dataset.type == "single") {
                        let imgDelete;
                        if(target.closest('form').querySelector('#imgDeleteSingle')) {
                            imgDelete = target.closest('form').querySelector('#imgDeleteSingle').value;
                        } else {
                            imgDelete = document.createElement('input');
                            imgDelete.setAttribute("type", "text");
                            imgDelete.setAttribute("name", "imgDelete[single]");
                            imgDelete.hidden = true;
                            imgDelete.readOnly = true;
                        }
                        imgDelete.value = target.dataset.name;
                        target.closest('form').append(imgDelete);
                    }  else if(target.dataset.type == "multi") {
                        const imgDeleteName = document.createElement('input');
                        imgDeleteName.setAttribute("type", "text");
                        imgDeleteName.setAttribute("name", "imgDelete[multi]["+target.dataset.delete+"][name]");
                        imgDeleteName.hidden = true;
                        imgDeleteName.readOnly = true;
                        const imgDelete = document.createElement('input');
                        imgDelete.setAttribute("type", "text");
                        imgDelete.setAttribute("name", "imgDelete[multi]["+target.dataset.delete+"][id]");
                        imgDelete.hidden = true;
                        imgDelete.readOnly = true;
                        imgDeleteName.value = target.dataset.name;
                        imgDelete.value = target.dataset.delete;
                        target.closest('form').append(imgDeleteName);
                        target.closest('form').append(imgDelete);
                    }
                    target.closest(".image-card").remove();
                }
            } else if(target.dataset.path) {
                fetch(adminpath + "/product/deleteImgs?path=" +target.dataset.path + "&name=" +target.dataset.name, {
                    method: "GET"
                }).then((data)=>{
                    if(!data.ok) {
                        return data.text().then(text=>{throw new Error(text)});  
                    }
                    target.parentElement.remove();
                })
                .catch((error)=>{
                    target.parentElement.parentElement.parentElement.previousElementSibling.append(createError("", "Error deleting image"));
                    target.disabled = true;
                    setTimeout(()=>{
                        target.disabled = false;
                        target.parentElement.parentElement.parentElement.previousElementSibling.querySelector(".fileError").remove();

                    }, 3000);
                });
            }

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