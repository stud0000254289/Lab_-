<?php

// Класс Product
class Product {
    protected $id;
    protected $name;
    protected $description;
    protected $price;
    protected $quantity;
    protected $category;

    public function __construct($id, $name, $description, $price, $quantity, $category) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->category = $category;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getDescription() {
        return $this->description;
    }

    public function reduceQuantity($amount) {
        if ($this->quantity >= $amount) {
            $this->quantity -= $amount;
        } else {
            throw new Exception("Not enough quantity in stock");
        }
    }
}

// Наследник DigitalProduct
class DigitalProduct extends Product {
    private $fileSize;
    private $downloadLink;

    public function __construct($id, $name, $description, $price, $quantity, $category, $fileSize, $downloadLink) {
        parent::__construct($id, $name, $description, $price, $quantity, $category);
        $this->fileSize = $fileSize;
        $this->downloadLink = $downloadLink;
    }

    public function generateDownloadLink() {
        return $this->downloadLink;
    }
}

// Наследник PhysicalProduct
class PhysicalProduct extends Product {
    private $weight;
    private $dimensions;

    public function __construct($id, $name, $description, $price, $quantity, $category, $weight, $dimensions) {
        parent::__construct($id, $name, $description, $price, $quantity, $category);
        $this->weight = $weight;
        $this->dimensions = $dimensions;
    }

    public function calculateShippingCost() {
        return $this->weight * 10; // Пример расчета стоимости доставки
    }
}

// Класс Cart
class Cart {
    protected $items = [];
    protected $totalPrice = 0;
    protected $userId;

    public function __construct($userId) {
        $this->userId = $userId;
    }

    public function addItem(Product $product, $quantity) {
        $this->items[] = ['product' => $product, 'quantity' => $quantity];
        $product->reduceQuantity($quantity);
        $this->calculateTotal();
    }

    public function removeItem($productId) {
        foreach ($this->items as $index => $item) {
            if ($item['product']->id == $productId) {
                unset($this->items[$index]);
                break;
            }
        }
        $this->calculateTotal();
    }

    public function calculateTotal() {
        $this->totalPrice = 0;
        foreach ($this->items as $item) {
            $this->totalPrice += $item['product']->getPrice() * $item['quantity'];
        }
    }

    public function clearCart() {
        $this->items = [];
        $this->totalPrice = 0;
    }
}

// Наследник GuestCart
class GuestCart extends Cart {
    public function convertToUserCart($userId) {
        $this->userId = $userId;
        // Пример преобразования корзины гостя в корзину пользователя
    }
}

// Класс User
class User {
    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected $role;

    public function __construct($id, $name, $email, $password, $role = 'customer') {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function login($email, $password) {
        // Реализация логики авторизации
    }

    public function register($name, $email, $password) {
        // Реализация логики регистрации
    }

    public function logout() {
        // Реализация выхода из системы
    }
}

// Наследник AdminUser
class AdminUser extends User {
    private $permissions;

    public function __construct($id, $name, $email, $password, $permissions = []) {
        parent::__construct($id, $name, $email, $password, 'admin');
        $this->permissions = $permissions;
    }

    public function banUser($userId) {
        // Логика блокировки пользователя
    }

    public function manageProducts() {
        // Логика управления продуктами
    }
}

// Наследник CustomerUser
class CustomerUser extends User {
    private $wishlist = [];

    public function addToWishlist($productId) {
        $this->wishlist[] = $productId;
    }
}

// Класс Review
class Review {
    protected $id;
    protected $productId;
    protected $userId;
    protected $rating;
    protected $comment;
    protected $date;

    public function __construct($id, $productId, $userId, $rating, $comment) {
        $this->id = $id;
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s');
    }

    public function addReview() {
        // Логика добавления отзыва
    }

    public function editReview($reviewId, $newComment) {
        // Логика редактирования отзыва
    }

    public function deleteReview($reviewId) {
        // Логика удаления отзыва
    }
}

// Класс FeedbackForm
class FeedbackForm {
    protected $name;
    protected $email;
    protected $message;
    protected $submittedAt;

    public function __construct($name, $email, $message) {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->submittedAt = date('Y-m-d H:i:s');
    }

    public function submitFeedback() {
        // Логика отправки формы обратной связи
    }
}

?>
