<?php
/**
 * Задание 4.1: Алфавитная сортировка символов строки
 * Возвращает строку с буквами в алфавитном порядке
 */
function alphabeticalOrder(string $str): string {
    $lowerStr = strtolower($str);
    $chars = str_split($lowerStr);
    sort($chars);
    return implode('', $chars);
}
echo "=== Алфавитная сортировка символов строки ===\n\n";
$str1 = 'alphabetical';
echo "Входная строка: '$str1'\n";
echo "Результат: '" . alphabeticalOrder($str1) . "'\n\n";
$examples = [
    'hello',
    'world',
    'programming',
    'javascript',
    'database'
];
echo "=== Дополнительные примеры ===\n\n";
foreach ($examples as $example) {
    echo "'$example' => '" . alphabeticalOrder($example) . "'\n";
}
echo "\n";
function alphabeticalOrderPreserveCase(string $str): string {
    $chars = str_split($str);
    
    usort($chars, function($a, $b) {
        return strcasecmp($a, $b);
    });
    
    return implode('', $chars);
}
echo "=== С сохранением регистра ===\n";
$str2 = 'HelloWorld';
echo "Входная строка: '$str2'\n";
echo "Результат (нижний регистр): '" . alphabeticalOrder($str2) . "'\n";
echo "Результат (сохранен регистр): '" . alphabeticalOrderPreserveCase($str2) . "'\n";
?>