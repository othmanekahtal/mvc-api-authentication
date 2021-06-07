<?php

class api_services
{
    public $database;

    /**
     * api_services constructor.
     */
    public function __construct()
    {
        $this->database = new Database();

    }
    

    public function Read_All_Users()
    {
//        die($this->generateTokren());
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

    /**
     * @return mixed
     */
    public function Delete_All_Users()
    {
        $sql = /** @lang text */
            'delete from users';
        $this->database->query($sql);
        return $this->database->execute();
    }

    /**
     * @param $id
     * @return false
     */
    public function DeleteUser($id)
    {
        //todo:verify id user is in database :
        if ($this->verifyByID($id)) {
            $sql = /** @lang sql */
                'delete from users where id=:id';
            $this->database->query($sql);
            $this->database->bind(':id', (int)$id, PDO::PARAM_INT);
            return $this->database->execute();
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function insertUser($data)
    {
        $sql = /** @lang text */
            'insert into users (email,username,fullname) values (:email,:username,:fullname)';
        $this->database->query($sql);
        $this->database->bind(':email', $data->email, PDO::PARAM_STR);
        $this->database->bind(':username', $data->username, PDO::PARAM_STR);
        $this->database->bind(':fullname', $data->fullname, PDO::PARAM_STR);
        return $this->database->execute();
    }

    /**
     * @param $email
     * @return int
     */
    public function verfifyByEmail($email)
    {
        $sql = /** @lang text */
            'select * from users where email=:email';
        $this->database->query($sql);
        $this->database->bind(':email', $email, PDO::PARAM_STR);
        return count($this->database->fetch_all_as_arr());
    }

    /**
     * @param $id
     * @return int
     */
    public function verifyByID($id): int
    {
        $sql = /** @lang sql */
            'select * from users where id=:id';
        $this->database->query($sql);
        $this->database->bind(':id', $id);
        return count($this->database->fetch_all_as_arr());
    }

    /**
     * @param $data
     */
    public function updateUser($data)
    {
        $column = array();
        foreach ($this->database->all_column() as $key) {
            array_push($column, $key->Field);
        }
        if ($data->id) {
            if ($this->verifyByID($data->id)) {
                $sql = 'update users set';
                $keys = '';
                foreach ($data as $key => $value) {
                    if ($key != 'id') {
                        if (in_array($key, $column)) {
                            $keys .= " $key=:$key,";
                        } else {
                            die('FAILED,YOU ENTER PARAMS NOT FOUND IN DATABASE');
                        }
                    }
                }
                $sql = $sql . rtrim($keys, ',') . ' where id=:id';
                $this->database->query($sql);
                foreach ($data as $key => $value) {
                    if (in_array($key, $column)) {
                        $this->database->bind(":$key", $value);
                    }
                }
                return $this->database->execute();
            }
        } else {
            return 'NO_ID';
        }
    }
}