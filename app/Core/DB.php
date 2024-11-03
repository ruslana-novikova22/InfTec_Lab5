<?php


namespace Core;

use PDO;
use Core\Util;
use Core\Util;

/**
 * Class DB
 */
class DB
{

    /**
     * @var null
     */
    private static $pdo = null;

    /**
     * @return null|PDO
     */
    public function getConnection()
    {
        if (!self::$pdo) {
            $dsn = 'mysql:host=' . MYSQL_HOST . ';port=' . MYSQL_PORT . ';dbname=' . DB_NAME . ';charset=utf8';
            $dsn = 'mysql:host=' . MYSQL_HOST . ';port=' . MYSQL_PORT . ';dbname=' . DB_NAME . ';charset=utf8';
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            );
            try {
                self::$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
                self::$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                echo "Connection failed: " . $e->getMessage();
                exit();
            }
        }
        return self::$pdo;
    }

    /**
     * @param $sql
     * @param array $parameters
     * @return array|bool
     */
    public function query($sql, $parameters = [])
    {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare($sql);
        $result = $stmt->execute($parameters);

        if ($result !== false) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * @param $sql
     * @param array $parameters
     */
    public function exec($sql, $parameters = [])
    {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare($sql);
        return $stmt->execute($parameters);
    }


    public function deleteEntity(DbModelInterface $model, int $id)
    {

        $dbh = $this->getConnection();
        $sql = sprintf(
            "DELETE FROM %s WHERE %s = ?",
            $model->getTableName(),
            $model->getPrimaryKeyName()
        );
        $statement = $dbh->prepare($sql);

        return $statement->execute(array($id));
    }

    public function updateEntity(DbModelInterface $model, int $id, $values = [])
    {
        $dbh = $this->getConnection();
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s = ?;",
            $model->getTableName(),
            Util::arrayToList(array_keys($values), "%s = ?"),
            $model->getPrimaryKeyName()
        );

        $statement = $dbh->prepare($sql);

        $parameters = array_merge(array_values($values), array($id));

        return $statement->execute($parameters);
    }

    public function createEntity(DbModelInterface $model, $values = [])
    {
        $dbh = $this->getConnection();
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s);",
            $model->getTableName(),
            Util::arrayToList(array_keys($values), "%s"),
            Util::arrayToList($values, "?")
        );
        $statement = $dbh->prepare($sql);

        if ($statement->execute(array_values($values))) {
            $sql = sprintf(
                "SELECT %s FROM %s ORDER BY %s DESC LIMIT 1; ",
                $model->getPrimaryKeyName(),
                $model->getTableName(),
                $model->getPrimaryKeyName()
            );
            $result = $this->query($sql);
            if ($result){
                return $result[0][$model->getPrimaryKeyName()];
            }
        }
        return false;
    }
}