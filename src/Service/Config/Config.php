<?php declare(strict_types=1);
namespace App\Service\Config;

final class Config
{
    private static string $configPath;
    private static array $configFiles;
    private static array $configItems;
    private static ?Config $_instance = null;

    private function  __construct()
    {
        self::$configPath = $_SERVER['DOCUMENT_ROOT'] . './config/';
        self::$configFiles = self::loadConfigFiles();
        self::$configItems = [];
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function init(): Config
    {
        self::$_instance = new Config();

        return self::$_instance;
    }

    public static function action(string $name) : self  // eg. login, register, task
    {
        self::$configFiles = self::findMatchingPaths($name);

        return self::$_instance;
    }

    public static function module(string $name) : self  // eg. form, assignments, filter, messages
    {
        self::$configPath .= self::findMatchingPaths($name)[0];

        return self::$_instance;
    }

    public static function get(string $name = "") : array
    {
        self::loadConfigItems();

        if($name !== '') {
            $property = self::hasProperty($name);

            return is_array($property) ? $property : [$property];
        }

        return self::$configItems;
    }


    private static function loadConfigFiles() : array
    {
        $configFilesList = [];

        if($handler = opendir(self::$configPath)) {

            while(false !== ($entry = readdir($handler))) {

                if ($entry !== "." && $entry !== "..") {

                    $configFilesList[] = $entry;
                }
            }
            closedir($handler);
        }

        return $configFilesList;
    }

    private static function loadConfigItems(): void
    {
        if(file_exists(self::$configPath)) {
            self::$configItems = include self::$configPath;
        } else {
            throw new \RuntimeException("Incorrect path: ". self::$configPath . ". File doesn't exist!");
        }
    }

    private static function hasProperty(string $property)
    {
        if(!array_key_exists($property, self::$configItems))
        {
            throw new \RuntimeException("Invalid key: " . $property . " was given.");
        }

        return self::$configItems[$property];
    }

    private static function findMatchingPaths(string $name): array
    {
        $subConfigFilesList = [];

        if(!self::isPropertyExist($name)) {
            throw new \RuntimeException("Incorrect " . __FUNCTION__ . " name: " . $name);
        }

        foreach (self::$configFiles as $fileName) {
            if(strpos($fileName, $name) !== false) {
                $subConfigFilesList[] = $fileName;
            }
        }
        return $subConfigFilesList;
    }

    private static function isPropertyExist(string $property) : bool
    {
        foreach (self::$configFiles as $fileName) {
            if(strpos($fileName, $property) !== false) {
                return true;
            }
        }
        return false;
    }
}
