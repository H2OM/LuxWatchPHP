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
        public static function getQuery($request, $FKAAN = false, $COUNT = false) {
            $result = self::$pdo->query($request);
            if($COUNT) return $result->fetch(PDO::FETCH_COLUMN);
            $out = [];
            while($row = $result->fetch()) {
                $FKAAN ? $out[array_shift($row)] = $row : array_push($out, $row);
            }
            if(!is_array($out[array_key_first($out)]))
                $out = [$out];
            return $out;
        }
        public static function getPreparedQuery($request, $parrametrs = [], $count = false, $FKAAN = false) {
            try {
                $state = self::$pdo->prepare($request);
                for($i = 1; $i <= count($parrametrs); $i++) {
                    $state->bindParam(
                        $i, $parrametrs[$i-1]['VALUE'],
                        ($parrametrs[$i-1]['INT'] ?? false) ? PDO::PARAM_INT : PDO::PARAM_STR, $parrametrs[$i-1]['PARAMVALUE'] ?? null);
                }
                $state->execute();
                $result = [];
                while($row = $state->fetch()) {
                    $FKAAN ? $result[array_shift($row)] = $row : array_push($result, $row);
                }
                if(count($result) == 1) $result = $result[0];
                if($count) $result = $result[array_key_first($result)];
                return $result;
            } catch (\PDOException $e) {
                $_SESSION['error'] = ("Не удалось связать параметры PDO   " . $parrametrs[array_key_first($parrametrs)]["VALUE"] . $e->getMessage());
                throw new \PDOException();
            }
        }
        public static function beginTransaction() {
            self::$pdo->beginTransaction();
        }
        public static function commitTransaction() {
            self::$pdo->commit();
        }
        public static function rollbackTransaction() {
            self::$pdo->rollBack();
        }
    }