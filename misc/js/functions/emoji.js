const messageBox = document.getElementById('message-box');
const chatBox = document.getElementById('chat-box');
const emojiButton = document.getElementById('emoji-button');
const sendButton = document.getElementById('send-button');

// Emoji list for the user to select from
const emojis = ["😊", "😂", "😍", "😎", "😢", "👍", "🙌", "🎉", "🔥", "💖"];

// Function to display a new message in the chat box
function displayMessage(message, sender = 'user') {
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');
    if (sender === 'user') {
        messageElement.classList.add('user-message');
    } else {
        messageElement.classList.add('received-message');
    }
    messageElement.textContent = message;
    chatBox.appendChild(messageElement);
    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
}

// Function to show the emoji picker
function showEmojiPicker() {
    const emojiList = document.createElement('div');
    emojiList.style.position = 'absolute';
    emojiList.style.top = '70px';
    emojiList.style.backgroundColor = '#fff';
    emojiList.style.padding = '10px';
    emojiList.style.borderRadius = '8px';
    emojiList.style.border = '1px solid #ccc';
    emojiList.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
    emojiList.style.display = 'grid';
    emojiList.style.gridTemplateColumns = 'repeat(5, 1fr)';
    emojiList.style.gap = '5px';

    emojis.forEach(emoji => {
        const emojiDiv = document.createElement('div');
        emojiDiv.textContent = emoji;
        emojiDiv.style.fontSize = '24px';
        emojiDiv.style.cursor = 'pointer';
        emojiDiv.addEventListener('click', () => {
            messageBox.value += emoji;
            document.body.removeChild(emojiList); // Remove the emoji picker after selection
        });
        emojiList.appendChild(emojiDiv);
    });

    document.body.appendChild(emojiList);
}

// Handle emoji button click
emojiButton.addEventListener('click', () => {
    showEmojiPicker();
});

// Handle Send button click
sendButton.addEventListener('click', () => {
    const message = messageBox.value.trim();
    if (message) {
        displayMessage(message, 'user'); // Display user's message
        messageBox.value = ''; // Clear input box

        // Simulate a reply from another user (you can replace this with actual backend logic)
        setTimeout(() => {
            displayMessage("Got your message! 😊", 'received'); // Display reply
        }, 1000);
    }
});