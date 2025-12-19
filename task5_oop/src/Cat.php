<?php
namespace OOP;
class Cat extends Animal
{
    private string $color;
    public function __construct(string $name, int $age, string $color)
    {
        parent::__construct($name, $age, " ошка");
        $this->color = $color;
    }
    public function makeSound(): void
    {
        echo "{$this->name} говорит: ћ€у!\n";
    }
    public function getInfo(): string
    {
        return parent::getInfo() . ", ÷вет: {$this->color}";
    }
}