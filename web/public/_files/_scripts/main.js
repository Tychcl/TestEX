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

async function requestAPI(api_url, form, Button, method = 'POST'){
    try {
        const formData = new FormData(form);
        const response = await fetch(api_url, {
            method: method,
            credentials: 'same-origin',
            body: formData
        });
        
        if(!response.ok){
            const r = await response.json();
            console.log(r['error']);
            showError(Button, r['error'])
            return false;
        } else {
            showError(Button, "Успешно");
            return response; 
        }
    } catch (error) {
        console.log(error);
        showError(Button, 'Сетевая ошибка');
        return false;
    }
}

function updateButtonText(input) {
    const button = input.parentNode.querySelector('button');
    if (input.files.length > 0) {
        button.textContent = input.files[0].name;
    } else {
        button.textContent = 'Выберите файл';
    }
}

//кнопки

async function infoadd(){
    await loadPage('/web/event/info/add');
}

async function eventadd(){
    await loadPage('/web/event/add');
}

async function catadd(){
    await loadPage('/web/category/add');
}

async function skilladd(){
    await loadPage('/web/skill/add');
}

async function signout() {
    try{
        const response = await fetch('/api/user/signout', {
            method: 'GET'
        });
        if(response.ok){
            window.location.reload(true);
        }else{
            console.log(r['error']);
        }
    }catch (error) {
        console.log(error);
    }
}

async function profile(){
    await loadPage('/web/user/profile');
}

async function useradd(){
    await loadPage('/web/user/add');
}