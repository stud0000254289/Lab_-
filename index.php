<?php

// Абстрактный товар
abstract class Product {
    protected $name;
    protected $price;

    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }

    // Абстрактный метод для подсчета финальной стоимости
    abstract public function calculateFinalPrice($quantity);

    // Метод для расчета дохода
    public function calculateIncome($quantity) {
        return $this->calculateFinalPrice($quantity);
    }
}

// Цифровой товар
class DigitalProduct extends Product {

    public function __construct($name, $price) {
        parent::__construct($name, $price);
    }

    // Финальная стоимость цифрового товара в 2 раза меньше
    public function calculateFinalPrice($quantity) {
        return ($this->price / 2) * $quantity;
    }
}

// Штучный физический товар
class PhysicalProduct extends Product {

    public function __construct($name, $price) {
        parent::__construct($name, $price);
    }

    // Финальная стоимость зависит от количества штук
    public function calculateFinalPrice($quantity) {
        return $this->price * $quantity;
    }
}

// Весовой товар
class WeightProduct extends Product {

    public function __construct($name, $pricePerKg) {
        parent::__construct($name, $pricePerKg);
    }

    // Финальная стоимость зависит от продаваемого количества в килограммах
    public function calculateFinalPrice($weightKg) {
        return $this->price * $weightKg;
    }
}

// Пример использования
$digitalProduct = new DigitalProduct("E-book", 50);
$physicalProduct = new PhysicalProduct("Laptop", 500);
$weightProduct = new WeightProduct("Apples", 50);

echo "Digital Product Final Price (for 3 units): " . $digitalProduct->calculateFinalPrice(3) . " руб.\n <br>";
echo "Physical Product Final Price (for 2 units): " . $physicalProduct->calculateFinalPrice(2) . " руб.\n <br>";
echo "Weight Product Final Price (for 5 kg): " . $weightProduct->calculateFinalPrice(5) . " руб.\n";

?>
