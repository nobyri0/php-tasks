<?php
/**
 * Задание 4.3: Самое часто встречающееся слово
 * Находит самое частое слово в тексте (до 1000 символов)
 */

function mostRecent(string $text): ?array {
    $text = substr($text, 0, 1000);
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^\p{L}\s]/u', '', $text);
    $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    if (empty($words)) {
        return null;
    }
    $wordCount = array_count_values($words);
    arsort($wordCount);
    $mostFrequentWord = key($wordCount);
    $frequency = $wordCount[$mostFrequentWord];  
    return [
        'word' => $mostFrequentWord,
        'count' => $frequency,
        'allWords' => $wordCount
    ];
}
echo "=== Поиск самого частого слова ===\n\n";
$text1 = "Привет мир! Это тестовый текст. Этот текст содержит разные слова. " .
         "Слово текст встречается здесь чаще всего. Текст это важно.";
echo "Текст 1:\n$text1\n\n";
$result1 = mostRecent($text1);
if ($result1) {
    echo "Самое частое слово: '{$result1['word']}'\n";
    echo "Встречается: {$result1['count']} раз(а)\n\n";
    
    echo "Топ-5 самых частых слов:\n";
    $counter = 0;
    foreach ($result1['allWords'] as $word => $count) {
        echo ($counter + 1) . ". '$word' - $count раз(а)\n";
        $counter++;
        if ($counter >= 5) break;
    }
}
echo "\n" . str_repeat("=", 50) . "\n\n";
$text2 = "The quick brown fox jumps over the lazy dog. " .
         "The dog was really lazy. The fox was very quick and brown.";
echo "Текст 2:\n$text2\n\n";
$result2 = mostRecent($text2);
if ($result2) {
    echo "Самое частое слово: '{$result2['word']}'\n";
    echo "Встречается: {$result2['count']} раз(а)\n\n";
}
echo str_repeat("=", 50) . "\n\n";
$text3 = str_repeat("программирование это интересно и увлекательно. ", 30);
echo "Текст 3 (длина: " . strlen($text3) . " символов, обрезан до 1000):\n";
echo substr($text3, 0, 100) . "...\n\n";
$result3 = mostRecent($text3);
if ($result3) {
    echo "Самое частое слово: '{$result3['word']}'\n";
    echo "Встречается: {$result3['count']} раз(а)\n";
}
?>