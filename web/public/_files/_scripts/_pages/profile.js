nameBackup = 'profileBackup';

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

        if(!check(['tid'])){
            return;
        }

        if(await requestAPI('/api/skill/add', Form, Button)){
            localStorage.removeItem(nameBackup);
        }

    });
}