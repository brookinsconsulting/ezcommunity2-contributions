<form action="/poll/polllist/">

<h1>{intl-head_line}</h1>

<hr noshade size="4"/>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-poll}</th>

	<th>{intl-description}</th>

	<th>{intl-enabled}</th>

	<th>{intl-closed}</th>

	<th>Hovedpoll</th>

	<th>&nbsp;</th>

	<th>&nbsp;</th>
</tr>

{poll_list}

</table>

<hr noshade size="4" />

<input class="okbutton" type="submit" value="Lagre endringer" />
</form>


<a href="/poll/polledit/new/">Legg til poll</a>