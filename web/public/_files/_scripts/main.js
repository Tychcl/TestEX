async function infoadd(){
    try{
        const response = await fetch('/web/event/info/add', {
                method: 'GET',
                credentials: 'same-origin'
            });
        if(!response.ok){
            alert('Ошибка ответа\nПодробнее в консоли');
            console.log(await response.json());
            return;
        }
        data = await response.json();
        const page = document.getElementById('page');
        page.innerHTML = data['html'];
        
    }catch(error){
        console.log(error);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    
});