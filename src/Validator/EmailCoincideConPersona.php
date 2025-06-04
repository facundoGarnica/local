<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class EmailCoincideConPersona extends Constraint
{
    public string $message = 'El email del usuario "{{ email }}" no coincide con el email de la persona asociada "{{ persona_email }}".';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}

