document.querySelectorAll('#single, #multi').forEach(loader=>{
    
    loader.addEventListener('change', ()=>{
        loader.parentElement.parentElement.querySelector('.fileError')?.remove();
        if(loader.files.length > 0) {
            let data = new FormData();
            for(let file in loader.files) {
                if(typeof(loader.files[file]) === "object") {
                    switch(loader.files[file].type) {
                        case "image/jpeg": 
                        case "image/png":
                        case "image/pjpeg":
                            data.append("images[]", loader.files[file]);
                            continue;
                        default: {
                            const errorMeassage = document.createElement("div");
                            errorMeassage.setAttribute("class", "fileError alert alert-danger alert-dismissible");
                            errorMeassage.setAttribute("style", "display: block;");
                            errorMeassage.innerHTML = 
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
                                +'<h6 style="display: inline-block;" ><i class="icon fas fa-ban"></i></h6>' 
                                +  'File type encorect: ' + '"' + loader.files[file].name
                                    .replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '')
                                    .replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '') 
                                + '"';
                            loader.parentElement.parentElement.prepend(errorMeassage);
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
                loader.parentElement.parentElement.querySelectorAll('.' + loader.dataset.name + '> .image-card')?.forEach(card=>card.remove());
                loader.parentElement.parentElement.querySelector('.' + loader.dataset.name).innerHTML = "";
                data.forEach(name=>{
                    let imageCard = document.createElement("div");
                    imageCard.classList.add("image-card");
                    let img = new Image();
                    img.src = '../images/' + name;
                    img.classList.add('image-card__image');
                    imageCard.appendChild(img);
                    imageCard.innerHTML +="<hr class='image-card__hr'><span class='image-card__desc'>" + name + "</span>";
                    img.onload = ()=> {
                        loader.parentElement.parentElement.querySelector('.' + loader.dataset.name).appendChild(imageCard);
                        loader.parentElement.parentElement.querySelector('.' + loader.dataset.name).setAttribute("style", "");
                    }
                });
                
            }).then(()=>{
                loader.parentElement.parentElement.parentElement.querySelector('.overlay.dark').setAttribute("style", "display:none;");
            }).catch((e)=>{
                const errorMeassage = document.createElement("div");
                errorMeassage.setAttribute("class", "fileError alert alert-danger alert-dismissible");
                errorMeassage.setAttribute("style", "display: block;");
                errorMeassage.innerHTML = 
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
                    + '<h6 style="display: inline-block;" ><i class="icon fas fa-ban"></i></h6>' 
                    + 'Some error with uploading image(s)' + e.code;
            });
        } 
    });
});