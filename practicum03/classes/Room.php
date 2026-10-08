<?php

class Room {
    protected string $number;
    protected int $capacity;
    protected float $pricePerNight;
    protected bool $isBooked;

    public function __construct(string $number, int $capacity, float $pricePerNight, bool $isBooked = false) {
        $this->number = $number;
        $this->capacity = $capacity;
        $this->pricePerNight = $pricePerNight;
        $this->isBooked = $isBooked;
    }

    public function getInfo(): string {
        $status = $this->isBooked ? 'Заброньовано' : 'Вільний';
        return "Кімната {$this->number} (Місткість: {$this->capacity} чол., Ціна: {$this->pricePerNight} грн/ніч) - <strong>{$status}</strong>";
    }

    public function isAvailable(): bool {
        return !$this->isBooked;
    }

    public function getPrice(): float {
        return $this->pricePerNight;
    }
}