<?php
namespace Notifications;
class EmailNotification extends AbstractNotification
{
    private string $emailAddress;
    public function __construct(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }
    public function send(string $message): string
    {
        $this->updateStatus('sent');
        $result = "Email отправлен с таким содержимым: {$message}";
        $result .= "\nАдрес: {$this->emailAddress}";
        return $result;
    }
    public function getType(): string
    {
        return 'Email';
    }
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}