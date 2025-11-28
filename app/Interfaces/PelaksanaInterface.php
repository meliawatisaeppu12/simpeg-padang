<?php

namespace App\Interfaces;

interface PelaksanaInterface
{
    public function getId(): int;
    public function getNama(): string;
    public function getJabatan(): string;
    public function getType(): string;
    public function getIdentifier(): string;
}