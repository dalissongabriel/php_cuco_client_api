<?php


namespace App\Service;


interface EntityFactoryInterface
{
    public function create(string $requestContent);
}