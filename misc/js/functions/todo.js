// Initialize todo list array
const todos = [];

// Select the DOM elements
const todoInput = document.getElementById('todo-input');
const addButton = document.getElementById('add-todo');
const todoList = document.getElementById('todo-list');

// Handle adding new todo
addButton.addEventListener('click', () => {
    const newTodo = todoInput.value.trim();
    if (newTodo) {
        todos.push(newTodo);
        renderTodos();
        todoInput.value = '';
    }
});

// Handle removing a todo
function removeTodo(index) {
    todos.splice(index, 1);
    renderTodos();
}

// Render the todo list
function renderTodos() {
    todoList.innerHTML = '';
    todos.forEach((todo, index) => {
        const li = document.createElement('li');
        li.innerHTML = `
          ${todo} <button onclick="removeTodo(${index})">Delete</button>
        `;
        todoList.appendChild(li);
    });
}