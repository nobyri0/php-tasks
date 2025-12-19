<?php
namespace Bank
class InvalidAmountException extends \Exception
{
    public function __construct(string $reason = "Сумма должна быть положительной")
    {
        parent::__construct($reason);
    }
}