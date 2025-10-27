<?php

namespace app\templates;

class Tabulate {
    protected array $tabs = [];

    public function addTab(string $label, string $content): void {
        $this->tabs[$label] = $content;
    }

    public function renderTabs(): string {
        $id = uniqid('tabs_');
        $html = "<div class='tabs' id='{$id}'>";
        
        // Flikrubriker
        $html .= "<div class='tab-buttons'>";
        $first = true;
        foreach ($this->tabs as $label => $content) {
            $active = $first ? 'active' : '';
            $html .= "<button class='tab-btn {$active}' data-tab='{$id}_{$label}'>$label</button>";
            $first = false;
        }
        $html .= "</div>";
        
        // Flikinnehåll
        $first = true;
        foreach ($this->tabs as $label => $content) {
            $active = $first ? 'active' : '';
            $html .= "<div class='tab-content {$active}' id='{$id}_{$label}'>{$content}</div>";
            $first = false;
        }

        $html .= "</div>";

        return $html;
    }
}