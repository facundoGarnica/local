<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailCoincideConPersonaValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof User || !$value->getPersona()) {
            return;
        }

        $emailUsuario = $value->getEmail();
        $emailPersona = $value->getPersona()->getEmail();

        if ($emailUsuario !== $emailPersona) {
            $this->context->buildViolation($constraint->message)
                ->atPath('email')
                ->addViolation();
        }
    }
}
