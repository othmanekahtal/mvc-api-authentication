<?php

//die('test');

class Api extends Controller
{
    public $db;

    public function __construct()
    {
        $this->db = $this->model('api_services');
    }

    public function users($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (empty($id)) {
                $r = $this->db->Read_All_Users();
                print_r(json_encode($r));
            } else {
                $r = $this->db->Read_User($id[0]);
                if (empty($r)) {
                    print_r(json_encode(["message" => "FAILED,NO USER UNDER ID $id[0]"]));
                } else {
                    $r = array_merge(['message' => 'SUCCESS'], (array)$r[0]);
                    print_r(json_encode($r));
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json);
            print_r(json_encode($data));
        } elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            if (empty($id)) {
                $r = $this->db->Delete_All_Users();
                if ($r) {
                    print_r(json_encode(['message' => "SUCCESS"]));
                } else {
                    print_r(json_encode(['message' => 'FAILED']));
                }
            } else {
                $r = $this->db->DeleteUser($id[0]);
                if ($r) {
                    print_r(json_encode(['message' => "SUCCESS, USER UNDER ID $id[0] DELETED"]));
                } else {
                    print_r(json_encode(['message' => "FAILED,NO USER UNDER ID $id[0]"]));
                }
            }
        }
    }
}