<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	/**
  * Authenticates a user.
  * Authenticate against our Database
  * @return boolean whether authentication succeeds.
  */
        private $_id;
        private $_user;
        
        public function getId()
        {
           return $this->_id;
        }
        public function authenticate()
        {
            $t_hasher = new PasswordHash(8, FALSE);
            $_user= Users::model()->findByAttributes(array('username'=>  $this->username));
            if($_user === null)
            {
                $this->errorCode= self::ERROR_UNKNOWN_IDENTITY;
            }
            elseif($t_hasher->CheckPassword($this->password, $_user->password))
            {
                $this->_id = $_user->id;
                $this->setState('username', $_user->username);
                $this->errorCode= self::ERROR_NONE;
                 
            }
            else
            {
                $this->errorCode= self::ERROR_PASSWORD_INVALID;
            }
            return !$this->errorCode;
                 
        }
        public function isAdmin(){
            return $_user->role;
        }
        
}