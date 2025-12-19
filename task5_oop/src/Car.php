<?php
namespace OOP;
class Car
{
    private string $brand;
    private string $model;
    private int $year;
    private int $mileage;
    public function __construct(string $brand, string $model, int $year, int $mileage)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
        $this->mileage = $mileage;
    }
    public function getInfo(): string
    {
        return "Машина: {$this->brand}, модель: {$this->model}, год: {$this->year}, пробег: {$this->mileage} км";
    }
    public function drive(int $kilometers): void
    {
        if ($kilometers > 0) {
            $this->mileage += $kilometers;
            echo "Вы проехали $kilometers км. Новый пробег: {$this->mileage} км\n";
        } else {
            echo "Ошибка: количество километров должно быть положительным\n";
        }
    }
    public function getMileage(): int
    {
        return $this->mileage;
    }
}