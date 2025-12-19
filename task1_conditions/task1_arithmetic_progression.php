<?php
/**
 * Задание 1.1: Проверка арифметической прогрессии
 * Определяет, являются ли три заданных числа последовательными членами арифметической прогрессии
 */
function isArithmeticProgression($a, $b, $c) {
    return ($b - $a) == ($c - $b);
}
echo "=== Проверка арифметической прогрессии ===\n\n";
$num1 = 2;
$num2 = 4;
$num3 = 6;
echo "Тест 1: $num1, $num2, $num3\n";
echo "Результат: " . (isArithmeticProgression($num1, $num2, $num3) ? "ДА" : "НЕТ") . "\n\n";
$num1 = 1;
$num2 = 3;
$num3 = 7;
echo "Тест 2: $num1, $num2, $num3\n";
echo "Результат: " . (isArithmeticProgression($num1, $num2, $num3) ? "ДА" : "НЕТ") . "\n\n";
$num1 = -5;
$num2 = 0;
$num3 = 5;
echo "Тест 3: $num1, $num2, $num3\n";
echo "Результат: " . (isArithmeticProgression($num1, $num2, $num3) ? "ДА" : "НЕТ") . "\n\n";
?>