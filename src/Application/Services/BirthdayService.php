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
            Logger::info("Sending notification for: {$memberDTO->name}, Days left: {$daysLeft}");
            $this->sendBirthdayNotification($memberDTO, $teamMemberDTOs, $daysLeft);
        }
    }

    private function sendBirthdayNotification(TeamMemberDTO $memberDTO, array $teamMemberDTOs, int $daysLeft): void
    {
        $recipients = $this->getFilteredRecipients($memberDTO, $teamMemberDTOs);
        $message = $this->prepareMessage($memberDTO, $daysLeft);

        foreach ($recipients as $recipient) {
            Logger::debug("Sending mail to: {$recipient}");
            $this->sendMail($recipient, 'Birthday Reminder', $message);
        }
    }

    private function getFilteredRecipients(TeamMemberDTO $memberDTO, array $teamMemberDTOs): array
    {
        return array_map(
            fn($recipient) => $recipient->email,
            array_filter(
                $teamMemberDTOs,
                fn($recipient) => $recipient->email !== $memberDTO->email
            )
        );
    }

    private function prepareMessage(TeamMemberDTO $memberDTO, int $daysLeft): string
    {
        $notifier = new BirthdayNotifier($memberDTO, []);
        return $notifier->createMessage($daysLeft);
    }

    private function sendMail(string $recipient, string $subject, string $message): void
    {
        try {
            $this->mailer->sendMail(
                $recipient,
                $subject,
                $message
            );
            Logger::info("Mail sent successfully to: {$recipient}");
        } catch (\Exception $e) {
            Logger::error("Mail sending failed for {$recipient}: {$e->getMessage()}");
        }
    }
}
