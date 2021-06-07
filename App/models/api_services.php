<?php

class api_services
{
    public $database;

    public function __construct()
    {
        $this->database = new Database();

    }

    /**
     * @return mixed
     */
    public function Read_All_Users()
    {
        $sql = /** @lang text */
            'select * from users';
        $this->database->query($sql);
        return $this->database->fetch_all_as_obj();
    }

    /**
     * @param $id
     */
    public function Read_User($id)
    {
        $sql = /** @lang text */
            'select * from users where id=:id';
        $this->database->query($sql);
        $this->database->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->database->fetch_all_as_obj();
    }

    public function Delete_All_Users()
    {
        $sql = /** @lang text */
            'delete from users';
        $this->database->query($sql);
        return $this->database->execute();
    }

    public function DeleteUser($id)
    {
        //todo:verify id user is in database :
        $sql = /** @lang text */
            'delete from users where id=:id';
        $this->database->query($sql);
        $this->database->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->database->execute();
    }

}