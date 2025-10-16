nameBackup = 'signupBackup';

function showhide(elem){
    console.log(elem);
    par = elem.parent.parent;
    div = par.querySelector('div.content');
    console.log(div);
    //this.src = '';
}

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

        if(await requestAPI('/api/user/signup', Form, Button)){
            localStorage.removeItem(nameBackup);
            Form.reset();
        }

    });
}