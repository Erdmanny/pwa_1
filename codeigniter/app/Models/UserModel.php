<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class UserModel extends Model{
    private $_user;


    public function __construct(){
        $this->db = Database::connect();
        $this->_user = $this->db->table('user');
    }

    public function validatePassword($email, $password){
      $user = $this->_user
            ->select()
            ->where("email", $email)
            ->get()
            ->getFirstRow();
        if (!empty($user) && password_verify($password, $user->password)){
            return $user;
        }
        return false;
    }

    public function createUser($email, $password, $token){
        $data = [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'token' => $token
        ];
        $this->_user->insert($data);
    }


}