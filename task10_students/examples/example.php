<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Students\Student;
use Students\Group;
use function Students\printStudentInfo;
use function Students\printGroupInfo;
echo "=== Система учета студентов ===\n\n";
$student1 = new Student("Иван", "Иванов");
$student1->addGrade(5);
$student1->addGrade(4);
$student1->addGrade(5);
$student1->addGrade(5);
$student2 = new Student("Мария", "Петрова");
$student2->addGrade(5);
$student2->addGrade(5);
$student2->addGrade(5);
$student2->addGrade(4);
$student3 = new Student("Алексей", "Сидоров");
$student3->addGrade(4);
$student3->addGrade(3);
$student3->addGrade(4);
$student3->addGrade(4);
$group = new Group("ИТ-21");
$group->addStudent($student1);
$group->addStudent($student2);
$group->addStudent($student3);
echo "Информация о студентах:\n";
echo str_repeat("=", 50) . "\n\n";
foreach ($group->getStudents() as $student) {
    printStudentInfo($student);
    echo "\n";
}
echo str_repeat("=", 50) . "\n";
printGroupInfo($group);
echo str_repeat("=", 50) . "\n\n";
$bestStudent = $group->getBestStudent();
if ($bestStudent !== null) {
    echo "🏆 Лучший студент группы:\n";
    printStudentInfo($bestStudent);
}
echo "\n" . str_repeat("=", 50) . "\n\n";
echo "Детальная статистика:\n\n";
foreach ($group->getStudents() as $student) {
    $average = $student->getAverage();
    $status = "";   
    if ($average >= 4.5) {
        $status = "Отличник ⭐";
    } elseif ($average >= 4.0) {
        $status = "Хорошист ✓";
    } elseif ($average >= 3.0) {
        $status = "Удовлетворительно";
    } else {
        $status = "Неуспевающий ⚠";
    } 
    echo $student->getFullName() . " - " . number_format($average, 2) . " - $status\n";
}