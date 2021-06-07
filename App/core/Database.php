<?php

class Database
{
    private $user = USER;
    private $host = HOST;
    private $password = PASSWORD;
    private $database = DATABASE;
    private $database_command;
    private $stmt;
    private $error;

    public function __construct()
    {
        // set dsn :
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->database;
        $options = array(PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        try {
            $this->database_command = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /**
     * @param $sql
     */
    public function query($sql)
    {
        $this->stmt = $this->database_command->prepare($sql);
    }

    /**
     * @param $placeholder
     * @param $value
     * @param null $type
     */
    public function bind($placeholder, $value, $type = null)
    {
        if (is_null($type)) $type = match (true) {
            is_int($value) => PDO::PARAM_INT,
            is_bool($value) => PDO::PARAM_BOOL,
            is_string($value) => PDO::PARAM_STR,
            default => PDO::PARAM_NULL,
        };
        $this->stmt->bindValue($placeholder, $value, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function fetch_all_as_obj()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetch_all_as_arr()
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function all_column()
    {
        $sql = 'show columns from users';
        $this->query($sql);
        return $this->fetch_all_as_obj();
    }
}