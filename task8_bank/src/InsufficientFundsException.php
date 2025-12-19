<?php
/**
 * Задание 8: Банковский счет с обработкой исключений
 */

namespace Bank;
class InsufficientFundsException extends \Exception
{
    public function __construct(float $balance, float $amount)
    {
        $message = "Недостаточно средств. Баланс: $balance, запрошено: $amount";
        parent::__construct($message);
    }
}