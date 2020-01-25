<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckPreviousKanji extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Le premier kanji de "{{ value }}" doit être identique au dernier kanji du mot précédent.';
}
