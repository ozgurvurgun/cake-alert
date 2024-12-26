<?php

namespace Infrastructure\Database;

use Domain\Interfaces\RepositoryInterface;
use Domain\Entities\TeamMember;

class DatabaseRepository implements RepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllTeamMembers(): array
    {
        $query = "SELECT * FROM team_members";
        $stmt = $this->db->query($query);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn($row) => new TeamMember($row['name'], new \DateTime($row['birthday']), $row['email']), $results);
    }

    public function addTeamMember(TeamMember $teamMember): void
    {
        $query = "INSERT INTO team_members (name, birthday, email) VALUES (:name, :birthday, :email)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'name' => $teamMember->getName(),
            'birthday' => $teamMember->getBirthday()->format('Y-m-d'),
            'email' => $teamMember->getEmail(),
        ]);
    }
}
