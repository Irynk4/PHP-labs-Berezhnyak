<?php

require_once 'Room.php';

class BookingSystem {
    private array $rooms = [];

    public function addRoom(Room $room): void {
        $this->rooms[] = $room;
    }

    public function findAvailable(): array {
        return array_filter($this->rooms, function(Room $room) {
            return $room->isAvailable();
        });
    }

    public function calculateTotal(): float {
        $total = 0;
        foreach ($this->rooms as $room) {
            if (!$room->isAvailable()) {
                $total += $room->getPrice();
            }
        }
        return $total;
    }

    public function getAllRooms(): array {
        return $this->rooms;
    }
}