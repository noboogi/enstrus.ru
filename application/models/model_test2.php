<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/application/library/safemysqlsingleton.php');
class Model_test2 extends Model
{

	public function get_data($type="all")
	{	
		$query = SDB::lastQuery();
		$result = SDB::getRow("SELECT * FROM `user` WHERE ?i", 1);
		return $result;
	}
}
