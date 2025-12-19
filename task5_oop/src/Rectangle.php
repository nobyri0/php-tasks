<?php
namespace OOP;
class Rectangle
{
    private float $length;
    private float $width;
    public function __construct(float $length, float $width)
    {
        if ($length <= 0 || $width <= 0) {
            throw new \Exception("Ошибка: стороны прямоугольника должны быть положительными числами!");
        }
        
        $this->length = $length;
        $this->width = $width;
    }
    public function getArea(): float
    {
        return $this->length * $this->width;
    }
    public function getPerimeter(): float
    {
        return 2 * ($this->length + $this->width);
    }
    public function getInfo(): string
    {
        return "Прямоугольник: длина = {$this->length}, ширина = {$this->width}";
    }
    public function getLength(): float
    {
        return $this->length;
    }
    public function getWidth(): float
    {
        return $this->width;
    }
}