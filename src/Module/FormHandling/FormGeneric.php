<?php
namespace App\Module\FormHandling;

use App\Service\Filter\Filter;
use App\Service\Logger\MessageSheme;
use App\Repository\BaseRepository;
use App\Service\Logger\Logger;
use App\Service\Token\Token;

abstract class FormGeneric extends Observable implements FormInterface
{
    static string $PATH_500;

    private string $recaptcha_secret;

    const PROCESS_STATUS = ["errors", "correct" , "session"];
    protected ?string $processStatus = NULL;

    protected $repository;

    protected array $data;  // raw array
    protected $object;     // entity result object
    protected array $errors = [];     // valid / script errors

    protected Logger $logger;

    public function __construct(array $formData, BaseRepository $repository)
    {
        $this->data = $formData;
        $this->repository = $repository;

        $this->logger = new Logger();

        if (!isset(self::$PATH_500) || empty(self::$PATH_500))
            self::$PATH_500 = $_SERVER['REQUEST_SCHEME']. "://" . $_SERVER['HTTP_HOST'] . "/templates/error/500.php";

    }

        // main method
    public function handler(?string $serverToken = NULL, array $filter, array $assignments): bool{
        try{
            if($this->checkToken($serverToken)){

                if(!$this->reCaptcha($this->data['recaptchaResponse']))
                {
                    throw new \RuntimeException("recaptcha error");
                }

                if (!$this->validData($filter, $assignments))
                {
                    $this->processStatus = self::PROCESS_STATUS[0];
                }

                $this->doHandler();

                $this->notify();

                if (empty($this->errors))
                    return TRUE;
            }
        }catch (\Exception $e){
            $config = new MessageSheme($_SERVER['REMOTE_ADDR'], static::class, __FUNCTION__);
            $this->logger->error($e->getMessage(), [$config]);

            header("Location: " . self::$PATH_500);
            die();
        }
        return FALSE;
    }

    abstract protected function doHandler();

            // generic methods
    public function checkToken(?string $serverToken = NULL): bool{
        if(!isset($serverToken))
            throw new \RuntimeException("token doesn't exists on server side ://");

        if(sodium_compare(
                (new Token($serverToken))->hash()->getToken(),
                (new Token($this->data['hidden']))->hexToBin()->getToken()
            ) !== 0
        ) throw new \RuntimeException('detected cross-site attack on login form');

        unset($this->data['hidden']);
        //unset($serverToken);    // when arg is passing by reference -> doesn't work --- WHY ???

        return TRUE;
    }

    private function reCaptcha(string $recaptchaValue):bool
    {
        // Make and decode POST request:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_response = $recaptchaValue;

        $data = array('secret' => $this->recaptcha_secret, 'response' => $recaptcha_response);

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($recaptcha_url, false, $context);
        $responseKeys = json_decode($response,true);


        if ($responseKeys["success"]) {
            return TRUE;
        } else {

            $config = new MessageSheme($_SERVER['REMOTE_ADDR'], __CLASS__, __FUNCTION__);
            $this->logger->error(implode("|",$responseKeys["error-codes"]), [$config]);
            return FALSE;
        }
        // Take action based on the score returned: $responseKeys->score >= 0.5
    }

    public function validData(array $filter, array $assignments): bool{
        $filter = new Filter($filter, $assignments);
        $filter->process($this->data);

        foreach ($filter->getMessages() as $key => $value)
        {
            $this->errors[$key] = $value;
        }

        if(!empty($this->errors))
            return FALSE;

        return TRUE;
    }


    // hermetical methods
    public function getProcessStatus(): string{
        return $this->processStatus;
    }
    public function getErrors(): array{
        return $this->errors;
    }
    public function getObject() {
        return $this->object;
    }

    // --------------------------------------
    public function setRecaptchaKey(string $key)
    {
        $this->recaptcha_secret = $key;
    }
}