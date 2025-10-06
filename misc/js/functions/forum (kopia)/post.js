const submitButton = document.getElementById('submit-post');
const titleInput = document.getElementById('post-title');
const contentInput = document.getElementById('post-content');
const categorySelect = document.getElementById('post-category');

// Function to handle adding a post
function handleAddPost() {
    const title = titleInput.value.trim();
    const content = contentInput.value.trim();
    const category = categorySelect.value;

    if (title && content) {
        const newPost = {
            id: Date.now(),
            title,
            content,
            category,
        };

        // Here, you can add the post to your post array or localStorage
        console.log(newPost); // Example: logging the new post object

        // Clear the form fields
        titleInput.value = '';
        contentInput.value = '';
        categorySelect.value = 'General'; // Reset category to default
    } else {
        alert('Please fill out both the title and content fields.');
    }
}

// Event listener for the submit button
submitButton.addEventListener('click', handleAddPost);