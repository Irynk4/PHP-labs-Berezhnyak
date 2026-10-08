<?php

function formatDateRange(string $startDate, string $endDate): string {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    return $start->format('d.m.Y') . ' - ' . $end->format('d.m.Y');
}

function nightsBetween(string $startDate, string $endDate): int {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    return (int)$interval->format('%a');
}