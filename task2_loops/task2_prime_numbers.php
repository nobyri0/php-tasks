<?php
/**
 * Задание 2.2: Простые числа от 1 до 100
 * Находит все простые числа с помощью вложенных циклов for
 */

function findPrimeNumbers(int $limit): array {
    $primes = [];
    for ($num = 2; $num <= $limit; $num++) {
        $isPrime = true;
        for ($divisor = 2; $divisor <= sqrt($num); $divisor++) {
            if ($num % $divisor == 0) {
                $isPrime = false;
                break; 
            }
        }
        if ($isPrime) {
            $primes[] = $num;
        }
    }
    
    return $primes;
}
echo "=== Простые числа от 1 до 100 ===\n\n";
$primes = findPrimeNumbers(100);
echo "Найдено простых чисел: " . count($primes) . "\n\n";
echo "Простые числа:\n";
$counter = 0;
foreach ($primes as $prime) {
    printf("%4d", $prime);
    $counter++;
    if ($counter % 10 == 0) {
        echo "\n";
    }
}
echo "\n\n";
echo "=== Дополнительная информация ===\n";
echo "Первые 10 простых чисел: ";
for ($i = 0; $i < 10; $i++) {
    echo $primes[$i];
    if ($i < 9) echo ", ";
}
echo "\n\n";
function isPrime($num) {
    if ($num < 2) return false;
    
    for ($i = 2; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) {
            return false;
        }
    }
    return true;
}
$testNum = 97;
echo "Проверка: $testNum " . (isPrime($testNum) ? "является" : "не является") . " простым числом\n";
?>