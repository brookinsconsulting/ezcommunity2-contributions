<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-bug_archive} - {current_module_name}</h1>
	</td>
	<td rowspan="2" align="right">
	<form action="/bug/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
<tr>
	<td>{current_module_description}</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->


<img src="/ezbug/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/bug/archive/0/">{intl-top_level}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/ezbug/admin/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/bug/archive/{module_id}/">{module_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN module_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-module}:</td>
	<th>{intl-description}:</th>
</tr>
	
<!-- BEGIN module_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/bug/archive/{module_id}/">{module_name}</a>&nbsp;
	</td>

	<td class="{td_class}">
	{module_description}&nbsp;
	</td>
</tr>
<!-- END module_item_tpl -->
</table>
<hr noshade size="4"/>
<!-- END module_list_tpl -->


<!-- BEGIN bug_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-bug}:</th>
	<th>{intl-status}:</th>
	<th>{intl-priority}:</th>
	<th>{intl-is_closed}:</th>
</tr>

<!-- BEGIN bug_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/bug/bugview/{bug_id}/">
	{bug_name}&nbsp;
	</a>
	</td>

	<td class="{td_class}">
	{bug_status}&nbsp;
	</td>

	<td class="{td_class}">
	{bug_priority}&nbsp;
	</td>

	<td class="{td_class}">
	<!-- BEGIN bug_is_closed_tpl -->
	{intl-is_closed}&nbsp;
	<!-- END bug_is_closed_tpl -->

	<!-- BEGIN bug_is_open_tpl -->
	{intl-is_open}&nbsp;
	<!-- END bug_is_open_tpl -->

	</td>

</tr>
<!-- END bug_item_tpl -->

</table>
<!-- END bug_list_tpl -->
