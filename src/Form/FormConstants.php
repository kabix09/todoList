<?php
namespace App\Form;

class FormConstants
{
    const FORM = 'form';
    const WRAPPER = 'div';
    const INPUT = 'input';
    const LABEL = 'label';
    const ERRORS = 'error';

    const TYPE_TEXT = 'text';
    const TYPE_EMAIL = 'email';
    const TYPE_PASSWORD = 'password';

    const TYPE_RADIO = 'radio';
    const TYPE_SELECT = 'select';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_DATE = 'date';

    const TYPE_BUTTON = 'button';
    const TYPE_FILE = 'file';

    const TYPE_HIDDEN = 'hidden'; // form prevent CSRF attack
    const TYPE_SUBMIT = 'submit';
}