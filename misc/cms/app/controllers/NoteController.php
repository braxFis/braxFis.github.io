<?php

// ----------------------------
// Notes
// ----------------------------
class Notes implements FeatureInterface {
    private array $notes = [];

    public function add(mixed $note) {
        $this->notes[] = $note;
    }

    public function remove(mixed $note) {
        $this->notes = array_filter($this->notes, fn($n) => $n !== $note);
    }

    public function list(): array {
        return $this->notes;
    }

    public function render(): string {
        $html = "<ul class='notes'>";
        foreach ($this->notes as $index => $note) {
            $html .= "<li>
                        {$note} 
                        <button onclick='deleteNote({$index})'>Delete</button>
                      </li>";
        }
        $html .= "</ul>";
        return $html;
    }
}
