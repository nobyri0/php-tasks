<?php
/**
 * Задание 8: Тесты для BankAccount
 * Файл: tests/BankAccountTest.php
 */

namespace Bank\Tests;

use PHPUnit\Framework\TestCase;
use Bank\BankAccount;
use Bank\InvalidAmountException;
use Bank\InsufficientFundsException;

class BankAccountTest extends TestCase
{
    /**
     * Тест создания счета с корректным балансом
     */
    public function testAccountCreationWithValidBalance(): void
    {
        $account = new BankAccount(1000.0);
        $this->assertEquals(1000.0, $account->getBalance());
    }

    /**
     * Тест создания счета с нулевым балансом
     */
    public function testAccountCreationWithZeroBalance(): void
    {
        $account = new BankAccount(0.0);
        $this->assertEquals(0.0, $account->getBalance());
    }

    /**
     * Тест создания счета с отрицательным балансом
     */
    public function testAccountCreationWithNegativeBalance(): void
    {
        $this->expectException(InvalidAmountException::class);
        new BankAccount(-100.0);
    }

    /**
     * Тест успешного пополнения счета
     */
    public function testSuccessfulDeposit(): void
    {
        $account = new BankAccount(1000.0);
        $account->deposit(500.0);
        $this->assertEquals(1500.0, $account->getBalance());
    }

    /**
     * Тест пополнения на ноль
     */
    public function testDepositZero(): void
    {
        $this->expectException(InvalidAmountException::class);
        $account = new BankAccount(1000.0);
        $account->deposit(0.0);
    }

    /**
     * Тест пополнения на отрицательную сумму
     */
    public function testDepositNegativeAmount(): void
    {
        $this->expectException(InvalidAmountException::class);
        $account = new BankAccount(1000.0);
        $account->deposit(-50.0);
    }

    /**
     * Тест успешного снятия средств
     */
    public function testSuccessfulWithdraw(): void
    {
        $account = new BankAccount(1000.0);
        $account->withdraw(300.0);
        $this->assertEquals(700.0, $account->getBalance());
    }

    /**
     * Тест снятия всех средств
     */
    public function testWithdrawAllFunds(): void
    {
        $account = new BankAccount(1000.0);
        $account->withdraw(1000.0);
        $this->assertEquals(0.0, $account->getBalance());
    }

    /**
     * Тест снятия суммы больше баланса
     */
    public function testWithdrawMoreThanBalance(): void
    {
        $this->expectException(InsufficientFundsException::class);
        $account = new BankAccount(1000.0);
        $account->withdraw(1500.0);
    }

    /**
     * Тест снятия нулевой суммы
     */
    public function testWithdrawZero(): void
    {
        $this->expectException(InvalidAmountException::class);
        $account = new BankAccount(1000.0);
        $account->withdraw(0.0);
    }

    /**
     * Тест снятия отрицательной суммы
     */
    public function testWithdrawNegativeAmount(): void
    {
        $this->expectException(InvalidAmountException::class);
        $account = new BankAccount(1000.0);
        $account->withdraw(-100.0);
    }

    /**
     * Тест нескольких операций подряд
     */
    public function testMultipleOperations(): void
    {
        $account = new BankAccount(1000.0);
        
        $account->deposit(500.0);
        $this->assertEquals(1500.0, $account->getBalance());
        
        $account->withdraw(200.0);
        $this->assertEquals(1300.0, $account->getBalance());
        
        $account->deposit(300.0);
        $this->assertEquals(1600.0, $account->getBalance());
        
        $account->withdraw(1600.0);
        $this->assertEquals(0.0, $account->getBalance());
    }

    /**
     * Тест с дробными суммами
     */
    public function testDecimalAmounts(): void
    {
        $account = new BankAccount(100.50);
        $account->deposit(50.25);
        $this->assertEquals(150.75, $account->getBalance());
        
        $account->withdraw(25.50);
        $this->assertEquals(125.25, $account->getBalance());
    }
}

/*
=== Инструкция по запуску тестов ===

1. Установите PHPUnit:
   composer require --dev phpunit/phpunit

2. Создайте файл phpunit.xml в корне проекта:

<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/autoload.php"
         colors="true"
         verbose="true">
    <testsuites>
        <testsuite name="Bank Tests">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
</phpunit>

3. Запустите тесты:
   vendor/bin/phpunit

Или конкретный тест:
   vendor/bin/phpunit tests/BankAccountTest.php

4. Запуск с покрытием кода:
   vendor/bin/phpunit --coverage-html coverage
*/
?>