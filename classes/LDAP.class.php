<?php
class LDAP
{
	public function __construct($hostname = null, $port = 389)
	{
		$handle = ldap_connect($hostname, $port);

	}
}
?>