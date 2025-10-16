nameBackup = 'profileBackup';

function showhide(elem){
    const field = elem.closest('fieldset');
    const div = field.querySelector('div.content');
    if(div.id == "hide"){
        div.id = "show";
        elem.src = "_files/_images/_forms/closeac.svg"
    }else{
        div.id = "hide";
        elem.src = "_files/_images/_forms/openac.svg"
    }
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

        if(!check()){
            return;
        }

        const r = await requestAPI('/api/event/info/getZip', Form, Button);
        if(r){
            try {
                const b = await r.blob();
                const url = window.URL.createObjectURL(b);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                //const contentDisposition = r.headers.get('Content-Disposition');
                let filename = 'event.zip';
                //if (contentDisposition) {
                //    const filenameMatch = contentDisposition.match(/filename="?(.+)"?/i);
                //    if (filenameMatch && filenameMatch.length > 1) {
                //        filename = filenameMatch[1];
                //    }
                //}

                a.download = filename;     
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                localStorage.removeItem(nameBackup);
                Form.reset();
            } catch (error) {
                console.error('Error creating blob:', error);
                showError(Button, 'Ошибка при создании файла');
            }
        }

    });
}