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
        if(response.status == 401){
            location.reload()
        }
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

function SaveForm(form, nameBackup){
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    localStorage.setItem(nameBackup, JSON.stringify(data));
}

function LoadForm(form, nameBackup){
    const savedData = localStorage.getItem(nameBackup);
    if (savedData) {
        const data = JSON.parse(savedData);
        Object.keys(data).forEach(key => {
            const element = form.elements[key];
            console.log();
            if (element && element.type != 'file') {
                if (element.type === 'checkbox') {
                    element.checked = data[key] === 'on';
                } else {
                    element.value = data[key];
                }
            }
        });
    }
}

function check(IdExceptions = []){
    const inputs = document.querySelectorAll('.form-input');
    value = true;
    inputs.forEach((e) => {
        console.log(e.id);
        if(!IdExceptions.includes(e.id) && !e.value.trim()){
            value = false;
            showError(e, 'Неправильное заполнение');
        } else {
            clearError(e);
        }
    });
    return value;
}

function showError(input, message) {
    clearError(input);
    const errorElement = document.createElement('span');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    input.parentNode.appendChild(errorElement);
    input.classList.add('invalid');
}
    
function clearError(input) {
    const errorElement = input.parentNode.querySelector('.error-message');
    if (errorElement) {
        errorElement.remove();
    }
    input.classList.remove('invalid');
}