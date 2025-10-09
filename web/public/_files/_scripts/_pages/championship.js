const nameBackup = 'chamBackup';
let studentCount = 1;
let awards;

function addStudent() {
    const container = document.getElementById('students');
    const newBlock = document.createElement('fieldset');
    newBlock.className = 'student';
    newBlock.innerHTML = `
        <div class="label">
            <p>Студент #${studentCount+1}</p>
            <img src="_files/_images/_forms/close.svg" onclick="removeStudent(this)">
        </div>
        <div class="form-group">
            <label class="form-label">ФИО</label>
            <input 
                id="fio"
                name="students[${studentCount}][fio]" 
                type="text" 
                class="form-input"
                placeholder="Фамилия Имя Отчество" required
                pattern="[А-Я]{1}[a-я]* [А-Я]{1}[a-я]* [А-Я]{1}[a-я]*">
        </div>
        <div class="form-group-mult">
            <div class="form-group.mult">
                <label for="role" class="form-label">Степень награды</label>
                <select
                    name="students[${studentCount}][award]"
                    id="role" 
                    class="form-input"
                    required>
                    <option value="">Выберите</option>
                    ${awards}
                </select>
            </div>
            <div class="file-input-wrapper">
                <label class="form-label">Документ</label>
                <button class="file-input-button" id="fileButtonText">Выберите файл</button>
                <input type="file" name="students[${studentCount}][file]" id="file" class="form-input" onchange="updateButtonText(this)" required accept=".pdf,.doc,.docx">
            </div>
        </div>`;
        studentCount++;
    container.appendChild(newBlock);
    localStorage.setItem('lastStudentCount', studentCount);
}

function removeStudent(button) {
    if (document.querySelectorAll('fieldset.student').length > 1) {
        button.closest('fieldset.student').remove();
        updateStudentIndexes();
        localStorage.setItem('lastStudentCount', studentCount);
    } else {
        alert('Должен остаться хотя бы один студент');
    }
}

function updateStudentIndexes() {
    const blocks = document.querySelectorAll('fieldset.student');
    studentCount = 0;
    blocks.forEach((block, index) => {
        block.querySelector('p').textContent = `Студент #${index + 1}`;
        const inputs = block.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
            }
        });
    });
    studentCount = blocks.length;
}

function updateButtonText(input) {
    const button = input.parentNode.querySelector('button');
    if (input.files.length > 0) {
        button.textContent = input.files[0].name;
    } else {
        button.textContent = 'Выберите файл';
    }
}

async function getawards() {
    try{
        const response = await fetch('/api/event/award', {
            method: 'GET',
            credentials: 'same-origin'
        });
        r = await response.json();
        if(response.status == 401){
            location.reload()
        }
        if(!response.ok){
            alert('Ошибка при отправке\nПодробнее в консоли');
            console.log(r['error']);
        }
        option = '';
        r['list'].forEach((e) => {
            option += `<option value="${e['Id']}">${e['Name']}</option>`
        });
        return option;
    }catch(error){
        alert('Ошибка при отправке\nПодробнее в консоли');
        console.log(error);
    }
}

async function init() {
    awards = await getawards();
    const Form = document.querySelector('.form');
    const Button = Form.querySelector('.form-button');
    console.log("aboba")
    lastStudentCount = localStorage.getItem('lastStudentCount');
    while(document.querySelectorAll('fieldset.student').length != lastStudentCount){
        addStudent()
    }

    LoadForm(Form, nameBackup);

    Form.addEventListener('input', function() {
        SaveForm(Form, nameBackup);
    });

    Form.addEventListener('submit', async function(e) {
        e.preventDefault();

        clearError(Button);
        localStorage.removeItem(nameBackup);

        const inputs = document.querySelectorAll('.form-input');

        inputs.forEach((e) => {
            if(e.id != 'tfio' && !e.value.trim()){
                showError(e, 'Неправильное заполнение');
                return;
            } else {
                clearError(e);
            }
        });
        
        try {
            const formData = new FormData(Form);
            const data = Object.fromEntries(formData.entries());
            const response = await fetch('/api/event/add', {
                method: 'POST',
                
                credentials: 'same-origin',
                body: formData
            });
            console.log(await response.json());
            return;
            if(!response.ok){
                r = await response.json();
                console.log(r);
                showError(Form, r['error'])
                return;
            }

            

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