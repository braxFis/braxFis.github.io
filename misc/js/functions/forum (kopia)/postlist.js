// Example posts array
const posts = [
    { id: 1, title: 'Post 1', content: 'This is the content of post 1', category: 'General' },
    { id: 2, title: 'Post 2', content: 'This is the content of post 2', category: 'Technology' },
    { id: 3, title: 'Post 3', content: 'This is the content of post 3', category: 'Sports' },
];

const selectedCategory = ''; // Change this to filter posts by category (e.g., 'Technology')

const postListContainer = document.getElementById('post-list-container');

// Function to render posts
function renderPosts(posts, selectedCategory) {
    // Filter posts based on selected category
    const filteredPosts = selectedCategory
        ? posts.filter(post => post.category === selectedCategory)
        : posts;

    // Clear the container before rendering
    postListContainer.innerHTML = '';

    if (filteredPosts.length > 0) {
        // Render filtered posts
        filteredPosts.forEach(post => {
            const postElement = document.createElement('div');
            postElement.classList.add('post');

            const titleElement = document.createElement('h3');
            titleElement.classList.add('post-title');
            titleElement.textContent = post.title;

            const contentElement = document.createElement('p');
            contentElement.classList.add('post-content');
            contentElement.textContent = post.content;

            const categoryElement = document.createElement('p');
            categoryElement.classList.add('post-category');
            categoryElement.textContent = `Category: ${post.category}`;

            postElement.appendChild(titleElement);
            postElement.appendChild(contentElement);
            postElement.appendChild(categoryElement);

            postListContainer.appendChild(postElement);
        });
    } else {
        // Display no posts message
        const noPostsElement = document.createElement('p');
        noPostsElement.classList.add('no-posts');
        noPostsElement.textContent = 'No posts available in this category. Add a new post!';
        postListContainer.appendChild(noPostsElement);
    }
}

// Call the render function to display posts
renderPosts(posts, selectedCategory);