document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('fgCustomForm');
    const data = {};
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        handleForm();
    });

    async function handleForm() {
        hideMessage();
    
        // Get all inputs
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.name ? data[input.name] = input.value : '';
        });
        data.action = form.action;

        // Send data to validator.php
        try {
            const res = await fetch('/wp-content/plugins/form-generator/includes/validator.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await res.json();

            if (!result.success) {
                Object.values(result.errors).forEach(error => {
                    showMessage('red', error);
                });
                
            } else {
                showMessage('green', 'Thank you for submitting the form');
                form.reset();
            }
        } catch (err) {
            console.error('Network error:', err);
        }
    }

    // Show message
    const showMessage = (color, message) => {
        const infoMessage = document.createElement('p');
        infoMessage.id = 'message'
        infoMessage.style.color = color;
        infoMessage.textContent = message;
        form.appendChild(infoMessage);
    }

    const hideMessage = () => {
        const successMessage = document.getElementById('message');
        successMessage ? successMessage.remove() : '';
    }
});