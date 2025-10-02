document.addEventListener('DOMContentLoaded', function() {
    const authForm = document.querySelector('.auth-form');
    const authButton = document.querySelector('.auth-button');
    
    // Функция переключения видимости пароля
    window.togglePassword = function() {
        event.preventDefault();
        const passwordInput = document.getElementById('password');
        const passwordControl = document.querySelector('.password-control');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordControl.classList.add('view');
        } else {
            passwordInput.type = 'password';
            passwordControl.classList.remove('view');
        }
    };
    
    // Валидация формы
    authForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = document.getElementById('auth-form');
        const login = document.getElementById('login');
        const password = document.getElementById('password');
        
        // Валидация логина
        if (!login.value.trim()) {
            showError(login, 'Введите логин');
            return;
        } else {
            clearError(login);
        }
        
        // Валидация пароля
        if (!password.value.trim()) {
            showError(password, 'Введите пароль');
            return;
        } else {
            clearError(password);
        }
        
        try {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const response = await fetch('/api/user/signin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
            if(!response.ok){
                alert('Ошибка ответа\nПодробнее в консоли');
                console.log(await response.json());
                return;
            }
            
            window.location.reload(true);

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
});