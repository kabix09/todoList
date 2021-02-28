<?php declare(strict_types=1);
namespace App\Service\Connection;

class QueryBuilder
{
    private static $classInstance = NULL;
    private static $sql;
    private static $prefix;
    private static $set;
    private static $where;
    private static $suffix;

    private function __construct()
    {
        self::$sql = "";
        self::$prefix = "";
        self::$set = array();
        self::$where = array();
        self::$suffix = "";
    }

    protected static function init(){
        return new QueryBuilder();
    }

    public static function select(string $table, array $columns = array()) : self {
        self::$classInstance = self::init();

        self::$prefix = "SELECT";
        if(!empty($columns)){
            foreach ($columns as $columnName)
                self::$prefix .= " " . $columnName . ",";

            self::$prefix = substr(self::$prefix, 0, -1);
        }else{
            self::$prefix .= " *";
        }
        self::$prefix .= " FROM " . $table;

        return self::$classInstance;
    }

    public static function insert(string $table, array $columnNames = array()){
        self::$classInstance = self::init();

        self::$prefix = "INSERT INTO " . $table . " (";

        self::$prefix .= implode(", ", $columnNames);

        self::$prefix .=") VALUES (";

        self::$prefix .= implode(", ",
            array_map(
                function($value)
                {
                    return ":" . $value;
                },
                $columnNames
            )) . ")";

        return self::$classInstance;
    }

    public static function update(string $table){
        self::$classInstance = self::init();

        self::$prefix = "UPDATE " . $table;

        return self::$classInstance;
    }

    public static function remove(string $table){
        self::$classInstance = self::init();

        self::$prefix = "DELETE FROM " . $table;

        return self::$classInstance;
    }

    public static function set(array $data = array()){
        self::$set[0] = " SET ";

        foreach($data as $item){
            self::$set[] = $item . " = :" . $item . ",";
        }

        return self::$classInstance;
    }

    public static function where(?string $a = NULL, ?string $b = NULL) : self{
        self::$where[0] = " WHERE " . $a . " " . $b;
        return self::$classInstance;
    }

    public static function like(string $a, string $b) : self{
        self::$where[] = trim($a . " LIKE " . $b);
        return self::$classInstance;
    }

    public static function and(?string $a = NULL, ?string $b = NULL) : self{
        self::$where[] = trim($b . " AND " . $a);
        return self::$classInstance;
    }

    public static function or(?string $a = NULL, ?string $b = NULL) : self{
        self::$where[] = trim($b . " OR " . $a);
        return self::$classInstance;
    }

    public static function in(string $a, array $values) : self{
        self::$where[] = trim($a . " IN (" . implode(", ", $values) . ")");
        return self::$classInstance;
    }

    public static function orderBy(string $orderBy = "ASC"){
        self::$suffix = trim("ORDER BY " . $orderBy);
        return self::$classInstance;
    }

    public static function getSQL() : string {
        self::$sql = self::$prefix . substr(implode(" ", self::$set), 0 , -1) . " " . implode(" ", self::$where) . " " . self::$suffix;
        return trim(self::$sql);
    }
}