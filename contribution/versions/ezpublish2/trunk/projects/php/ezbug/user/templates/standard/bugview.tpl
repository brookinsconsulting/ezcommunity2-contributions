
<h1>{intl-bug_view}</h1>

<hr noshade="noshade" size="4">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top" width="100%">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td>
	<p class="boxtext">{intl-bug_module}:</p>
	{module_name}
	</td>

	<td>
	<p class="boxtext">{intl-bug_category}:</p>
	{category_name}
	</td>
</tr>
</table>


<p class="boxtext">{intl-bug_date}:</p>
{bug_date}

<p class="boxtext">{intl-bug_title}:</p>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="bglight">
	{name_value}
	</td>
</tr>
</table>

<p class="boxtext">{intl-bug_description}:</p>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="bglight">
	{description_value}
	</td>
</tr>
</table>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td>
	<p class="boxtext">{intl-assigned_priority}:</p>
	{priority_name}
	</td>

	<td>
	<p class="boxtext">{intl-assigned_status}:</p>
	{status_name}
	</td>
</tr>
</table>

<br />
<span class="boxtext">{intl-is_closed}:</span>
<!-- BEGIN yes_tpl -->
{intl-yes}
<!-- END yes_tpl -->
<!-- BEGIN no_tpl -->
{intl-no}
<!-- END no_tpl -->

<br />
<br />
<!-- BEGIN log_item_tpl -->
<b>{log_date}</b>
<p>
{log_description}
</p>
<!-- END log_item_tpl -->	

</td>
</tr>
</table>


<hr noshade="noshade" size="4">




