<?php
namespace Bank
class BankAccount
{
    private float $balance;

    /**
     * Конструктор банковского счета
     * @param float $initialBalance Начальный баланс
     * @throws InvalidAmountException Если начальный баланс отрицательный
     */
    public function __construct(float $initialBalance)
    {
        if ($initialBalance < 0) {
            throw new InvalidAmountException("Начальный баланс не может быть отрицательным");
        }
        $this->balance = $initialBalance;
    }

    /**
     * Получить текущий баланс
     * @return float Баланс счета
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Пополнить счет
     * @param float $amount Сумма для пополнения
     * @throws InvalidAmountException Если сумма недопустима
     */
    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidAmountException("Сумма пополнения должна быть больше нуля");
        }
        
        $this->balance += $amount;
        echo "Счет пополнен на $amount. Новый баланс: {$this->balance}\n";
    }

    /**
     * Снять средства со счета
     * @param float $amount Сумма для снятия
     * @throws InvalidAmountException Если сумма недопустима
     * @throws InsufficientFundsException Если недостаточно средств
     */
    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidAmountException("Сумма снятия должна быть больше нуля");
        }
        
        if ($amount > $this->balance) {
            throw new InsufficientFundsException($this->balance, $amount);
        }
        
        $this->balance -= $amount;
        echo "Снято $amount со счета. Новый баланс: {$this->balance}\n";
    }
}

echo "=== Банковский счет ===\n\n";

try {
    echo "Создание счета с балансом 1000...\n";
    $account = new BankAccount(1000.0);
    echo "Счет создан. Баланс: " . $account->getBalance() . "\n\n";

    echo str_repeat("-", 50) . "\n\n";

    echo "Пополнение счета на 500...\n";
    $account->deposit(500.0);
    echo "\n";

    echo str_repeat("-", 50) . "\n\n";

    echo "Снятие 300 со счета...\n";
    $account->withdraw(300.0);
    echo "\n";

    echo str_repeat("-", 50) . "\n\n";

    echo "Попытка снять 2000 (больше, чем на счете)...\n";
    $account->withdraw(2000.0);
} catch (InsufficientFundsException $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n\n";
} catch (InvalidAmountException $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n\n";
}

echo str_repeat("=", 50) . "\n\n";

try {
    echo "Попытка создать счет с отрицательным балансом...\n";
    $invalidAccount = new BankAccount(-100);
} catch (InvalidAmountException $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n\n";
}

echo str_repeat("=", 50) . "\n\n";

try {
    echo "Попытка пополнить счет на отрицательную сумму...\n";
    $account->deposit(-50);
} catch (InvalidAmountException $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n\n";
}

echo str_repeat("=", 50) . "\n\n";

try {
    echo "Попытка снять 0 рублей...\n";
    $account->withdraw(0);
} catch (InvalidAmountException $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n\n";
}

echo str_repeat("=", 50) . "\n\n";
echo "Итоговый баланс: " . $account->getBalance() . "\n";
?>