<?php

namespace Application\DTO;

class TeamMemberDTO
{
    public string $name;
    public string $birthday;
    public string $email;

    public function __construct(string $name, string $birthday, string $email)
    {
        $this->name = $name;
        $this->birthday = $birthday;
        $this->email = $email;
    }

    public static function fromDomain($teamMember): self
    {
        return new self(
            $teamMember->getName(),
            $teamMember->getBirthday()->format('Y-m-d'),
            $teamMember->getEmail()
        );
    }
}
