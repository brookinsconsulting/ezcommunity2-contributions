
<h1>{intl-bug_view}</h1>

<hr noshade="noshade" size="4">
<!-- BEGIN path_tpl -->


<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />

<a class="path" href="/bug/archive/0/">{intl-top_level}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />

<a class="path" href="/bug/archive/{module_id}/">{module_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<br />

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td>
	<p class="boxtext">{intl-bug_title}:</p>
	{name_value}
	<br /><br />
	</td>

	<td>
	<p class="boxtext">{intl-bug_date}:</p>
	{bug_date}
	<br /><br />
	</td>
</tr>
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

<p class="boxtext">{intl-bug_reporter}:</p>
<div class="p">{reporter_name_value}</div>

<p class="boxtext">{intl-bug_description}:</p>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="bglight">
	{description_value}
	</td>
</tr>
</table>

<br />

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
<p class="boxtext">{intl-is_closed}:</p>
<!-- BEGIN yes_tpl -->
<div class="p">{intl-yes}</div>
<!-- END yes_tpl -->
<!-- BEGIN no_tpl -->
<div class="p">{intl-no}</div>
<!-- END no_tpl -->

<br />
<br />
<!-- BEGIN log_item_tpl -->
<b>{log_date}</b>
<p>
{log_description}
</p>
<!-- END log_item_tpl -->	

