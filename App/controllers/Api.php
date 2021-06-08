<?php


class Api extends Controller
{
    public $db;
    /**
     * @var mixed
     */
    private $_koten;

    public function __construct()
    {
        $this->db = $this->model('api_services');
    }

    /**
     * @param $id
     */

    public function users($id)
    {
        // we stored token in variable,for check it in every request happens!
        if ($this->gettoken()) {
            try {
                $this->verification($this->gettoken());
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                    if (empty($id)) {
                        $r = $this->db->Read_All_Users();
                        print_r(json_encode($r));
                    } else {
                        $r = $this->db->Read_User($id[0]);
                        if (empty($r)) {
                            print_r(json_encode(["message" => "FAILED, NO USER UNDER ID $id[0]"]));
                        } else {
                            $r = array_merge(['message' => 'SUCCESS'], (array)$r[0]);
                            print_r(json_encode($r));
                        }
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $json = file_get_contents('php://input');
                    $data = json_decode($json);
                    if (empty($data)) {
                        print_r(json_encode(['message' => 'FAILED,NO DATA CATCHES!']));
                    } else {
                        if ($this->db->verfifyByEmail($data->email)) {
                            print_r(json_encode(['message' => 'FAILED, ALREADY EMAIL IS SAVED IN DATABASE!']));
                        } else {
                            $r = $this->db->insertUser($data);
                            if ($r) {
                                print_r(json_encode(['message' => 'SUCCESS, USER ADDED!']));
                            } else {
                                print_r(json_encode(['message' => 'FAILED, USER CAN\'T ADDED!']));
                            }
                        }
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
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
                } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                    $json = file_get_contents('php://input');
                    $data = json_decode($json);
                    if (empty($data)) {
                        print_r(json_encode(['message' => 'FAILED,NO DATA CATCHES!']));
                    } else {
                        if ($this->db->verfifyByEmail($data->email)) {
                            print_r(json_encode(['message' => 'FAILED, ALREADY EMAIL IS SAVED IN DATABASE!']));
                        } else {
                            if ($this->db->updateUser($data) === 'NO_ID') {
                                print_r(json_encode(['message' => 'FAILED,ID IS NOT FOUND IN DATABASE']));
                            } elseif ($this->db->updateUser($data) === true) {
                                print_r(json_encode(['message' => "SUCCESS,USER UNDER ID $data->id IS UPDATED"]));
                            } else {
                                print_r(json_encode(['message' => "FAILED,WRONG HAPPENS"]));
                            }
                        }
                    }
                }

            } catch (\Throwable $th) {
                print_r(json_encode("unauthorizedtoken"));
            }

        } else {
            print_r(json_encode("unauthorizedheader"));
        }

    }

    /**
     * @return mixed
     */
    public function generate()
    {
        print_r(json_encode($this->authorization()));
    }
}