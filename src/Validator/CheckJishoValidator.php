<?php

namespace App\Validator;

use App\JishoApi\JishoApi;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CheckJishoValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CheckJisho) {
            throw new UnexpectedTypeException($constraint, CheckJisho::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $jisho = new JishoApi($value);

        if(false === $jisho->getJishoExist()){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
