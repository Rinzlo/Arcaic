<?php

declare(strict_types=1);

namespace App\Models;

use App\Token;
use PDO;
use Core\Model;

/**
 * @property string remember_token
 * @property float|int expirey_timestamp
 */
class User extends Model
{

    /**
     * @var array
     */
    public $errors = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value){
            $this->$key = $value;
        };
    }

    /**
     * expects to use attributes:
     * username,
     * email,
     * password
     */
    public function save(): bool
    {
        $this->validate();

        if(!empty($this->errors)){
            return false;
        }

        $hash = password_hash($this->password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (username, email, password)
                VALUES (:username, :email, :hash)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':hash', $hash, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * validate the user's creation information:
     * username,
     * email,
     * password,
     * password_confirmation
     */
    public function validate(): void
    {
        if($this->username == ''){
            $this->errors[] = 'Name is required';
        }

        if(filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }
        if(static::emailExists($this->email)){
            $this->errors[] = 'email already taken';
        }

        if(strlen($this->password) < 6){
            $this->errors[] = 'Please enter at least 6 characters for the password';
        }

        if(preg_match('/.*[a-z]+.*/i', $this->password) == 0){
            $this->errors[] = 'Password needs at least one letter';
        }

        if(preg_match('/.*\d+.*/i', $this->password) == 0){
            $this->errors[] = 'Password needs at least one number';
        }
    }

    /**
     * @param $email
     * @return bool
     */
    public static function emailExists(string $email): bool
    {
        return static::findByEmail($email) !== false;
    }
	
	/**
	 * @param string $email
	 * @return mixed
	 */
    public static function findByEmail(string $email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
	
	/**
	 * Find a user model by ID
	 *
	 * @param $id The user ID
	 * @return mixed User object if found, false otherwise
	 */
    public static function findByID($id)
    {
    	$sql = 'SELECT * FROM users WHERE id = :id';
    	
    	$db = static::getDB();
    	$stmt = $db->prepare($sql);
    	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
    	
    	$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
    	
    	$stmt->execute();
    	
    	return $stmt->fetch();
    }
	
	/**
	 * @param string $email
	 * @param string $password
	 * @return bool|mixed
	 */
    public static function authenticate(string $email, string $password)
    {
        $user = static::findByEmail($email);

        if($user){
            if(password_verify($password, $user->password)){
                return $user;
            }
        }

        return false;
    }
    
	public function rememberLogin(): bool
    {
    	$token = new Token();
    	$hashed_token = $token->getHash();
    	$this->remember_token = $token->getValue();
    	
    	$this->expirey_timestamp = time() + 60 * 60 * 24 * 30;    // 30 days from now
	    
	    $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
				VALUES (:token_hash, :user_id, :expires_at)';
	    
	    $db = static::getDB();
	    $stmt = $db->prepare($sql);
	    
	    $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
	    $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
	    $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expirey_timestamp), PDO::PARAM_STR);
	    
	    return $stmt->execute();
    }
}