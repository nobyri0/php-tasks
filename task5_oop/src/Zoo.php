<?php
namespace OOP;
class Zoo
{
    private array $animals = [];
    public function addAnimal(Animal $animal): void
    {
        $this->animals[] = $animal;
        echo "Животное '{$animal->getName()}' добавлено в зоопарк.\n";
    }
    public function listAnimals(): void
    {
        echo "\n=== Список животных в зоопарке ===\n\n";       
        if (empty($this->animals)) {
            echo "Зоопарк пуст.\n";
            return;
        }
        foreach ($this->animals as $index => $animal) {
            echo ($index + 1) . ". " . $animal->getInfo() . "\n";
        }
        echo "\n";
    }
    public function animalSounds(): void
    {
        echo "=== Звуки животных ===\n\n";     
        if (empty($this->animals)) {
            echo "В зоопарке нет животных.\n";
            return;
        }
        foreach ($this->animals as $animal) {
            $animal->makeSound();
        }
        echo "\n";
    }
    public function getAnimalCount(): int
    {
        return count($this->animals);
    }
}