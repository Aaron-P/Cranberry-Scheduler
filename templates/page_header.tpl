{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

<span>
	<a href="{$baseUrl}index.php?page=main">
		<img id="logo" src="{$baseUrl}images/logo.png" alt="Cranberry Scheduler" />
	</a>
</span>

{if $loggedIn}
<div id="user_bar">
	<span>Logged in as: {$firstName} {$lastName}&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="{$baseUrl}index.php?page=settings">Settings</a>
		&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="{$baseUrl}index.php?logout">Logout</a>
	</span>
</div>
{/if}
