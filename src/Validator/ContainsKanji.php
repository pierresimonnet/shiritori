<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsKanji extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Le mot "{{ value }}" n\'est pas écrit en kanjis.';
}
