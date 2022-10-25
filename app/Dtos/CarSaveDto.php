<?php


namespace App\Dtos;


class CarSaveDto
{
    public string $name;
    public ?string $model;
    public ?int $price;
    public ?string $description;
}
