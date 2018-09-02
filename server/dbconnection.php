<?php

	class DatabaseConnection extends mysqli {

		const DB_HOSTNAME = "localhost";
		const DB_USERNAME = "moncadaevento";
		const DB_PASSWORD = "Moncada2017@";
		const DB_NAME = "compliance2018";
		//const DB_USERNAME = "root";
		//const DB_PASSWORD = "@MorionMysql2016";

		public function __construct() {

			parent::__construct(self::DB_HOSTNAME, self::DB_USERNAME, self::DB_PASSWORD, self::DB_NAME);

			if($this->connect_errno){
				$message = 'Connection failed: '.$this->connect_error;
				throw new Exception($message, $this->connect_errno);
			}

			$this->set_charset("utf8");
		}
	}
?>
