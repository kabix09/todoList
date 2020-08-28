<?php declare(strict_types=1);
namespace App\Connection;

class QueryBuilder
{
    private static $classInstance = NULL;
    private static $sql = "";
    private static $prefix = "";
    private static $where = array();
    private static $suffix = "";

    public static function select(string $table, array $columns = array()) : self {
        self::$classInstance = new QueryBuilder();

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

    public static function where(?string $a = NULL) : self{
        self::$where[0] = " WHERE " . $a;
        return self::$classInstance;
    }

    public static function like(string $a, string $b) : self{
        self::$where[] = trim($a . " LIKE " . $b);
        return self::$classInstance;
    }

    public static function and(?string $a = NULL) : self{
        self::$where[] = trim(" AND " . $a);
        return self::$classInstance;
    }

    public static function or(?string $a = NULL) : self{
        self::$where[] = trim(" OR " . $a);
        return self::$classInstance;
    }

    public static function in(string $a, array $values) : self{
        self::$where[] = trim($a . " IN (" . implode(" ", $values) . ")");
        return self::$classInstance;
    }

    public static function orderBy(string $orderBy = "ASC"){
        self::$suffix = trim("ORDER BY " . $orderBy);
        return self::$classInstance;
    }

    public static function getSQL() : string {
        self::$sql = self::$prefix . implode(" ", self::$where) . " " . self::$suffix;
        return trim(self::$sql);
    }
}