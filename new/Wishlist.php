<?php

// ----------------------------
// Wishlist
// ----------------------------
class Wishlist implements FeatureInterface {
    private array $products = [];
    private array $wishlist = [];

    public function __construct(array $products) {
        $this->products = $products;
    }

    public function add(mixed $productId) {
        $product = array_filter($this->products, fn($p) => $p['id'] === $productId);
        if ($product) {
            $this->wishlist[$productId] = array_values($product)[0];
        }
    }

    public function remove(mixed $productId) {
        unset($this->wishlist[$productId]);
    }

    public function list(): array {
        return array_values($this->wishlist);
    }

    public function render(): string {
        $html = "<ul class='wishlist'>";
        foreach ($this->wishlist as $product) {
            $html .= "<li>
                        <h4>{$product['name']}</h4>
                        <p>{$product['description']}</p>
                        <p>Price: \${$product['price']}</p>
                        " . ($product['image'] ? "<img src='{$product['image']}' width='100'>" : "") . "
                        <button onclick='removeFromWishlist({$product['id']})'>🗑 Remove</button>
                      </li>";
        }
        $html .= "</ul>";
        return $html;
    }
}