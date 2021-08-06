<?php


namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsFormatYoutube extends Constraint
{
    public string $message = 'The link "{{ string }}" contains an illegal format.';
}
