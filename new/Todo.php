<?php

// ----------------------------
// Todo
// ----------------------------
class Todo implements FeatureInterface {
    private array $tasks = [];

    public function add(mixed $task) {
        $this->tasks[] = $task;
    }

    public function remove(mixed $task) {
        $this->tasks = array_filter($this->tasks, fn($t) => $t !== $task);
    }

    public function list(): array {
        return $this->tasks;
    }

    public function render(): string {
        $html = "<ul class='todo'>";
        foreach ($this->tasks as $index => $task) {
            $html .= "<li>{$task} <button onclick='removeTodo({$index})'>Delete</button></li>";
        }
        $html .= "</ul>";
        return $html;
    }
}