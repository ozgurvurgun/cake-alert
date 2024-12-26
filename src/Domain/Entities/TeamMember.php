<?php

namespace Domain\Entities;

class TeamMember
{
    private string $name;
    private \DateTime $birthday;
    private string $email;

    public function __construct(string $name, \DateTime $birthday, string $email)
    {
        $this->name = $name;
        $this->birthday = $birthday;
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isBirthdayToday(): bool
    {
        $today = new \DateTime();
        return $this->birthday->format('m-d') === $today->format('m-d');
    }
}
