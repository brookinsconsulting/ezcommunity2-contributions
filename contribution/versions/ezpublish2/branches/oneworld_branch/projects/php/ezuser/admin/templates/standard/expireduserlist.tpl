<form action="{www_dir}{index}/user/expiredusers/edit/" method="post">

<h1>{intl-expired_users}</h1>

<hr size="4" noshade="noshade" />

<table width="100%" class="list" cellpadding="4" cellspacing="0" border="0">
<tr>
        <th>
        </th>
	<th>
	{intl-day}:
	</th>
	<th>
	{intl-month}:
	<th>
	{intl-year}:
	</th>
</tr>
<tr>
        <td>
        <input type="checkbox" name="NoExpiryDate" />
	<span class="p">{intl-no_expiry_date}</span>
        </td>
        <td>
        <select name="Day">
<!-- BEGIN day_item_tpl -->
        <option value="{day}" {day_selected}>{day}
<!-- END day_item_tpl -->
        </select>
	</td>
	<td>
	<select name="Month">
<!-- BEGIN month_item_tpl -->
        <option value="{month_id}" {month_selected}>{month_name}
<!-- END month_item_tpl -->
        </select>
        </td>
        <td>
        <input type="text" class="halfbox" size="4" name="Year" value="{year}" />
        </td>
</tr>
</table>

<hr size="4" noshade="noshade" />

<table width="100%" class="list" cellpadding="4" cellspacing="0" border="0">
<tr>
        <th>
	{intl-username}:
	</th>
	<th>
	{intl-name}:
	</th>
	<th>
	{intl-activate}:
	</th>
</tr>
<!-- BEGIN user_item_tpl -->
<tr>
        <td>
	<a href="/user/useredit/edit/{id}/">{username}</a>
	</td>
	<td>
	{name}
	</td>
	<td>
	<input type="checkbox" name="UserArray[]" value="{id}" />
	</td>
</tr>
<!-- END user_item_tpl -->
</table>

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />


</form>
