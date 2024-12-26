<?php

namespace Domain\Interfaces;

interface MailerInterface
{
    public function sendMail(string $to, string $subject, string $body): void;
}
