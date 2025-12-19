<?php
namespace Notifications;
abstract class AbstractNotification implements Notification
{
    protected string $status = 'pending';
    protected ?int $timestamp = null;

    public function getStatus(): string
    {
        return $this->status;
    }
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }
    protected function updateStatus(string $newStatus): void
    {
        $this->status = $newStatus;
        $this->timestamp = time();
    }
    abstract public function send(string $message): string;
    abstract public function getType(): string;
}
