
let studentCount = 1;

function addStudent() {
    const container = document.getElementById('students-container');
    const newBlock = document.createElement('div');
    newBlock.className = 'student-block';
    newBlock.innerHTML = `
        <h4>Студент #${studentCount + 1}</h4>
        <label>ФИО студента: <input type="text" name="students[${studentCount}][fio]" required></label>
        <label>Степень награды: 
            <select name="students[${studentCount}][award_degree]" required>
                <option value="1">1 место</option>
                <option value="2">2 место</option>
                <option value="3">3 место</option>
            </select>
        </label>
        <label>Документ студента: <input type="file" name="student_documents[${studentCount}]" required></label>
        <button type="button" onclick="removeStudent(this)">Удалить</button>
    `;
    container.appendChild(newBlock);
    studentCount++;
}

function removeStudent(button) {
    if (document.querySelectorAll('.student-block').length > 1) {
        button.closest('.student-block').remove();
        // Обновляем индексы после удаления
        updateStudentIndexes();
    } else {
        alert('Должен остаться хотя бы один студент');
    }
}

function updateStudentIndexes() {
    const blocks = document.querySelectorAll('.student-block');
    studentCount = 0;
    blocks.forEach((block, index) => {
        block.querySelector('h4').textContent = `Студент #${index + 1}`;
        // Обновляем индексы в полях ввода
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
document.addEventListener('DOMContentLoaded', function() {

    const chamForm = document.querySelector('.cham-form');
    const chamButton = document.querySelector('.button');
    
    // Валидация формы
    chamForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const formData = new FormData(this);
            //const data = Object.fromEntries(formData.entries());
            //console.log(data)
            const response = await fetch('/api/user/signin', {
                method: 'POST',
                body: formData
            });
            if(!response.ok){
                alert('Ошибка ответа\nПодробнее в консоли');
                console.log(await response.json());
                return;
            }
            
            //window.location.reload(true);

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