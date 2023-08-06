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
        public static function getPreparedQuery($request, $parrametrs = []) {
            try {
                $state = self::$pdo->prepare($request);
                for($i = 1; $i <= count($parrametrs); $i++) {
                    $state->bindParam($i, $parrametrs[$i-1]['VALUE'], PDO::PARAM_STR, $parrametrs[$i-1]['PARAMVALUE']);
                }
                $state->execute();
                $result = [];
                while($row = $state->fetch()) {
                    array_push($result, $row);
                }
                if(count($result) == 1) $result = $result[0];
                return $result;
            } catch (\PDOException $e) {
                throw new \PDOException("Не удалось связать параметры PDO   " . $e->getMessage());
            }
            
        }
    }