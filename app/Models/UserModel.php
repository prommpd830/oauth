<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
	protected $table ='users';
	protected $primaryKey = 'id';
	protected $DBGroup = 'default';
	protected $allowedFields = ['oauth_id', 'user_username', 'user_email', 'user_password', 'user_img', 'updated_at', 'created_at'];

	function cekLogin($email)
    {
        $query = $this->table($this->table)
                ->where('user_email', $email)
                ->countAll();
 
        if($query > 0){
            $hasil = $this->table($this->table)
                    ->where('user_email', $email)
                    ->limit(1)
                    ->get()
                    ->getRowArray();
        } else {
            $hasil = array(); 
        }
        return $hasil;
    }

	function isAlreadyRegister($authid){
		return $this->db->table('users')->getWhere(['oauth_id'=>$authid])->getRowArray() > 0 ? true : false;
	}

	function updateUserData($userdata, $authid){
		$this->db->table("users")->where(['oauth_id'=>$authid])->update($userdata);
	}
	
	function insertUserData($userdata){
		$this->db->table("users")->insert($userdata);
	}
}