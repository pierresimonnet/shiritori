<?php

namespace App\Validator;

use App\Repository\WordRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckPreviousKanjiValidator extends ConstraintValidator
{
    /**
     * @var WordRepository
     */
    private $wordRepository;

    public function __construct(WordRepository $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint CheckPreviousKanji */

        if (null === $value || '' === $value) {
            return;
        }

        $currentShiritori = $this->context->getObject()->getShiritori();
        $previousEntry = null;
        if (null !== $this->wordRepository->findLastWord($currentShiritori)){
            $previousEntry = $this->wordRepository->findLastWord($currentShiritori)->getWord();
        }
        $inputSplit = preg_split("//u", $value, -1, PREG_SPLIT_NO_EMPTY);
        $inputFirstChar = reset($inputSplit);
        $lastEntrySplit = preg_split("//u", $previousEntry, -1, PREG_SPLIT_NO_EMPTY);

        if($lastEntrySplit && $inputFirstChar !== end($lastEntrySplit)){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
