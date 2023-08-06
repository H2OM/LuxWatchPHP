const options = document.querySelector('#currency');


options.style.cssText = "display: block;font-size: 12px;overflow: hidden;white-space: nowrap;text-transform: uppercase;font-weight: 400;color: #fff; background-color:black;border:none;";
document.querySelector('#currency').addEventListener('change',(e)=>{
    options.querySelector('.label').classList.remove('label');
    options.querySelectorAll('option').forEach(v=>{
        if(v.value == e.target.value) {
            v.classList.add('label'); 
            return;
        }
    });
    fetch('http://localhost/PROJECTS/LuxuryWathesPHP/app/controllers/POSTCOOKcurrency.php', {
        method: 'POST',
        body: JSON.stringify({curr: e.target.value})
    }).then(()=>{
        location.reload();
    });
    // window.location = 'app/controllers/CurrencyController.php/change?curr=' + e.target.value;
});