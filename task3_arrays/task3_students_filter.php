<?php
/**
 * Задание 3.3: Фильтрация студентов по среднему баллу
 * Выводит студентов с средней оценкой >= 4
 */

$students = [
    ['name' => 'Маша', 'age' => 22, 'grades' => [5, 4, 5]],
    ['name' => 'Витя', 'age' => 23, 'grades' => [3, 4, 2]],
    ['name' => 'Олег', 'age' => 21, 'grades' => [4, 5, 5]],
];
echo "=== Студенты с высоким средним баллом (>= 4) ===\n\n";
foreach ($students as $student) {
    $sum = array_sum($student['grades']);
    $count = count($student['grades']);
    $averageGrade = $sum / $count;
    
    if ($averageGrade >= 4) {
        echo $student['name'] . ": " . number_format($averageGrade, 2) . "\n";
    }
}
echo "\n";
echo "=== Полная информация о всех студентах ===\n\n";
foreach ($students as $student) {
    $sum = array_sum($student['grades']);
    $count = count($student['grades']);
    $averageGrade = $sum / $count;
    
    echo "Имя: " . $student['name'] . "\n";
    echo "Возраст: " . $student['age'] . "\n";
    echo "Оценки: " . implode(", ", $student['grades']) . "\n";
    echo "Средний балл: " . number_format($averageGrade, 2) . "\n";
    echo "Статус: " . ($averageGrade >= 4 ? "Хорошист/Отличник" : "Требуется улучшение") . "\n";
    echo str_repeat("-", 40) . "\n";
}
function filterStudentsByAverage(array $students, float $minAverage): array {
    usort($students, function($a, $b) {
        $avgA = array_sum($a['grades']) / count($a['grades']);
        $avgB = array_sum($b['grades']) / count($b['grades']);
        return $avgB <=> $avgA;
    });
    return $students;
}
echo "\n=== Студенты, отсортированные по среднему баллу ===\n\n";
$sortedStudents = filterStudentsByAverage($students);
foreach ($sortedStudents as $index => $student) {
    $average = array_sum($student['grades']) / count($student['grades']);
    echo ($index + 1) . ". " . $student['name'] . " - " . number_format($average, 2) . "\n";
}
?>