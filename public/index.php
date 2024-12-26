<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Application\Services\BirthdayService;
use Infrastructure\Database\DatabaseRepository;
use Infrastructure\Mail\Mailer;

try {
    echo "Environment is loading..." . PHP_EOL;

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    echo ".env file loaded." . PHP_EOL;

    $repository = new DatabaseRepository();
    echo "DatabaseRepository created." . PHP_EOL;

    $mailer = new Mailer();
    echo "Mailer created." . PHP_EOL;

    $birthdayService = new BirthdayService($repository, $mailer);
    echo "BirthdayService started." . PHP_EOL;

    $birthdayService->notifyTeam();
    echo "Birthday notifications sent successfully!" . PHP_EOL;
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . PHP_EOL;
}
