
let studentCount = 1;

function addStudent() {
    studentCount++;
    const container = document.getElementById('students');
    const newBlock = document.createElement('fieldset');
    newBlock.className = 'student';
    newBlock.innerHTML = `
        <p>Студент #${studentCount}</p>
        <button class="delete-student" onclick="removeStudent(this)">Удалить</button>
        <div class="form-group">
            <label class="form-label">ФИО</label>
            <input 
                name="student[${studentCount}][fio]" 
                type="text" 
                class="form-input"
                placeholder="Фамилия Имя Отчество" required>
        </div>
        <div class="form-group-mult">
            <div class="form-group.mult">
                <label for="role" class="form-label">Степень награды</label>
                <select
                    name="student[${studentCount}][award]"
                    id="role" 
                    class="form-input"
                    required>
                    <option value="">Выберите</option>
                    <?= $roles ?>
                </select>
            </div>
            <div class="file-input-wrapper">
                <label class="form-label">Документ</label>
                <button class="file-input-button" id="fileButtonText">Выберите файл</button>
                <input type="file" name="student[${studentCount}][file]" id="fileInput" onchange="updateButtonText(this)" required>
                <script>
                    function updateButtonText(input) {
                        const button = document.getElementById('fileButtonText');
                        if (input.files.length > 0) {
                            button.textContent = input.files[0].name;
                        } else {
                            button.textContent = 'Выберите файл';
                        }
                    }
                </script>
            </div>
        </div>`;
    container.appendChild(newBlock);
}

function removeStudent(button) {
    if (document.querySelectorAll('fieldset.student').length > 1) {
        button.closest('fieldset.student').remove();
        updateStudentIndexes();
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
    console.log(input.parentNode);
    const button = input.parentNode.querySelector('button');
    if (input.files.length > 0) {
        button.textContent = input.files[0].name;
    } else {
        button.textContent = 'Выберите файл';
    }
}

function init() {
    const Form = document.querySelector('.form');
    const Button = Form.querySelector('.form-button');
    console.log("aboba")

    Form.addEventListener('submit', async function(e) {
        e.preventDefault();

        clearError(Button);

        const form = e.target;
        const name = document.getElementById('name');
        const start = document.getElementById('start');
        const end = document.getElementById('end');
        const level = document.getElementById('level');

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