<?php

namespace Domain\Services;

use Application\DTO\TeamMemberDTO;
use Utils\DateHelper;

class BirthdayNotifier
{
    private TeamMemberDTO $birthdayPerson;
    private array $recipients;

    public function __construct(TeamMemberDTO $birthdayPerson, array $recipients)
    {
        $this->birthdayPerson = $birthdayPerson;
        $this->recipients = $recipients;
    }

    public function createMessage(): string
    {
        $today = new \DateTime();
        $nextBirthday = DateHelper::getNextBirthday($this->birthdayPerson->birthday, $today);
        $daysLeft = DateHelper::getDaysLeft($nextBirthday, $today);
        $birthdayFormatted = $nextBirthday->format('d M Y');
        return "Merhaba,\n\n"
            . "{$this->birthdayPerson->name} isimli ekip üyemizin doğum günü {$birthdayFormatted} tarihinde! "
            . "Doğum gününe sadece {$daysLeft} gün kaldı! Kutlamayı unutmayın. 🎉🎂\n\n"
            . "İyi çalışmalar,\nSmartLab Cake Alert 🎂";
    }

    public function getRecipients(): array
    {
        return array_filter($this->recipients, fn($recipient) => $recipient->email !== $this->birthdayPerson->email);
    }
}
