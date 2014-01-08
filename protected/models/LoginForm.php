<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
                        array('password', 'authenticate'),
                );
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
                {
                        $identity=new UserIdentity($this->username,$this->password);
                        $identity->authenticate();
                        switch($identity->errorCode)
                        {
                                case UserIdentity::ERROR_NONE:
                                        Yii::app()->user->login($identity);
                                        break;
                                case UserIdentity::ERROR_UNKNOWN_IDENTITY:
                                        $this->addError('username','Username is incorrect.');
                                        break;
                                default: // UserIdentity::ERROR_PASSWORD_INVALID
                                        $this->addError('password','Password is incorrect.');
                                        break;
                        }
                }
	}
        
        public function login()
        {
                if($this->_identity===null)
                {
                        $this->_identity=new UserIdentity($this->username,$this->password);
                        $this->_identity->authenticate();
                }
                if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
                {
                        Yii::app()->user->login($this->_identity);
                        return true;
                }
                else{
                        return false;
                }
        }
}
