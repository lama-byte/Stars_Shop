<?php
session_start();

class Product {
    protected $id, $image, $name, $price, $category, $description, $stock;
    
    public function __construct($id, $name, $price, $category, $description = "", $stock = 0, $image="") {
        $this->id = $id; 
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->description = $description;
        $this->stock = $stock;
        $this->image = $image;
    }

    public function getId() { return $this->id; }
    public function getName() { return htmlspecialchars($this->name); }
    public function getPrice() { return floatval($this->price); }
    public function getCategory() { return htmlspecialchars($this->category); }
    public function getDescription() { return htmlspecialchars($this->description); }
    public function getStock() { return $this->stock; }

    
    public function reduceStock($quantity = 1) {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            return true;
        }
        return false;
    }
    public function getImage() {
        return htmlspecialchars($this->image);
    }
}


class CrochetProduct extends Product {
    
    public function __construct($id, $name, $price, $description, $stock, $image) {
        parent::__construct($id, $name, $price, "crochet", $description, $stock, $image);
    }
    
}

class AccessoryProduct extends Product {
    
    public function __construct($id, $name, $price, $description, $stock, $image) {
        parent::__construct($id, $name, $price, "accessories", $description, $stock, $image);
    }
    
}

class ShoppingCart {
    private $items = [];
    public function addItem($productId, $quantity = 1) {
        if (isset($this->items[$productId])) {
            $this->items[$productId] += $quantity;
        } else {
            $this->items[$productId] = $quantity;
        }
        return true;
    }
    
    public function removeItem($productId) {
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
            return true;
        }
        return false;
    }
    
    public function updateQuantity($productId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($productId);
        } else {
            $this->items[$productId] = $quantity;
            return true;
        }
    }
    
    public function getItems() { return $this->items; }
    public function getItemCount() { return count($this->items); }
    public function getTotalQuantity() {
        return array_sum($this->items);
    }
    
    public function getTotal($products, $taxRate = 0.15) {
        $subtotal = 0;
        foreach ($this->items as $productId => $quantity) {
            if (isset($products[$productId])) {
                $subtotal += $products[$productId]->getPrice() * $quantity;
            }
        }
        $tax = $subtotal * $taxRate;
        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax
        ];
    }
    
    public function clear() { 
        $this->items = [];
        return true;
    }
}


$products = [
    1 => new AccessoryProduct(
        1,
        "Green Hooded Kitty Doll",
        60,
        "Cute handmade green crochet kitty keychain with hoodie.",
        10,
        "items/green_cat_keychain.jpg"
    ),

    2 => new AccessoryProduct(
        2,
        "Apple Charm",
        20,
        "Mini apple-shaped crochet keychain charm.",
        25,
        "items/apple_keychain.jpg"
    ),

    3 => new AccessoryProduct(
        3,
        "Red Hooded Kitty Doll",
        60,
        "Cute handmade red crochet kitty keychain with hoodie.",
        10,
        "items/red_cat_keychain.jpg"
    ),

    4 => new CrochetProduct(
        4,
        "Blue Button Headband",
        35,
        "Comfortable crochet headband with decorative buttons.",
        12,
        "items/blue_headband.jpg"
    ),

    5 => new CrochetProduct(
        5,
        "Rose Spiral Fingerless Gloves",
        55,
        "Warm spiral-pattern fingerless gloves.",
        8,
        "items/fingerless_gloves.jpg"
    ),

    6 => new CrochetProduct(
        6,
        "Blue Apple Beanie",
        70,
        "Cute and warm crochet beanie with apple-inspired design.",
        10,
        "items/blue_apple_hat.jpg"
    ),

    7 => new CrochetProduct(
        7,
        "Smily Frog Beanie",
        66,
        "Adorable frog-themed crochet beanie with smiling face.",
        10,
        "items/frog_beanie.jpg"
    ),

    8 => new CrochetProduct(
        8,
        "Boho Earthy Tone Hat",
        75,
        "A boho-style earthy-tone handmade crochet hat.",
        15,
        "items/funky_beanie.jpg"
    ),

    9 => new CrochetProduct(
        9,
        "Red Cozy Scarf",
        80,
        "Warm and cozy crochet scarf with a red apple theme.",
        10,
        "items/red_apple_scarf.jpg"
    ),

    10 => new CrochetProduct(
        10,
        "Red Button Headband",
        35,
        "Soft crochet headband with stylish red buttons.",
        12,
        "items/red_headband.jpg"
    ),

];  

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new ShoppingCart();
}

$cart = $_SESSION['cart'];


$users = [
    'customer@starsshop.com' => [
        'password' => 'User&123', 
        'name' => 'Star Customer',
        'address' => 'Riyadh, Saudi Arabia',
        'phone' => '+966 55 765 4321'
    ]
];


function searchProducts($query, $category = '') {
    global $products;
    $results = [];
    
    $query = strtolower(trim($query));
    
    foreach ($products as $product) {
        $matchSearch = empty($query) || 
                       strpos(strtolower($product->getName()), $query) !== false ||
                       strpos(strtolower($product->getDescription()), $query) !== false;
        
        $matchCategory = empty($category) || $product->getCategory() === $category;
        
        if ($matchSearch && $matchCategory) {
            $results[] = $product;
        }
    }
    
    return $results;
}


function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function formatPrice($price) {
    return '$' . number_format($price, 2);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


?>