<?php

ini_set('date.timezone','Asia/Shanghai');

$db_host = 'rm-uf658t1hnuxsvd850.mysql.rds.aliyuncs.com';
$db_user = 'exands';
$db_pass = 'exands#100';
$db_name = 'exals';
$db_character = 'utf8';
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
mysqli_query($link , "set names " . $db_character);

function fmac(&$mac)
{
	$mac = strtolower($mac);
	$mac = str_replace(":", "", $mac);
	$mac = str_replace("-", "", $mac);
	$mac = str_replace(".", "", $mac);
	return $mac;
}

function dbe($sql = '')
{
	global $link;
	if ($sql)
		mysqli_query($link , $sql);
}

function dbq($sql = '')
{
	global $link;

	if (empty($sql))
		return null;

	$data = array();
	$result = mysqli_query($link , $sql);
	if ($result && mysqli_num_rows($result) > 0 )
	{
		while($row = mysqli_fetch_assoc($result))
			$data[] = $row;

		mysqli_free_result($result);
	}

	return $data;
}

$resp = array('errcode' => '0', 'errmsg' => 'success');

$method = @$_REQUEST['method'];
try
{
	switch ($method)
	{
		case 'getMonitoredClients':
			$query = "select mac from mac_monitor";
			$result = dbq($query);

			$resp['data'] = array();
			foreach ($result as $vo)
			{
				$resp['data'][] = $vo['mac'];
			}
			break;

		default:
			throw new Exception('method' . $method . ' not recognized', 406);
	}
}
catch (Exception $e)
{
	$resp['errcode'] = '1';
	$resp['errmsg'] = $e->getMessage();
}

echo json_encode($resp);

exit();
