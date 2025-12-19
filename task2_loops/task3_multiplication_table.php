<?php
/**
 * Задание 2.3: Таблица умножения
 * Выводит таблицу умножения от 1 до 10 с помощью вложенных циклов
 */

echo "=== Таблица умножения от 1 до 10 ===\n\n";
function printMultiplicationTable(int $size): void {

echo "    ";
for ($j = 1; $j <= 10; $j++) {
    printf("%4d", $j);
}
echo "\n";
echo "    " . str_repeat("----", 10) . "\n";
for ($i = 1; $i <= 10; $i++) {
    printf("%2d |", $i);
    
    for ($j = 1; $j <= 10; $j++) {
        $result = $i * $j;
        printf("%4d", $result);
    }
    echo "\n";
}
echo "\n";
echo "=== Таблицы умножения по отдельности ===\n\n";
for ($i = 1; $i <= 5; $i++) {
    echo "Таблица умножения на $i:\n";
    for ($j = 1; $j <= 10; $j++) {
        $result = $i * $j;
        echo "$i × $j = $result\n";
    }
    echo "\n";
}
echo "=== Компактная версия (одна строка) ===\n\n";
for ($i = 1; $i <= 10; $i++) {
    for ($j = 1; $j <= 10; $j++) {
        echo ($i * $j) . " ";
    }
    echo "\n";
}
}
?>