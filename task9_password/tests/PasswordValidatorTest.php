<?php
/**
 * Задание 9: Тесты для PasswordValidator с DataProvider
 * Файл: tests/PasswordValidatorTest.php
 */

namespace Validators\Tests;

use PHPUnit\Framework\TestCase;
use Validators\PasswordValidator;
use PHPUnit\Framework\Attributes\DataProvider;

class PasswordValidatorTest extends TestCase
{
    /**
     * Тест валидных паролей с использованием DataProvider
     */
    #[DataProvider('validPasswordsProvider')]
    public function testValidPasswords(string $password): void
    {
        $this->assertTrue(
            PasswordValidator::validate($password),
            "Пароль '$password' должен быть валидным"
        );
    }

    /**
     * DataProvider для валидных паролей
     */
    public static function validPasswordsProvider(): array
    {
        return [
            'стандартный пароль' => ['Password123'],
            'длинный пароль' => ['VeryLongPassword123456'],
            'с спецсимволами' => ['Pass@word123'],
            'минимальная длина' => ['Passw0rd'],
            'множество цифр' => ['Password12345'],
            'множество заглавных' => ['PASSword123'],
            'смешанный регистр' => ['PaSsWoRd123'],
        ];
    }

    /**
     * Тест невалидных паролей с использованием DataProvider
     */
    #[DataProvider('invalidPasswordsProvider')]
    public function testInvalidPasswords(string $password, string $reason): void
    {
        $this->assertFalse(
            PasswordValidator::validate($password),
            "Пароль '$password' должен быть невалидным: $reason"
        );
    }

    /**
     * DataProvider для невалидных паролей
     */
    public static function invalidPasswordsProvider(): array
    {
        return [
            'слишком короткий' => ['Pass1', 'менее 8 символов'],
            'нет заглавной буквы' => ['password123', 'отсутствует заглавная буква'],
            'нет цифры' => ['Password', 'отсутствует цифра'],
            'есть пробел' => ['Pass word1', 'содержит пробел'],
            'только буквы' => ['password', 'нет цифр и заглавных'],
            'только цифры' => ['12345678', 'нет букв'],
            'пустая строка' => ['', 'пустой пароль'],
            'пробелы в начале' => [' Password123', 'пробел в начале'],
            'пробелы в конце' => ['Password123 ', 'пробел в конце'],
            'только пробелы' => ['        ', 'только пробелы'],
        ];
    }

    /**
     * Тест метода getErrors для разных сценариев
     */
    #[DataProvider('passwordErrorsProvider')]
    public function testGetErrors(string $password, array $expectedErrors): void
    {
        $errors = PasswordValidator::getErrors($password);
        $this->assertCount(
            count($expectedErrors),
            $errors,
            "Количество ошибок не совпадает для пароля '$password'"
        );
    }

    /**
     * DataProvider для тестирования getErrors
     */
    public static function passwordErrorsProvider(): array
    {
        return [
            'валидный пароль' => [
                'Password123',
                [] 
            ],
            'короткий без заглавной и цифры' => [
                'pass',
                [
                    'Пароль должен содержать минимум 8 символов',
                    'Пароль должен содержать хотя бы одну заглавную букву',
                    'Пароль должен содержать хотя бы одну цифру'
                ]
            ],
            'с пробелом' => [
                'Pass word1',
                ['Пароль не должен содержать пробелы']
            ],
            'только длина ОК' => [
                'password',
                [
                    'Пароль должен содержать хотя бы одну заглавную букву',
                    'Пароль должен содержать хотя бы одну цифру'
                ]
            ],
        ];
    }

    /**
     * Тест граничных значений длины пароля
     */
    #[DataProvider('lengthBoundaryProvider')]
    public function testLengthBoundaries(string $password, bool $expectedValid): void
    {
        $this->assertEquals(
            $expectedValid,
            PasswordValidator::validate($password),
            "Пароль '$password' должен быть " . ($expectedValid ? 'валидным' : 'невалидным')
        );
    }

    /**
     * DataProvider для граничных значений длины
     */
    public static function lengthBoundaryProvider(): array
    {
        return [
            '7 символов (невалидный)' => ['Passw0r', false],
            '8 символов (валидный)' => ['Passw0rd', true],
            '9 символов (валидный)' => ['Passw0rd1', true],
        ];
    }

    /**
     * Тест специальных символов
     */
    #[DataProvider('specialCharactersProvider')]
    public function testSpecialCharacters(string $password, bool $expectedValid): void
    {
        $this->assertEquals(
            $expectedValid,
            PasswordValidator::validate($password)
        );
    }

    /**
     * DataProvider для специальных символов
     */
    public static function specialCharactersProvider(): array
    {
        return [
            'со спецсимволами' => ['P@ssw0rd!', true],
            'с подчеркиванием' => ['Pass_word1', true],
            'с дефисом' => ['Pass-word1', true],
            'только спецсимволы' => ['!@#$%^&*', false],
        ];
    }
}
?>