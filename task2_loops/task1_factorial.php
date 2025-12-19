<?php
/**
 * Задание 2.1: Факториал числа (цикл while)
 * Вычисляет факториал числа с помощью цикла while
 */

function factorial(int $n): int|string {
    if ($n < 0) {
        return "Ошибка: факториал не определен для отрицательных чисел";
    }
    if ($n == 0) {
        return 1;
    }   
    $result = 1;
    $i = 1;
    while ($i <= $n) {
        $result *= $i;
        $i++;
    }
    
    return $result;
}

echo "=== Вычисление факториала (цикл while) ===\n\n";
$testNumbers = [0, 1, 5, 7, 10, 12];
foreach ($testNumbers as $num) {
    $result = factorial($num);
    echo "$num! = $result\n";
}

echo "\n";
echo "Введите число для вычисления факториала: ";
$number = intval(trim(fgets(STDIN)));
$result = factorial($number);

if (is_numeric($result)) {
    echo "$number! = $result\n";
} else {
    echo $result . "\n";
}
echo "=== Демонстрация процесса вычисления ===\n";
$n = 6;
echo "Вычисляем $n!:\n";
$result = 1;
$i = 1;

while ($i <= $n) {
    $result *= $i;
    echo "$i: $result\n";
    $i++;
}
?>