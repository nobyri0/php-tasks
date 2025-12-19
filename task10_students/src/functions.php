<?php
namespace Students;
function printStudentInfo(Student $student): void
{
    echo "Студент: " . $student->getFullName() . "\n";
    echo "Оценки: " . implode(", ", $student->getGrades()) . "\n";
    echo "Средний балл: " . number_format($student->getAverage(), 2) . "\n";
}
function printGroupInfo(Group $group): void
{
    echo "Группа: " . $group->getGroupName() . "\n";
    echo "Количество студентов: " . $group->getStudentCount() . "\n";
    echo "Средний балл группы: " . number_format($group->getGroupAverage(), 2) . "\n";
}