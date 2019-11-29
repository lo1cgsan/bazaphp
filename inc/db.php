<?php

class Baza {
	private $db = null;
	var $ret = array();
	var $mode = PDO::FETCH_ASSOC;
	var $kom = array();

	function __construct($dbfile) {
		if (!file_exists($dbfile))
			$this->kom[] = 'Brak pliku bazy. Tworzę nowy.';
		try {
			$this->db = new PDO("sqlite:$dbfile");
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			$this->kom[] = 'Błąd: '.$e->getMessage()."\n";
		}
		$this->init_tables();
	}

	function init_tables() {
		if (file_exists('baza/baza.sql')) {
			$q = "SELECT name FROM sqlite_master WHERE type='table' AND name='menu'";
			$this->db_query($q);
			if (empty($this->ret)) {
				$sql = file_get_contents('baza/baza.sql');
				$this->db_exec($sql);
				$this->kom[] = "Utworzono tabele!";
			}
		}
	}

	function db_query($q) {
		try {
			$this->ret = $this->db->query($q, $this->mode)->fetchAll();
			$this->kom[] = "Wykonano: $q\n";
		} catch (PDOException $e) {
			$this->kom[] = 'Błąd: '.$e->getMessage()."\n";
		}
	}

	function db_exec($q) {
		try {
			$this->db->exec($q);
			$this->kom[] = "Wykonano: $q\n";
		} catch (PDOException $e) {
			$this->kom[] = 'Błąd: '.$e->getMessage()."\n";
		}
	}

}
?>