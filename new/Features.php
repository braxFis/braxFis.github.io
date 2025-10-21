<?php

interface FeatureInterface {
    public function add(mixed $item);
    public function remove(mixed $item);
    public function list(): array;
    public function render(): string; // Ny metod för HTML-rendering
}