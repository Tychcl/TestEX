nameBackup = 'infoBackup';

function init() {
    const Form = document.querySelector('.form');
    const Button = Form.querySelector('.form-button');
    console.log("aboba")

    LoadForm(Form, nameBackup);

    Form.addEventListener('input', function() {
        SaveForm(Form, nameBackup);
    });

    Form.addEventListener('submit', async function(e) {
        e.preventDefault();

        clearError(Button);

        if(!check(['end'])){
            return;
        }

        const start = document.getElementById('start');
        const end = document.getElementById('end');
        const time_start = new Date(start.value);
        const time_end = new Date(end.value);

        if(!end.value.trim() && time_end < time_start){
            showError(end, 'Должно быть > или = начала');
            return;
        } else {
            clearError(level);
        }
        
        if(await requestAPI('/api/event/info/add', Form, Button)){
            localStorage.removeItem(nameBackup);
        }

    });
    
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
}