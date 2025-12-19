<?php
require_once __DIR__ . '/../vendor/autoload.php';
use OOP\Car;
echo "=== Пример 1: Класс Car ===\n\n";
$car = new Car("BMW", "X5", 2020, 50000);
echo $car->getInfo() . "\n\n";
$car->drive(150);
$car->drive(300);
echo "\nТекущий пробег: " . $car->getMileage() . " км\n";