<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />

<form action="/bug/support/edit/{action}/{id}" method="post">

<p class="boxtext">{intl-id}:</p>
{id}

<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" size="40" name="Name" value="{name}">

<p class="boxtext">{intl-email}:</p>
<input type="text" class="box" size="40" name="Email" value="{email}">
<br />
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
	<p class="boxtext">{intl-day}:</p>

	<select name="Day">
	<!-- BEGIN day_tpl -->
		<option value="{day_id}" {selected}>{day_name}</option>
	<!-- END day_tpl -->
	</select>

	</td>
	<td valign="top">
	<p class="boxtext">{intl-month}:</p>

	<select name="Month">
	<!-- BEGIN month_tpl -->
		<option value="{month_id}" {selected}>{month_name}</option>
	<!-- END month_tpl -->
	</select>

	</td>
	<td width="40%" valign="top">
	<p class="boxtext">{intl-year}:</p>
	<input type="text" name="Year" value="{year_value}" />
	</td>
</tr>
</table>
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" Name="OK" value="{intl-ok}">
<input class="stdbutton" type="submit" Name="Cancel" value="{intl-cancel}">
