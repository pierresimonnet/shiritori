<?php

namespace App\Validator;

use App\JishoApi\JishoApi;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CheckJishoValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint CheckJisho */

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
