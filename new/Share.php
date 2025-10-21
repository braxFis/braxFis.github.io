<?php

// ----------------------------
// Share
// ----------------------------
class Share implements FeatureInterface {
    private array $sharedItems = [];

    public function add(mixed $item) {
        $this->sharedItems[] = $item;
    }

    public function remove(mixed $item) {
        $this->sharedItems = array_filter($this->sharedItems, fn($i) => $i !== $item);
    }

    public function list(): array {
        return $this->sharedItems;
    }

    public function render(): string {
        $html = "<ul class='share'>";
        foreach ($this->sharedItems as $item) {
            $html .= "<li>{$item} 
                        <a href='https://twitter.com/intent/tweet?text=" . urlencode($item) . "' target='_blank'>Twitter</a> | 
                        <a href='https://www.facebook.com/sharer/sharer.php?u=" . urlencode($item) . "' target='_blank'>Facebook</a>
                      </li>";
        }
        $html .= "</ul>";
        return $html;
    }
}
