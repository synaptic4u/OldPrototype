<?php

namespace Synaptic4U\Core;

use Exception;
use PDO;
use PDOException;

class DB
{
    protected $lastinsertid = -1;
    protected $rowcount = -1;
    protected $conn;
    protected $status;

    public function __construct(int $log = null)
    {
        try {
            $root = dirname(__FILE__, 1);
            $filepath = $root.'/db.json';
            if (1 === (int) $log) {
                $filepath = $root.'/db_logs.json';
            }

            // $db_json = file_get_contents($root.$app."db.json");
            $this->conn = json_decode(file_get_contents($filepath), true);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Exception' => $e->__toString(),
            ]);
        }
    }

    public function db(): mixed
    {
        try {
            $dsn = 'mysql:host='.$this->conn['host'].';dbname='.$this->conn['dbname'];

            // Create PDO
            if ($pdo = new PDO($dsn, $this->conn['user'], $this->conn['pass'])) {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } else {
                throw new Exception();
            }
        } catch (PDOException $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Error' => $e->getMessage(),
            ]);

            // exit('<div style="width: 400px; vertical-align:middle;margin: 25% auto;text-align: center;"><h4 style="font-size:20px;color: grey">Something\'s wrong! It\'s not you, it\'s us.</h4></div>');

            $pdo = null;
        }

        return $pdo;
    }

    public function query($params, $sql, string $method = ''): mixed
    {
        try {
            $pdo = null;
            $stmt = null;
            $result = null;

            // $this->log([
            //     'Location' => __METHOD__.'(): 0',
            //     'Calling Method' => $method,
            //     'params' => json_encode($params),
            //     'sql' => $sql,
            // ]);

            $pdo = $this->db();

            $pdo->beginTransaction();

            $stmt = $pdo->prepare($sql);

            $this->status = ($stmt->execute($params)) ? 'true' : 'false';

            $this->lastinsertid = $pdo->lastInsertId();

            $pdo->commit();

            $this->rowcount = $stmt->rowCount();

            $result = $stmt->fetchAll();

            ob_start();
            $stmt->debugDumpParams();
            $debug = ob_get_contents();
            ob_end_clean();

            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'Calling Method' => $method,
            //     'params' => json_encode($params),
            //     'debug' => $debug,
            //     '$pdo->lastInsertId()' => $this->getLastId(),
            //     '$stmt->rowCount()' => $this->getrowCount(),
            //     'status' => $this->status,
            // ]);
        } catch (PDOException $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Calling Method' => $method,
                'Params' => $params,
                'sql' => $sql,
                'Error' => $e->getMessage(),
                'pdo->errorCode()' => $pdo->errorCode(),
                'pdo->errorInfo()' => json_encode($pdo->errorInfo()),
            ]);

            $pdo = null;
            $stmt = null;
            $result = null;
            unset($stmt, $pdo);

            // exit('<div style="width: 400px; vertical-align:middle;margin: 25% auto;text-align: center;"><h4 style="font-size:20px;color: grey">Something\'s wrong! It\'s not you, it\'s us.</h4></div>');
        } finally {
            $pdo = null;
            $stmt = null;
            unset($stmt, $pdo);

            return $result;
        }
    }

    public function callProc($params, $sql, string $method = ''): mixed
    {
        try {
            $pdo = null;
            $stmt = null;
            $result = null;

            $pdo = $this->db();

            $pdo->beginTransaction();

            $stmt = $pdo->prepare($sql);
            $stmt->closeCursor();

            $this->status = ($stmt->execute($params)) ? 'true' : 'false';

            // $this->log([
            //     'Location' => __METHOD__.'(): 0',
            //     'Calling Method' => $method,
            //     'params' => json_encode($params),
            //     'sql' => $sql,
            // ]);

            // $this->lastinsertid = $pdo->lastInsertId();

            // $pdo->commit();

            $this->rowcount = $stmt->rowCount();

            // $result = $stmt->fetchAll();
            do {
                $result[] = $stmt->fetchAll();
            } while ($stmt->nextRowset());

            ob_start();
            $stmt->debugDumpParams();
            $debug = ob_get_contents();
            ob_end_clean();

            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'Calling Method' => $method,
            //     'debug' => $debug,
            //     'result' => json_encode($result[0], JSON_PRETTY_PRINT),
            //     'params' => json_encode($params),
            //     '$pdo->lastInsertId()' => $this->getLastId(),
            //     '$stmt->rowCount()' => $this->getrowCount(),
            //     'status' => $this->status,
            // ]);
        } catch (PDOException $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Calling Method' => $method,
                'Params' => $params,
                'sql' => $sql,
                'Error' => $e->getMessage(),
                'pdo->errorCode()' => $pdo->errorCode(),
                'pdo->errorInfo()' => json_encode($pdo->errorInfo()),
            ]);

            $pdo = null;
            $stmt = null;
            $result = null;
            unset($stmt, $pdo);

            // exit('<div style="width: 400px; vertical-align:middle;margin: 25% auto;text-align: center;"><h4 style="font-size:20px;color: grey">Something\'s wrong! It\'s not you, it\'s us.</h4></div>');
        } finally {
            $pdo = null;
            $stmt = null;
            unset($stmt, $pdo);

            return $result[0];
        }
    }

    public function getLastId()
    {
        return $this->lastinsertid;
    }

    public function getrowCount()
    {
        return $this->rowcount;
    }

    public function getStatus()
    {
        return $this->status;
    }

    // Database table cleaning - Cleans everything older than 120 minutes.
    public function cleanStream($userid = null)
    {
        // $sql = 'delete
        // 		  from stream
        // 		 where case when isnull(?) then (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(DatedOn)) > 7200 else userid = ? end';

        // $result = $this->query([$userid, $userid], $sql);
    }

    public function cleanMyKeys($userid = null)
    {
        // $sql = 'delete
        // 		  from mykeys
        //           where case when isnull(?) then (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(DatedOn)) > 7200 else userid = ? end';

        // $result = $this->query([$userid, $userid], $sql);
    }

    protected function error($msg)
    {
        new Log($msg, 'error', 1);
    }

    protected function log($msg)
    {
        new Log($msg, 'database', 1);
    }
}
