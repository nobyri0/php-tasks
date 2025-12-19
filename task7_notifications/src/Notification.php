<?php
namespace Notifications;
interface Notification
{
    public function send(string $message): string;
    public function getStatus(): string;
    public function getType(): string;
}