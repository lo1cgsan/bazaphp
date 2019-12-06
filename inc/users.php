<?php
ini_set('session.save_path', 'sesje');


class User {
	var $dane = array();
	var $keys = array('id', 'login', 'haslo', 'email', 'data');

	function __construct() {
		if (!isset($_SESSION)) session_start();
	}

	function login($login, $haslo) {
		if ($this->is_user($login, $haslo)) {
			$_SESSION['dane'] = $this->dane;
		}
	}

	function is_user($login, $haslo) {
		$q = "SELECT * FROM users WHERE login='$login' AND haslo='".sha1($haslo)."' LIMIT 1";
		Baza::db_query($q);
		if (!empty(Baza::$ret[0])) {
			$this->dane = array_merge($this->dane, Baza::$ret[0]);
			$sid = sha1($this->id.$this->login.session_id());
			$_SESSION['sid'] = $sid;
			return true;
		}
		return false;
	}

	function __set($k, $v) {
		$this->dane[$k] = $v;
	}

	function __get($k) {
		if (array_key_exists($k, $this->dane))
			return $this->dane[$k];
		else
			return null;
	}

	function is_login($login) {
		$q="SELECT id FROM users WHERE login='$login' LIMIT 1";
		Baza::db_query($q);
		if (Baza::$ret) return true;
		return false;
	}

	function is_email($email) {
		$q="SELECT id FROM users WHERE email='$email' LIMIT 1";
		Baza::db_query($q);
		if (Baza::$ret) return true;
		return false;
	}

	function create_user() {
		$this->haslo = sha1($this->haslo);
		$q = 'INSERT INTO users (id, login, email, haslo)';
		$q .= ' VALUES(NULL, \''.$this->login.'\', \''.$this->email.'\', \''.$this->haslo.'\')';

		Baza::db_exec($q);
		$this->id = Baza::db_lastID();
	}

}
?>