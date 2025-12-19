<?php
/**
 * Бонусное задание: Калькулятор
 * 
 * Требования:
 * - Поддержка операций: +, -, *, /
 * - Работа с отрицательными числами
 * - Максимум 5 операндов
 * - Обработка ошибок (деление на ноль, некорректный ввод)
 * - Функция eval() запрещена
 */

function calculator($expression) {
    $expression = str_replace(' ', '', $expression);
    if (!preg_match('/^-?\d+([+\-*\/]-?\d+)*$/', $expression)) {
        return "Error";
    }
    preg_match_all('/-?\d+|[+\-*\/]/', $expression, $matches);
    $tokens = $matches[0];
    $operandCount = 0;
    foreach ($tokens as $token) {
        if (is_numeric($token)) {
            $operandCount++;
        }
    }
    if ($operandCount > 5) {
        return "Error";
    }
    $i = 1;
    while ($i < count($tokens)) {
        if ($tokens[$i] == '*' || $tokens[$i] == '/') {
            $operator = $tokens[$i];
            $left = floatval($tokens[$i - 1]);
            $right = floatval($tokens[$i + 1]);
            if ($operator == '/' && $right == 0) {
                return "Error";
            }
            $result = ($operator == '*') ? $left * $right : $left / $right;
            array_splice($tokens, $i - 1, 3, [$result]);
        } else {
            $i += 2; 
        }
    }
    $result = floatval($tokens[0]);
    for ($i = 1; $i < count($tokens); $i += 2) {
        $operator = $tokens[$i];
        $operand = floatval($tokens[$i + 1]);
        
        if ($operator == '+') {
            $result += $operand;
        } elseif ($operator == '-') {
            $result -= $operand;
        }
    }
    return (floor($result) == $result) ? intval($result) : $result;
}

echo "=== Калькулятор ===\n\n";

$testCases = [
    "2+4"        => 6,
    "-2+8-4"     => 2,
    "abc"        => "Error",
    "5\\\\\\5"   => "Error",
    "5/0"        => "Error",
];

echo "Тесты из задания:\n";
foreach ($testCases as $expression => $expected) {
    $result = calculator($expression);
    $status = ($result == $expected) ? "✓" : "✗";
    echo "$status Входная строка: $expression\n";
    echo "  Ожидается: $expected, Получено: $result\n\n";
}

echo str_repeat("=", 50) . "\n\n";
$additionalTests = [
    "10*2+5"     => "Умножение и сложение",
    "100/5-10"   => "Деление и вычитание",
    "2+3*4"      => "Приоритет операций",
    "-5*2+10"    => "Отрицательное число",
    "15/3/5"     => "Несколько делений",
    "1+2+3+4+5"  => "Максимум операндов (5)",
    "1+2+3+4+5+6" => "Больше 5 операндов (Error)",
    "10-5*2"     => "Вычитание и умножение",
    "20/4+3*2"   => "Комбинация операций",
];

echo "Дополнительные тесты:\n";
foreach ($additionalTests as $expression => $description) {
    $result = calculator($expression);
    echo "Входная строка: $expression\n";
    echo "Описание: $description\n";
    echo "Ответ: $result\n\n";
}
?>