<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsKanjiValidator extends ConstraintValidator
{
    /**
     * @var string
     */
    private $pattern = "/[a-zA-Z0-9０-９あ-んア-ンー。、？！＜＞： 「」（）｛｝≪≫〈〉《》【】『』〔〕［］・\n\r\t\s\(\)　]/u";

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint ContainsKanji */

        if (null === $value || '' === $value) {
            return;
        }

        if (preg_match($this->pattern, $value)){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
