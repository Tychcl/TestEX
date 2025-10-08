const pageLoadEvent = new CustomEvent('pageLoaded', {
            detail: {
                target: this,
                url: this.href
            }
        });

function loadScript(src, element) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        element.appendChild(script);
    });
}

async function loadPage(route) {
    try{
        const response = await fetch(route , {
                method: 'GET',
                credentials: 'same-origin'
            });
        if(!response.ok){
            alert('Ошибка ответа\nПодробнее в консоли');
            console.log(await response.json());
            return;
        }
        data = await response.json();
        const page = document.getElementById('page');
        page.innerHTML = data['html'];
        loadScript(data['js'], page).then(() => {init();});
        //await init();
        //document.dispatchEvent(pageLoadEvent); 
    }catch(error){
        console.log(error);
    }
}

async function infoadd(){
    await loadPage('/web/event/info/add')
}

async function eventadd(){
    await loadPage('/web/event/add')
}

document.addEventListener('pageLoaded', function() {
    console.log("main")
});