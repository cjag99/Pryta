<?php
class Team
{
    public function __construct(
        private int $id,
        private string $name,
        private ?string $description = null,
        private ?DateTimeImmutable $creation_date = null,
        private ?int $team_leader = null,
        private bool $is_available = true
    ) {
        $this->creation_date = $creation_date ?? new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreationDate(): ?string
    {
        return $this->creation_date?->format('Y-m-d');
    }

    public function getTeamLeader(): ?int
    {
        return $this->team_leader;
    }

    public function isAvailable(): bool
    {
        return $this->is_available;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setCreationDate(?DateTimeImmutable $creation_date): void
    {
        $this->creation_date = $creation_date ?? new DateTimeImmutable();
    }

    public function setTeamLeader(?int $team_leader): void
    {
        $this->team_leader = $team_leader;
    }

    public function setIsAvailable(bool $is_available): void
    {
        $this->is_available = $is_available;
    }
}
