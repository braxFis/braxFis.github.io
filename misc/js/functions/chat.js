const messageBox = document.getElementById("message-input");
const sendButton = document.getElementById("send-button");
const messagesDiv = document.getElementById("messages");
const emojiButton = document.getElementById("emoji-button");
const fileInput = document.getElementById("file-input");

// Connect to WebSocket server
const socket = new WebSocket('ws://localhost:8080'); // Replace with your WebSocket server URL

socket.onopen = () => {
    console.log("Connected to the WebSocket server");
};

socket.onmessage = (event) => {
    const receivedMessage = event.data;
    displayMessage(receivedMessage, 'other'); // Display incoming messages
};

socket.onclose = () => {
    console.log("Disconnected from the WebSocket server");
};

// Display a message in the chat window
function displayMessage(text, sender = 'self') {
    const messageDiv = document.createElement("div");
    messageDiv.classList.add("message", sender);

    const messageText = document.createElement("div");
    messageText.classList.add("message-text");

    // If the message is a file, handle it differently
    if (text.startsWith("file:")) {
        const fileData = JSON.parse(text.substring(5));
        if (fileData.type.startsWith('image/')) {
            // Display images
            messageText.innerHTML = `<img src="${fileData.url}" alt="file" style="max-width: 100%; height: auto; border-radius: 8px;" />`;
        } else {
            // Display file as a link
            messageText.innerHTML = `<a href="${fileData.url}" target="_blank">Click to view file: ${fileData.name}</a>`;
        }
    } else {
        messageText.innerHTML = handleMentions(text); // Handle mentions and display text
    }

    messageDiv.appendChild(messageText);
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight; // Scroll to the bottom
}

// Process mentions (e.g., @username)
function handleMentions(text) {
    return text.replace(/@\w+/g, (match) => {
        return `<span class="mention">${match}</span>`;
    });
}

// Handle Send button click
sendButton.addEventListener("click", () => {
    const messageText = messageBox.value.trim();

    if (messageText) {
        socket.send(messageText); // Send message via WebSocket
        displayMessage(messageText, 'self'); // Display user message
        messageBox.value = ""; // Clear message input
        sendButton.disabled = true;
    }
});

// Enable or disable the Send button based on input
messageBox.addEventListener("input", () => {
    sendButton.disabled = messageBox.value.trim() === "";
});

// Emoji Picker
emojiButton.addEventListener("click", function() {
    const emojiPicker = new EmojiButton();
    emojiPicker.on('emoji', (emoji) => {
        messageBox.value += emoji;
        sendButton.disabled = false;
    });

    emojiPicker.togglePicker(emojiButton);
});

// Handle file input change (file selection)
fileInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onloadend = () => {
            const fileData = {
                name: file.name,
                type: file.type,
                size: file.size,
                url: reader.result, // Base64 file URL
            };
            socket.send(JSON.stringify({ type: "file", data: fileData })); // Send the file data through WebSocket
            displayMessage(`Sent file: ${file.name}`, 'self'); // Display file sent message
        };
        reader.readAsDataURL(file); // Read the file as a Base64 URL
    }
});