<?php

declare(strict_types=1);

namespace App\Enums;

enum Severidad: string
{
    case Block = 'block';     // impide emitir presupuesto
    case Warning = 'warning'; // avisa, pero se puede continuar

    public function label(): string
    {
        return $this === self::Block ? 'Error' : 'Aviso';
    }
}
