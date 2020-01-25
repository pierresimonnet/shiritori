<?php

namespace App\Validator;

use App\JishoApi\JishoApi;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckJishoValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\CheckJisho */

        if (null === $value || '' === $value) {
            return;
        }

        $jisho = new JishoApi($value);
        $data = null;
        if(null !== $jisho->getJishoResult()){
            $data = $jisho->getJishoResult()[0]['japanese'][0]['word'];
        }

        if($value !== $data){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
