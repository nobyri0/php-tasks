<?php
/**
 * Задание 3.2: Третье максимальное число
 * Находит третье максимальное число в массиве
 */

function findThirdMax(array $numbers): int|float|string {
    $uniqueNumbers = array_unique($numbers);
    rsort($uniqueNumbers);
    if (count($uniqueNumbers) < 3) {
        return "Ошибка: в массиве менее 3 уникальных чисел";
    }
    return $uniqueNumbers[2];
}

echo "=== Поиск третьего максимального числа ===\n\n";

$numbers1 = [12, 5, 23, 45, 8, 23, 67, 34, 12, 9];
echo "Массив 1: " . implode(", ", $numbers1) . "\n";
$result1 = findThirdMax($numbers1);
echo "Третье максимальное число: $result1\n\n";

$numbers2 = [100, 50, 75, 25, 90, 60, 80];
echo "Массив 2: " . implode(", ", $numbers2) . "\n";
$result2 = findThirdMax($numbers2);
echo "Третье максимальное число: $result2\n\n";

$numbers3 = [10, 10, 10, 20, 20, 30, 30, 40];
echo "Массив 3: " . implode(", ", $numbers3) . "\n";
$result3 = findThirdMax($numbers3);
echo "Третье максимальное число: $result3\n\n";

$numbers4 = [5, 5, 5, 10, 10];
echo "Массив 4: " . implode(", ", $numbers4) . "\n";
$result4 = findThirdMax($numbers4);
echo "Результат: $result4\n\n";

function showTopThree(array $numbers): int|float|string {
    $uniqueNumbers = array_unique($numbers);
    rsort($uniqueNumbers);
    
    echo "Топ-3 числа:\n";
    for ($i = 0; $i < min(3, count($uniqueNumbers)); $i++) {
        echo ($i + 1) . ". " . $uniqueNumbers[$i] . "\n";
    }
}

echo "=== Топ-3 для массива 1 ===\n";
showTopThree($numbers1);
?>