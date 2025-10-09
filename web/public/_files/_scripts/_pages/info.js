const nameBackup = 'infoBackup';

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

        localStorage.removeItem(nameBackup);
        clearError(Button);

        const form = e.target;
        const name = document.getElementById('name');
        const start = document.getElementById('start');
        const end = document.getElementById('end');
        const level = document.getElementById('level');

        // Валидация логина
        if (!name.value.trim()) {
            showError(name, 'Введите наименования');
            return;
        } else {
            clearError(name);
        }

        if (!start.value.trim()) {
            showError(start, 'Введите дату начала');
            return;
        } else {
            clearError(start);
        }

        if (!end.value.trim()) {
            showError(end, 'Введите дату окончания');
            return;
        } else {
            clearError(end);
        }

        if (!level.value.trim()) {
            showError(level, 'Выберите уровень проведения');
            return;
        } else {
            clearError(level);
        }
        
        const time_start = new Date(start.value);
        const time_end = new Date(end.value);

        if(time_end < time_start){
            showError(end, 'Должно быть > или = начала');
            return;
        } else {
            clearError(level);
        }

        try {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            data['start'] = time_start.getTime();
            data['end'] = time_end.getTime();
            const response = await fetch('/api/event/info/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
            if(!response.ok){
                r = await response.json();
                console.log(r);
                showError(Form, r['error'])
                return;
            }

            showError(Button, "Чемпионат успешно добавлен")

        }catch (error) {
            alert('Ошибка при отправке\nПодробнее в консоли');
            console.log(error);
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