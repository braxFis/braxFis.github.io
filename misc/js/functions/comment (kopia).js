const commentInput = document.getElementById("comment-input");
const submitButton = document.getElementById("submit-comment");
const commentsList = document.getElementById("comments-list");

// A mock list of comments to display
let comments = [
    { author: 'Alice', text: 'This is a great post!', replies: [] },
    { author: 'Bob', text: 'I totally agree with Alice!', replies: [] }
];

// Function to render comments and replies
function renderComments() {
    commentsList.innerHTML = '';
    comments.forEach((comment, index) => {
        const commentDiv = document.createElement('div');
        commentDiv.classList.add('comment');

        // Display comment details
        commentDiv.innerHTML = `
                <div class="author">${comment.author}</div>
                <div class="text">${comment.text}</div>
                <button onclick="showReplyForm(${index})">Reply</button>
                <div id="replies-${index}" class="replies"></div>
            `;

        // Display replies for this comment
        comment.replies.forEach(reply => {
            const replyDiv = document.createElement('div');
            replyDiv.classList.add('reply');
            replyDiv.innerHTML = `<div class="author">${reply.author}</div><div class="text">${reply.text}</div>`;
            commentDiv.appendChild(replyDiv);
        });

        commentsList.appendChild(commentDiv);
    });
}

// Function to show the reply form for a specific comment
function showReplyForm(commentIndex) {
    const repliesDiv = document.getElementById(`replies-${commentIndex}`);

    const replyForm = document.createElement('div');
    replyForm.innerHTML = `
            <textarea id="reply-input-${commentIndex}" placeholder="Write a reply..." rows="2"></textarea>
            <button onclick="submitReply(${commentIndex})">Submit Reply</button>
        `;
    repliesDiv.appendChild(replyForm);
}

// Function to submit a new comment
submitButton.addEventListener('click', () => {
    const commentText = commentInput.value.trim();
    if (commentText) {
        const newComment = { author: 'User', text: commentText, replies: [] };
        comments.push(newComment);
        renderComments();
        commentInput.value = '';  // Clear the input
        submitButton.disabled = true;  // Disable button until the input is filled
    }
});

// Function to submit a reply to a specific comment
function submitReply(commentIndex) {
    const replyInput = document.getElementById(`reply-input-${commentIndex}`);
    const replyText = replyInput.value.trim();
    if (replyText) {
        comments[commentIndex].replies.push({ author: 'User', text: replyText });
        renderComments();
    }
}

// Enable or disable the submit button based on input
commentInput.addEventListener('input', () => {
    submitButton.disabled = commentInput.value.trim() === '';
});

// Initial render of comments
renderComments();