<?php
/**
 * Задание 9: Валидатор паролей
 * Файл: src/PasswordValidator.php
 */

namespace Validators;

class PasswordValidator
{
    /**
     * Валидация пароля
     * 
     * Правила:
     * - Минимум 8 символов
     * - Хотя бы одна заглавная буква
     * - Хотя бы одна цифра
     * - Нет пробелов
     * 
     * @param string $password Пароль для проверки
     * @return bool true если пароль валиден, false иначе
     */
    public static function validate(string $password): bool
    {
        if (strlen($password) < 8) {
            return false;
        }
        if (strpos($password, ' ') !== false) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return true;
    }

    /**
     * Получить список ошибок валидации
     * 
     * @param string $password Пароль для проверки
     * @return array Массив ошибок
     */
    public static function getErrors(string $password): array
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = "Пароль должен содержать минимум 8 символов";
        }

        if (strpos($password, ' ') !== false) {
            $errors[] = "Пароль не должен содержать пробелы";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Пароль должен содержать хотя бы одну заглавную букву";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Пароль должен содержать хотя бы одну цифру";
        }

        return $errors;
    }
}
echo "=== Валидатор паролей ===\n\n";

$testPasswords = [
    'Password123',      // Валидный
    'pass',             // Слишком короткий
    'password',         // Нет заглавной буквы и цифры
    'PASSWORD',         // Нет цифры
    '12345678',         // Нет заглавной буквы
    'Pass 123',         // Есть пробел
    'MyPass1',          // Валидный
    'A1b2C3d4',         // Валидный
];

foreach ($testPasswords as $password) {
    $isValid = \Validators\PasswordValidator::validate($password);
    
    echo "Пароль: '$password'\n";
    echo "Статус: " . ($isValid ? "✅ Валидный" : "❌ Невалидный") . "\n";
    
    if (!$isValid) {
        $errors = \Validators\PasswordValidator::getErrors($password);
        echo "Ошибки:\n";
        foreach ($errors as $error) {
            echo "  - $error\n";
        }
    }
    
    echo "\n";
}
?>