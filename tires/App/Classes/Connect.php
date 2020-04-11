<?php
declare(strict_types=1);

namespace Classes;

class Connect
{
    private static $dbh;

    public function __construct(){

        $params = parse_ini_file('../database.ini');
        if ($params === false) {
            new \Exception("Error reading database configuration file");
        }

        $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
                          $params['host'],
                          $params['port'],
                          $params['database'],
                          $params['user'],
                          $params['password']);

        try {
            if (is_null(self::$dbh)) {
                self::$dbh = new \PDO($conStr);
                self::$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
            return self::$dbh;

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    final private function __clone(){}


    public function query($queryString)
    {
        try {
            $st = self::$dbh->query($queryString, \PDO::FETCH_OBJ);
            //$st->execute();
            return $results = $st->fetchAll();
        } catch
        (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

}