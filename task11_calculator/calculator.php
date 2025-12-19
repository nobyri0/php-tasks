<?php
/**
 * Задание 11: Калькулятор с формой
 */
$number1 = '';
$number2 = '';
$operation = '+';
$result = null;
$error = '';
$requestData = !empty($_POST) ? $_POST : $_GET;

if (!empty($requestData)) {
    $number1 = $requestData['number1'] ?? '';
    $number2 = $requestData['number2'] ?? '';
    $operation = $requestData['operation'] ?? '+';
    if ($number1 === '' || $number2 === '') {
        $error = 'Пожалуйста, заполните оба поля';
    } elseif (!is_numeric($number1) || !is_numeric($number2)) {
        $error = 'Введите корректные числа';
    } else {
        $num1 = floatval($number1);
        $num2 = floatval($number2);
        switch ($operation) {
            case '+':
                $result = $num1 + $num2;
                break;
            case '-':
                $result = $num1 - $num2;
                break;
            case '*':
                $result = $num1 * $num2;
                break;
            case '/':
                if ($num2 == 0) {
                    $error = 'Ошибка: деление на ноль';
                } else {
                    $result = $num1 / $num2;
                }
                break;
            default:
                $error = 'Неизвестная операция';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Калькулятор</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #555;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95em;
        }
        
        input[type="number"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        input[type="number"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .result {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        
        .result-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9em;
            margin-bottom: 5px;
        }
        
        .result-value {
            color: white;
            font-size: 2em;
            font-weight: bold;
        }
        
        .error {
            margin-top: 30px;
            padding: 15px;
            background: #ff4757;
            color: white;
            border-radius: 10px;
            text-align: center;
            animation: shake 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .operation-symbols {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .operation-btn {
            padding: 15px;
            background: #f5f5f5;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .operation-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .operation-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧮 Калькулятор</h1>
        
        <form method="POST" action="calculator.php">
            <div class="form-group">
                <label for="number1">Первое число:</label>
                <input 
                    type="number" 
                    id="number1" 
                    name="number1" 
                    value="<?= htmlspecialchars($number1) ?>"
                    step="any"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="number2">Второе число:</label>
                <input 
                    type="number" 
                    id="number2" 
                    name="number2" 
                    value="<?= htmlspecialchars($number2) ?>"
                    step="any"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="operation">Операция:</label>
                <select id="operation" name="operation">
                    <option value="+" <?= $operation === '+' ? 'selected' : '' ?>>+ (Сложение)</option>
                    <option value="-" <?= $operation === '-' ? 'selected' : '' ?>>- (Вычитание)</option>
                    <option value="*" <?= $operation === '*' ? 'selected' : '' ?>>× (Умножение)</option>
                    <option value="/" <?= $operation === '/' ? 'selected' : '' ?>>÷ (Деление)</option>
                </select>
            </div>
            
            <button type="submit">Посчитать</button>
        </form>
        
        <?php if ($error): ?>
            <div class="error">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php elseif ($result !== null): ?>
            <div class="result">
                <div class="result-label">Результат:</div>
                <div class="result-value">
                    <?= htmlspecialchars($number1) ?> 
                    <?= htmlspecialchars($operation) ?> 
                    <?= htmlspecialchars($number2) ?> 
                    = 
                    <?= number_format($result, 2, '.', ' ') ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>