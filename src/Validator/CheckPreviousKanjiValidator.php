<?php

namespace App\Validator;

use App\Repository\WordRepository;
use App\Utils\WordSplit;
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

        $inputFirstChar = WordSplit::split($value)['first'];
        $previousLastChar = WordSplit::split($previousEntry)['last'];

        if($previousLastChar && $inputFirstChar !== $previousLastChar){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
