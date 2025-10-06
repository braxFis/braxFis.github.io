document.getElementById('newsletter-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const submitButton = document.getElementById('submit-button');
    const messageElement = document.getElementById('message');

    // Disable the submit button
    submitButton.disabled = true;
    submitButton.innerText = 'Submitting...';

    // Send the email to the backend
    try {
        const response = await fetch('/api/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email }),
        });

        const data = await response.json();

        if (response.ok) {
            messageElement.textContent = 'You have successfully subscribed!';
            messageElement.style.color = '#4CAF50';
        } else {
            messageElement.textContent = data.message || 'Something went wrong.';
            messageElement.style.color = 'red';
        }
    } catch (error) {
        messageElement.textContent = 'Error: Could not connect to the server.';
        messageElement.style.color = 'red';
    } finally {
        // Enable the submit button again
        submitButton.disabled = false;
        submitButton.innerText = 'Submit';
    }
});