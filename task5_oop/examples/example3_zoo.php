<?php
require_once __DIR__ . '/../vendor/autoload.php';
use OOP\Zoo;
use OOP\Dog;
use OOP\Cat;
echo "=== Пример 3: Зоопарк ===\n\n";
$zoo = new Zoo();
$dog1 = new Dog("Бобик", 3, "Немецкая овчарка");
$dog2 = new Dog("Шарик", 5, "Хаски");
$cat1 = new Cat("Мурка", 2, "Рыжий");
$cat2 = new Cat("Васька", 4, "Серый");
$zoo->addAnimal($dog1);
$zoo->addAnimal($dog2);
$zoo->addAnimal($cat1);
$zoo->addAnimal($cat2);
$zoo->listAnimals();
$zoo->animalSounds();
echo "Всего животных в зоопарке: " . $zoo->getAnimalCount() . "\n";