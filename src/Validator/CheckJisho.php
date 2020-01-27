<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckJisho extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Le mot "{{ value }}" n\'est pas dans le dictionnaire.';
}
