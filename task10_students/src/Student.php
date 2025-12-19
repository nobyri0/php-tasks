<?php
namespace Students;
class Student
{
    private string $firstName;
    private string $lastName;
    private array $grades = [];
    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
    public function addGrade(int $grade): void
    {
        if ($grade < 2 || $grade > 5) {
            throw new \InvalidArgumentException("ќценка должна быть от 2 до 5");
        }
        $this->grades[] = $grade;
    }
    public function getAverage(): float
    {
        if (empty($this->grades)) {
            return 0.0;
        }
        return array_sum($this->grades) / count($this->grades);
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
    public function getGrades(): array
    {
        return $this->grades;
    }
}