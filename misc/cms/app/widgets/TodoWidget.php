<?php

namespace app\widgets;

use app\models\Todo;

class TodoWidget{
  public static function renderTodo(){
    return 
          '
          <div class="todo-container">
            <h1>Todo List</h1>
            <input type="text" id="todo-input" placeholder="Enter new task">
            <button id="add-todo">Add Todo</button>
            <ul id="todo-list" class="todo-list"></ul>
          </div>
          ';
  }
}