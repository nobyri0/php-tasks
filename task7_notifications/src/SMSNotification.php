<?php
namespace Notifications;
class SMSNotification extends AbstractNotification
{
    private string $phoneNumber;
    public function __construct(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
    public function send(string $message): string
    {
        $this->updateStatus('sent');
        $result = "SMS отправлен с таким содержимым: {$message}";
        $result .= "\nНомер: {$this->phoneNumber}";
        return $result;
    }
    public function getType(): string
    {
        return 'SMS';
    }
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
}