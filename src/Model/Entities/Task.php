<?php
require_once __DIR__ . '/TaskState.php';
class Task
{
    public function __construct(
        private int $id,
        private string $name,
        private ?string $description = null,
        private string $state = TaskState::NOT_ASSIGNED->value,
        private ?DateTimeImmutable $started_on = null,
        private ?DateTimeImmutable $due_date = null,
        private int $project_id,
        private ?int $member_assigned = null
    ) {}


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

    public function getDescriptionOrNull(): string
    {
        return $this->description ?? '';
    }

    public function setState(string $state): void
    {
        if (!in_array($state, array_map(fn($s) => $s->value, TaskState::cases()))) {
            throw new \InvalidArgumentException('Invalid task state');
        }
        $this->state = $state;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setStartedOn(?DateTimeImmutable $started_on): void
    {
        $this->started_on = $started_on;
    }

    public function getStartedOn(): ?DateTimeImmutable
    {
        return $this->started_on;
    }

    public function setDueDate(?DateTimeImmutable $due_date): void
    {
        $this->due_date = $due_date;
    }

    public function getDueDate(): ?DateTimeImmutable
    {
        return $this->due_date;
    }

    public function setProjectId(int $project_id): void
    {
        $this->project_id = $project_id;
    }

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function setMemberAssigned(?int $member_assigned): void
    {
        $this->member_assigned = $member_assigned;
    }

    public function getMemberAssigned(): ?int
    {
        return $this->member_assigned;
    }
}
