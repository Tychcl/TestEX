//alert(window.location.hostname);
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('infoForm');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Отправка...';
        submitButton.disabled = true;
        try {
            const response = await fetch('/api/');
            alert(response.url);
        }catch (error) {
            console.error('Ошибка при отправке:', error);
        }
    });
});