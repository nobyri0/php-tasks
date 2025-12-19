<?php
/**
 * Задание 1.3: Количество дней в месяце
 * Выводит количество дней в месяце (не високосный год)
 * Использовано только 2 условия!
 */

function getDaysInMonth($month) {
    if ($month < 1 || $month > 12) {
        return "Ошибка: номер месяца должен быть от 1 до 12";
    }
    if ($month == 2) {
        return 28;
    } 
    elseif ($month == 4 || $month == 6 || $month == 9 || $month == 11) {
        return 30;
    } 
    else {
        return 31;
    }
}
$monthNames = [
    1 => "Январь",
    2 => "Февраль",
    3 => "Март",
    4 => "Апрель",
    5 => "Май",
    6 => "Июнь",
    7 => "Июль",
    8 => "Август",
    9 => "Сентябрь",
    10 => "Октябрь",
    11 => "Ноябрь",
    12 => "Декабрь"
];
echo "=== Количество дней в месяце (не високосный год) ===\n\n";
for ($month = 1; $month <= 12; $month++) {
    $days = getDaysInMonth($month);
    printf("%2d. %-10s - %2d дней\n", $month, $monthNames[$month], $days);
}
echo "\n";
echo "Введите номер месяца (1-12): ";
$month = intval(trim(fgets(STDIN)));
$days = getDaysInMonth($month);
if (is_numeric($days)) {
    echo "В этом месяце $days дней\n";
} else {
    echo $days . "\n";
}
?>