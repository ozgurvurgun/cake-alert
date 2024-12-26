<?php

namespace Domain\Interfaces;

interface RepositoryInterface
{
    public function getAllTeamMembers(): array;
}
