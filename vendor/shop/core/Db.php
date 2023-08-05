<?php
    namespace shop;

    use PDO;

    class Db {
        use Tsingleton;
        public static $pdo;
        public function __construct() {
            $db = require_once CONF . '/config_db.php';       
            try {
                self::$pdo = new PDO($db['dsn'], $db['user'], $db['pass'], $db['opts']);

            } catch (\PDOException $e) {
                throw new \PDOException("Error with data base connection", 500);
            }
        }
        public static function getQuery($request, $FKAAN = false) {
            $result = self::$pdo->query($request);
            $out = [];
            while($row = $result->fetch()) {
                $FKAAN ? $out[array_shift($row)] = $row : array_push($out, $row);
            }
            return $out;
        }
    }