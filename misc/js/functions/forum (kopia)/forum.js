// Get references to DOM elements
const postTitleInput = document.getElementById('post-title');
const postContentInput = document.getElementById('post-content');
const submitPostButton = document.getElementById('submit-post');
const postList = document.getElementById('post-list');
const categorySelect = document.getElementById('category-select');

let posts = JSON.parse(localStorage.getItem('forumPosts')) || [];

// Load posts on page load
function loadPosts() {
    postList.innerHTML = ''; // Clear the existing posts
    const selectedCategory = categorySelect.value;

    posts
        .filter(post => !selectedCategory || post.category === selectedCategory)
        .forEach(post => {
            const postItem = document.createElement('li');
            postItem.classList.add('post-item');
            postItem.innerHTML = `
                        <div class="post-category">${post.category}</div>
                        <h3>${post.title}</h3>
                        <p>${post.content}</p>
                    `;
            postList.appendChild(postItem);
        });
}

// Handle submitting a new post
submitPostButton.addEventListener('click', () => {
    const title = postTitleInput.value.trim();
    const content = postContentInput.value.trim();
    const category = categorySelect.value;

    if (title && content) {
        const newPost = { title, content, category };
        posts = [newPost, ...posts]; // Add new post at the beginning
        localStorage.setItem('forumPosts', JSON.stringify(posts)); // Save to localStorage

        // Clear inputs and reload posts
        postTitleInput.value = '';
        postContentInput.value = '';
        loadPosts();
    }
});

// Handle category filter change
categorySelect.addEventListener('change', loadPosts);

// Load posts on page load
loadPosts();