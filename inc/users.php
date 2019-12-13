<?php
ini_set('session.save_path', 'sesje');


class User {
	var $dane = array();
	var $keys = array('id', 'login', 'haslo', 'email', 'data');
	var $kom = array();
	var $CookieName = 'bazaphp';
	var $remTime = 7200; // 2 godz
	var $CookieDomain = '';

	function __construct() {
		if ($this->CookieDomain == '') $this->CookieDomain = 'localhost'; // $_SERVER['HTTP_HOST'];

		if (!isset($_SESSION)) session_start();

		if (isset($_COOKIE[$this->CookieName]) && !$this->id) {
			$u = unserialize(base64_decode($_COOKIE[$this->CookieName]));
			$this->login($u['login'], $u['haslo'], false, true);
			$this->kom[] = "Witaj {$this->login}, zostałeś automatycznie zalogowany!";
		}

		if (!$this->id && isset($_POST['login2'])) {
			//$login = clrtxt($_POST['login']);
			foreach ($_POST as $k => $v) {
    		${$k} = clrtxt($v);
  		}
  		$this->login($login2, $haslo2, true, true);
		}

	}

	function login($login, $haslo, $remember=false, $loadUser=true) {
		if ($loadUser && $this->is_user($login, $haslo)) {
			if ($remember) { // zapisanie ciasteczka
				$cookie = base64_encode(serialize(array('login'=>$login, 'haslo'=>$haslo, 'czas'=>time())));
				$a = setcookie($this->CookieName, $cookie, time()+$this->remTime, '/', $this->CookieDomain, false, true);
				if ($a) $this->kom[] = 'Zapisano ciasteczko.';
				$this->kom[] = "Witaj $login! Zostałeś zalogowany.";
				return true;
			}
		} else {
			$this->kom[] = '<b>Błędny login lub hasło!</b>';
			return false;
		}
	}

	function logout($redirectTo = '') {
		echo 'Wylogowanie';
		setcookie($this->CookieName, '', time()-(3*$this->remTime), '/', $this->CookieDomain, false, true);
		$this->dane = array();
		$_SESSION = array();
		if (session_destroy()) $this->kom[] = 'Zostałeś wylogowany!';
		if ($redirectTo != '' && !headers_sent()) {
			header('Location: '.$redirectTo);
			exit;
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