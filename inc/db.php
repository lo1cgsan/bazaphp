<?php

class Baza {
	static private $db = null;
	static public $ret = array();
	static public $mode = PDO::FETCH_ASSOC;
	static public $kom = array();

	public function __construct($dbfile) {
		if (!file_exists($dbfile))
			self::$kom[] = 'Brak pliku bazy. Tworzę nowy.';
		try {
			self::$db = new PDO("sqlite:$dbfile");
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			self::$kom[] = 'Błąd: '.$e->getMessage()."\n";
		}
		self::init_tables();
	}

	function init_tables() {
		if (file_exists('baza/baza.sql')) {
			$q = "SELECT name FROM sqlite_master WHERE type='table' AND name='menu'";
			self::db_query($q);
			if (empty(self::$ret)) {
				$sql = file_get_contents('baza/baza.sql');
				self::db_exec($sql);
				self::$kom[] = "Utworzono tabele!";
			}
		}
	}

	static public function db_query($q) {
		try {
			self::$ret = self::$db->query($q, self::$mode)->fetchAll();
			self::$kom[] = "Wykonano: $q\n";
		} catch (PDOException $e) {
			self::$kom[] = 'Błąd: '.$e->getMessage()."\n";
		}
	}

	static public function db_exec($q) {
		try {
			self::$db->exec($q);
			self::$kom[] = "Wykonano: $q\n";
		} catch (PDOException $e) {
			self::$kom[] = 'Błąd: '.$e->getMessage()."\n";
		}
	}

}
?>