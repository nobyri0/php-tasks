<?php
require_once __DIR__ . '/../vendor/autoload.php';
use OOP\Rectangle;
echo "=== Пример 2: Класс Rectangle ===\n\n";
try {
    $rectangle = new Rectangle(10, 5);
    echo $rectangle->getInfo() . "\n";
    echo "Площадь: " . $rectangle->getArea() . " кв. единиц\n";
    echo "Периметр: " . $rectangle->getPerimeter() . " единиц\n\n";  
    $square = new Rectangle(7, 7);
    echo $square->getInfo() . "\n";
    echo "Площадь: " . $square->getArea() . " кв. единиц\n";   
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}