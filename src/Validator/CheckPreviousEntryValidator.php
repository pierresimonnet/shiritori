<?php

namespace App\Validator;

use App\Entity\Shiritori;
use App\Entity\Word;
use App\Repository\WordRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

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
        if (!$constraint instanceof CheckPreviousEntry) {
            throw new UnexpectedTypeException($constraint, CheckPreviousEntry::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if($this->context->getObject() instanceof Word){
            $shiritori = $this->context->getObject()->getShiritori();
            if(null !== $shiritori && $shiritori instanceof Shiritori) {
                if ($this->wordRepository->findOneByWordAndShiritori($value, $shiritori)) {
                    $this->context->buildViolation($constraint->message)
                        ->setParameter('{{ value }}', $value)
                        ->addViolation();
                }
            }
        }
    }
}
