<?php

namespace App\Validator;

use App\Repository\WordRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckPreviousEntryValidator extends ConstraintValidator
{
    /**
     * @var WordRepository
     */
    private $wordRepository;

    public function __construct(WordRepository $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint CheckPreviousEntry */

        if (null === $value || '' === $value) {
            return;
        }

        $currentShiritori = $this->context->getObject()->getShiritori();
        $previousEntry = null;
        if (null !== $this->wordRepository->findLastWord($currentShiritori)){
            $previousEntry = $this->wordRepository->findLastWord($currentShiritori)->getWord();
        }

        if ($value === $previousEntry){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
