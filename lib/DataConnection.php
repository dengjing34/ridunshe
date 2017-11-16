<?php
//dengjing34@vip.qq.com
class DataConnection {

	private static $connection = null;

	public static function getConnection() {
		if (self::$connection == null) {
                        $cfg = Config::item('db');
                        self::$connection = @mysql_connect($cfg['host'], $cfg['user'], $cfg['password']) or die(mysql_error());
                        mysql_select_db($cfg['database']) or die(mysql_error());
                        mysql_query('set names utf8') or die(mysql_error());
		}
		return self::$connection;
	}

}
