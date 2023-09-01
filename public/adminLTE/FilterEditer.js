if(document.querySelector('[data-type="filter"]')) {
    fetch(adminpath + "/filter/filter-edit?groups=all").then(data=>{
        if(!data.ok) {
            throw new Error();
        }
        return data.json();
        
    }).then(data=>localStorage.setItem("Allgroups", JSON.stringify(data))).catch(()=>{
        document.querySelector('[data-type="filter"]').closest('.box-body').prepend(createError("Error get filters groups", "Rrestart page or try again later!"));
    });
}
document.querySelectorAll('.filter-group').forEach(table=>{
    table.addEventListener('click',e=>{
        if(e.target.classList.contains('groupEditer') || e.target.parentElement.classList.contains('groupEditer')) {
            e.preventDefault();
            createEditer(e.target.closest("tr"));
        }
    });
});

function createEditer(mainTr) {
    const prevTr = document.createElement("tr");
    prevTr.setAttribute("style", mainTr.getAttribute("style"));
    prevTr.setAttribute("data-type", mainTr.getAttribute("data-type"));
    prevTr.innerHTML = mainTr.innerHTML;
    let newTds = '';
    mainTr.querySelectorAll("td").forEach(td=>{
        if("editer" in td.dataset) {
            if("filter" in td.dataset) {
                newTds += "<td><input data-input data-title name='title' type='text' value='"+td.dataset.filter+"' required style='border:none; outline: 1px solid #dee2e6; outline-offset: -1px; width: 0px; min-width:"+td.dataset.filter.length*12+"px'></td>";
            } else if("group" in td.dataset) {
                newTds +="<td><select data-newgroup>";
                const groups = JSON.parse(localStorage.getItem("Allgroups"));
                for(let k in groups) {
                    newTds += "<option value='"+groups[k].id+"'"+(groups[k].title==td.dataset.group ? 'selected' : '')+">"+groups[k].title+"</option>";
                }
                newTds +="</select></td>";
            } else {
                newTds +="<td>"+td.innerHTML+"</td>";
            }
        }
    });
    newTds += "<td><button data-editorSave style='background-color: unset; border:none;'>&#128190;</button></td><td><button data-editorClose style='background-color: unset; outline-offset: -1px; border:none; text-decoration:underline;'>Close</button></td>";
    mainTr.innerHTML = newTds;
    mainTr.querySelector('[data-input]').focus();
    mainTr.querySelector('[data-input]').oninput = () =>{
        let target = mainTr.querySelector('[data-input]');
        target.style.width = (target.value.length+1) * 8 + 'px';
    };
    mainTr.querySelector('[data-input]').addEventListener('keydown',({code})=>{
        if(code=="Enter") {
            mainTr.querySelector('[data-editorSave]').click();
        }
    });
    mainTr.querySelector('[data-editorClose]').addEventListener('click',e=>{
        e.preventDefault();
        mainTr.innerHTML = prevTr.innerHTML; 
    });
    mainTr.querySelector('[data-editorSave]').addEventListener('click',e=>{
        e.preventDefault();
        const title = mainTr.querySelector("[data-title]"),
              titleInput = mainTr.querySelector('[data-input]'),
              prevTitle = prevTr.querySelector("[data-filter]"),
              filterGroup = prevTr.querySelector("[data-group]") ?? null,
              filterNewGroup = mainTr.querySelector("[data-newgroup]") ?? null,
              filterNewGroupValue = filterNewGroup ? filterNewGroup.querySelector('option[value="'+filterNewGroup.value+'"]').textContent : null;
        
        if(title.value.length < 1) {
            e.target.disabled = true;
            titleInput.style.outline = "2px solid red";
            setTimeout(()=>{
                e.target.disabled = false;
                titleInput.style.outline = "";
            }, 2000); 
            return; 
        }
        if((!filterGroup && prevTitle.dataset.filter == title.value) || 
            (filterGroup && (prevTitle.dataset.filter == title.value) && (filterGroup.dataset.group == filterNewGroupValue))) {
            mainTr.innerHTML = prevTr.innerHTML;
            return;
        }
        const data = new FormData();
        data.append("titleId", prevTitle.dataset.filter);
        data.append("title", title.value);
        if(filterNewGroup) {data.append("groupId", filterNewGroup.value);}
        switch(mainTr.dataset.type) {
            case "group":
            case "filter":
                fetch(adminpath + "/filter/" + mainTr.dataset.type + "-edit", {
                    method: "POST",
                    body: data
                }).then(data=>{
                    if(!data.ok) {
                        throw new Error();
                    }
                    if(filterGroup) {filterGroup.dataset.group = filterGroup.innerHTML = filterNewGroupValue;}
                    prevTitle.dataset.filter = prevTitle.innerHTML = title.value;
                    mainTr.innerHTML = prevTr.innerHTML;

                }).catch(()=>{
                    mainTr.closest('.box-body').prepend(createError("", "Error updating"));
                    setTimeout(()=>mainTr.closest('.box-body').querySelector('.fileError').remove(), 3000);
                });
                break;    
            default:
                mainTr.innerHTML = prevTr.innerHTML;
        }
    });
}

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