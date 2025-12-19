<?php
/**
 * Задание 3.1: Средняя оценка студентов
 * Подсчитывает среднюю оценку всех студентов
 */

function calculateAverageGrade(array $grades): float {
    if (empty($grades)) {
        return 0;
    }   
    $sum = array_sum($grades);
    $count = count($grades);
    return $sum / $count;
}
echo "=== Подсчет средней оценки студентов ===\n\n";
$grades1 = [5, 4, 5, 3, 4, 5, 4, 3, 5, 4];
echo "Оценки группы 1: " . implode(", ", $grades1) . "\n";
echo "Средняя оценка: " . round(calculateAverageGrade($grades1), 2) . "\n\n";
$grades2 = [5, 5, 5, 4, 5, 5, 4, 5, 5, 5];
echo "Оценки группы 2: " . implode(", ", $grades2) . "\n";
echo "Средняя оценка: " . round(calculateAverageGrade($grades2), 2) . "\n\n";
$grades3 = [2, 3, 4, 5, 3, 4, 3, 2, 4, 3];
echo "Оценки группы 3: " . implode(", ", $grades3) . "\n";
echo "Средняя оценка: " . round(calculateAverageGrade($grades3), 2) . "\n\n";
function getGradeStatistics(array $grades): float {
    return [
        'average' => round(calculateAverageGrade($grades), 2),
        'min' => min($grades),
        'max' => max($grades),
        'count' => count($grades)
    ];
}
echo "=== Детальная статистика (группа 1) ===\n";
$stats = getGradeStatistics($grades1);
echo "Средняя оценка: " . $stats['average'] . "\n";
echo "Минимальная оценка: " . $stats['min'] . "\n";
echo "Максимальная оценка: " . $stats['max'] . "\n";
echo "Количество оценок: " . $stats['count'] . "\n";
?>