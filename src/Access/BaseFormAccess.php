<?php
namespace App\Access;

use App\Connection\Connection;
use App\Session\Session;
use App\Token\Token;

abstract class BaseFormAccess extends BaseAccess implements FormStatus
{
    static string $PATH_404;

    protected Session $session;
    private Connection $connection;

    protected $mainLogicObject;
    private $templateScriptPath;

    public function __construct(Session $session, Connection $connection)
    {
        parent::__construct($session);

        $this->connection = $connection;

        if (!isset(self::$PATH_404) || empty(self::$PATH_404))
            self::$PATH_404 = $_SERVER['REQUEST_SCHEME']. "://" . $_SERVER['HTTP_HOST'] . "/templates/error/404.php";

    }

    public function setTemplatePath(string $templatePath)
    {
        $this->templateScriptPath = $templatePath;
    }

    public function generateToken() : void
    {
        if (!isset($_POST['hidden']))
            $this->session['token'] = (new Token())->generate()->binToHex()->getToken();
    }

    abstract protected function clearErrors(): void;

    private function catchData(): array
    {
        $formData = [];
        foreach($_POST as $key => $value)
            $formData[$key] = urldecode($value);

        return $formData;
    }

    abstract protected function setupObserverLogic(array $formData, Connection $connection): void;

    public function core()
    {
        switch($_SERVER['REQUEST_METHOD'])
        {
            case static::STATUS[self::GET]:
            {
                return include $this->templateScriptPath;
                break;
            }

            case self::STATUS[self::POST]:
            {
                // 1) clear form errors
                $this->clearErrors();

                // 2) unset for-sure button
                unset($_POST['submit']);

                // 3) fetch data
                $formData = $this->catchData();

                // with this, key is regenerated every time after page refresh
                unset($_POST);

                // 4) setup observer and observers
                $this->setupObserverLogic($formData, $this->connection);

                $this->main($formData);
                break;
            }

            default:
            {
                header("Location: " . self::$PATH_404);
                break;
            }
        }
        exit();
    }

    abstract protected function main(array $queryParams): void;
}