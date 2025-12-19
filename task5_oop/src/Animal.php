<?php
namespace OOP;
abstract class Animal
{
    protected string $name;
    protected int $age;
    protected string $species;
    public function __construct(string $name, int $age, string $species)
    {
        $this->name = $name;
        $this->age = $age;
        $this->species = $species;
    }
    abstract public function makeSound(): void;
    public function getInfo(): string
    {
        return "Имя: {$this->name}, Вид: {$this->species}, Возраст: {$this->age} лет";
    }
    public function getName(): string
    {
        return $this->name;
    }
}