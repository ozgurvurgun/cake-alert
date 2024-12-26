<?php

namespace Utils;

class DateHelper
{
    public static function getNextBirthday(string $birthdayString, \DateTime $today): \DateTime
    {
        $birthday = new \DateTime($birthdayString);
        $birthdayThisYear = (clone $today)->setDate(
            (int) $today->format('Y'),
            (int) $birthday->format('m'),
            (int) $birthday->format('d')
        );

        if ($birthdayThisYear < $today) {
            $birthdayThisYear->modify('+1 year');
        }

        return $birthdayThisYear;
    }


    public static function getDaysLeft(\DateTime $nextBirthday, \DateTime $today): int
    {
        $interval = $today->diff($nextBirthday);
        return $interval->days;
    }
}
