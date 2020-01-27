<?php

namespace App\Validator;

use App\Entity\Word;
use App\Repository\WordRepository;
use App\Utils\WordSplit;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

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

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CheckPreviousKanji) {
            throw new UnexpectedTypeException($constraint, CheckPreviousKanji::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if($this->context->getObject() instanceof Word){
            $currentShiritori = $this->context->getObject()->getShiritori();
            if(null !== $currentShiritori){
                $previousEntry = $this->wordRepository->findLastWord($currentShiritori)->getWord();
                if(null !== $previousEntry){
                    $inputFirstChar = WordSplit::split($value)['first'];
                    $previousLastChar = WordSplit::split($previousEntry)['last'];

                    if($inputFirstChar !== $previousLastChar){
                        $this->context->buildViolation($constraint->message)
                            ->setParameter('{{ value }}', $value)
                            ->addViolation();
                    }
                }
            }
        }
    }
}
