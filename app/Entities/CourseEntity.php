<?php

namespace App\Entities;

use InvalidArgumentException;

class CourseEntity
{
    private ?int $id;
    private int $instructorId;
    private string $title;
    private string $description;
    private float $price;
    private string $level;
    private int $durationHours;
    private bool $isPublished;

    public function __construct(
        int $instructorId,
        string $title,
        string $description,
        float $price,
        string $level,
        int $durationHours,
        bool $isPublished = false,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->setInstructorId($instructorId);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setPrice($price);
        $this->setLevel($level);
        $this->setDurationHours($durationHours);
        $this->isPublished = $isPublished;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstructorId(): int
    {
        return $this->instructorId;
    }

    public function setInstructorId(int $instructorId): void
    {
        if ($instructorId <= 0) {
            throw new InvalidArgumentException('Instructor ID must be positive');
        }
        $this->instructorId = $instructorId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $trimmed = trim($title);
        if (empty($trimmed)) {
            throw new InvalidArgumentException('Title cannot be empty');
        }
        if (strlen($trimmed) < 3) {
            throw new InvalidArgumentException('Title must be at least 3 characters');
        }
        if (strlen($trimmed) > 255) {
            throw new InvalidArgumentException('Title cannot exceed 255 characters');
        }
        $this->title = $trimmed;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $trimmed = trim($description);
        if (empty($trimmed)) {
            throw new InvalidArgumentException('Description cannot be empty');
        }
        if (strlen($trimmed) < 10) {
            throw new InvalidArgumentException('Description must be at least 10 characters');
        }
        $this->description = $trimmed;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        if ($price < 0) {
            throw new InvalidArgumentException('Price cannot be negative');
        }
        if ($price > 9999.99) {
            throw new InvalidArgumentException('Price cannot exceed 9999.99');
        }
        $this->price = round($price, 2);
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function setLevel(string $level): void
    {
        $validLevels = ['beginner', 'intermediate', 'advanced'];
        if (!in_array($level, $validLevels)) {
            throw new InvalidArgumentException('Invalid level. Must be: ' . implode(', ', $validLevels));
        }
        $this->level = $level;
    }

    public function getDurationHours(): int
    {
        return $this->durationHours;
    }

    public function setDurationHours(int $durationHours): void
    {
        if ($durationHours < 0) {
            throw new InvalidArgumentException('Duration cannot be negative');
        }
        if ($durationHours > 500) {
            throw new InvalidArgumentException('Duration cannot exceed 500 hours');
        }
        $this->durationHours = $durationHours;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function publish(): void
    {
        $this->isPublished = true;
    }

    public function unpublish(): void
    {
        $this->isPublished = false;
    }

    public function isFree(): bool
    {
        return $this->price === 0.0;
    }

    public function isForBeginners(): bool
    {
        return $this->level === 'beginner';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'instructor_id' => $this->instructorId,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'level' => $this->level,
            'duration_hours' => $this->durationHours,
            'is_published' => $this->isPublished,
        ];
    }
}