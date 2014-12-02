<?php
require_once("setup.php");
// These loaders are disabled and should be removed.
exit(1);

$row = 1;
if (($handle = fopen("csvProbs/stats250v3.csv","r")) !== FALSE)
{
	while (($data = fgetcsv($handle,10000,", ")) !== FALSE)
	{
		$num = count($data);

		$url = $data[2];
		$solution = $data[5];

		//SEARCH TO SEE IF PROBLEM EXISTS
		$query = " SELECT * FROM problems WHERE url = :url";
		$bindings = array(":url" => $url);
		$res=$dbmgr->fetch_assoc( $query , $bindings );
		$p_id = $res[0]['id'];

		//UPDATE PROBLEM IN PROBLEM TABLE
		$query = "UPDATE problems SET solution =: solution WHERE id = :id";
		$bindings = array(":solution" => $solution, ":id" => $p_id);
		$dbmgr->exec_query( $query , $bindings );

		$row++;
	}
	fclose($handle);
}

