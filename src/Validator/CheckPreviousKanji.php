<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckPreviousKanji extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Le premier kanji de "{{ value }}" doit être identique au dernier kanji du mot précédent.';
}
