<?php

class User{
	private $_db,
			$_data,
			$_session,
			$_isLogged = false,
			$_cookieName;
	public function __construct($user=null)
	{
		$this->_db = DB::getInstance(); //db instance
		//storing name of cookie & session
		$this->_session = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');
		//user information
		if (!$user) {
			if(Session::exists($this->_session)){
				$user = Session::get($this->_session);
				if ($this->find($user)) {
					$this->_isLogged = true;
				}else{
					//log out
				}
			}
			
		}else{
			$this->find($user);
		}
	}

	public function createUser($fields = array())
	{
		if (!$this->_db->insert('users', $fields)) {
			throw new Exception("Error While Registering User");
		}
	}

	public function login($username = null, $password = null, $remember = false)
	{
		if(!$username && !$password && $this->exists()){
			Session::put($this->_session, $this->data()->id);
		}else{
			$user = $this->find($username); //find user in db
			//check login creds
			if ($user) {
				if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
					Session::put($this->_session, $this->data()->id);
					if ($remember) {
						$hash = Hash::unique();
						//check cookie hash exists in db
						$checkHash = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));
						//if not store in db
						if (!$checkHash->count()) {
							$this->_db->insert('users_session', array('user_id' => $this->data()->id , 'hash' => $hash));
						}else{
							$hash = $checkHash->first()->hash;
						}
					}
					//set cookie with the hash
					Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					return true;
				}
			}
		}


			return false;
	}

	public function update($field = array(), $id = null)
	{
		if (!$id && $this->isLogged()) {
			$id = $this->data()->id;
		}
		if(!$this->_db->update('users', $id, $field)){
			throw new Exception("Update Error");
			
		}
	}


	public function find($user = null)
	{
		if ($user) {
			//finding based on name and id both
			//checking id or name
			$field = (is_numeric($user)) ? 'id' : 'username' ;
			//db query to get user 
			$data = $this->_db->get('users', array($field, '=', $user));
			if ($data->count()) {
				//storing data in _data property
				$this->_data = $data->first();
				return true;
			}
		}

	}
	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}
	public function logout()
	{
		$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
		Session::delete($this->_session);
		Cookie::delete($this->_cookieName);
	}
	public function data()
	{
		return $this->_data;
	}
	public function isLogged(){
		return $this->_isLogged;
	}


}