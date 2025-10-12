document.addEventListener('DOMContentLoaded', function() {
    const Form = document.querySelector('.auth-form');
    const Button = Form.querySelector('.auth-button');
    
    // Валидация формы
    Form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        console.log(Form, new FormData(Form));

        if(!check()){
            return;
        }
        
        if(await requestAPI('/api/user/signin', Form, Button)){
            window.location.reload(true);
        }

    });

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
});