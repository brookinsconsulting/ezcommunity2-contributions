<h1>{intl-sql_query}</h1>

<b>{intl-notice}</b>: {intl-only_for_advanced_users}
<hr noshade="noshade" size="4" />

<form action="/sitemanager/sqladmin/query" method="post">

<textarea name="QueryText" cols="80" rows="4">{query_text}</textarea><br />

<input class="okbutton" type="submit" name="Run" value="{intl-run}" />

<hr noshade="noshade" size="4" />

</form>

{query_result}
{error}