<?php declare(strict_types=1);
namespace App\Service\Config;

class Constants
{
    // ini
    public const DATABASE = 'db';
    public const MAIL = 'smtp';
    public const RECAPTCHA = 'reCaptcha';

    // actions
    public const LOG_IN = 'log';
    public const REGISTER = 'reg';
    public const CHANGE_PASSWORD = 'changePassword';

    public const TASK = 'task';
    public const CREATE_TASK = 'createTask';
    public const EDIT_TASK = 'editTask';
    public const SEND_TASK = 'sendTask';

    //modules
    public const FORM = 'Form';
    public const ASSIGNMENTS = 'Assignments';
    public const FILTER_SANITIZE = 'filter_sanitize';
    public const FILTER_VALIDATE = 'filter_validate';

    //builders
    public const FORM_TEMPLATE = 'form';
    public const FORM_WRAPPER = 'Wrapper';
 }