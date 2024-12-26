<?php

namespace Application\Services;

use Domain\Interfaces\RepositoryInterface;
use Domain\Interfaces\MailerInterface;
use Application\DTO\TeamMemberDTO;
use Domain\Services\BirthdayNotifier;
use Utils\DateHelper;
use Utils\Logger;

class BirthdayService
{
    private RepositoryInterface $repository;
    private MailerInterface $mailer;

    public function __construct(RepositoryInterface $repository, MailerInterface $mailer)
    {
        $this->repository = $repository;
        $this->mailer = $mailer;
    }

    public function notifyTeam(): void
    {
        Logger::info("Birthday notification service started.");

        $teamMemberDTOs = $this->getTeamMembers();
        foreach ($teamMemberDTOs as $memberDTO) {
            $this->processBirthdayNotification($memberDTO, $teamMemberDTOs);
        }

        Logger::info("Birthday notification service completed.");
    }

    private function getTeamMembers(): array
    {
        $teamMembers = $this->repository->getAllTeamMembers();
        Logger::info("Number of team members pulled from database: " . count($teamMembers));

        return array_map(fn($member) => TeamMemberDTO::fromDomain($member), $teamMembers);
    }

    private function processBirthdayNotification(TeamMemberDTO $memberDTO, array $teamMemberDTOs): void
    {
        $today = new \DateTime();
        $notificationDaysBefore = (int) $_ENV['DEFAULT_NOTIFICATION_DAYS'];

        $nextBirthday = DateHelper::getNextBirthday($memberDTO->birthday, $today);
        $daysLeft = DateHelper::getDaysLeft($nextBirthday, $today);

        Logger::debug("Member: {$memberDTO->name}, Days Left: {$daysLeft}, Today: {$today->format('Y-m-d')}, Next Birthday: {$nextBirthday->format('Y-m-d')}");

        if ($daysLeft <= $notificationDaysBefore && $daysLeft >= 0) {
            Logger::info("Sending notification: {$memberDTO->name}, Days left: {$daysLeft}");
            $this->sendBirthdayNotification($memberDTO, $teamMemberDTOs, $daysLeft);
        }
    }

    private function sendBirthdayNotification(TeamMemberDTO $memberDTO, array $teamMemberDTOs, int $daysLeft): void
    {
        $notifier = new BirthdayNotifier($memberDTO, $teamMemberDTOs);
        $message = $notifier->createMessage();
        $recipients = $notifier->getRecipients();
        $bccRecipients = array_map(fn($r) => $r->email, $recipients);

        Logger::debug("Mail Recipients: " . implode(", ", $bccRecipients));
        Logger::debug("Mail Content: {$message}");

        try {
            $this->mailer->sendMail(
                $_ENV['MAIL_FROM'],
                'Birthday Reminder',
                $message,
                $bccRecipients
            );
            Logger::info("Mail sent successfully: " . implode(", ", $bccRecipients));
        } catch (\Exception $e) {
            Logger::error("Mail sending failed: {$e->getMessage()}");
        }
    }
}
