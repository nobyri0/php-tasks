<?php
namespace OOP;
class Dog extends Animal
{
    private string $breed;
    public function __construct(string $name, int $age, string $breed)
    {
        parent::__construct($name, $age, "Собака");
        $this->breed = $breed;
    }
    public function makeSound(): void
    {
        echo "{$this->name} говорит: Гав-гав!\n";
    }
    public function getInfo(): string
    {
        return parent::getInfo() . ", Порода: {$this->breed}";
    }
}