<h1>{intl-site_map}</h1>

<br />

<table width="100%" cellpadding="2" cellspacing="0" border="0">
<!-- BEGIN category_value_tpl -->
<tr>
	<td>
	{option_level}
	<span class="subdiv">
	{option_name}</span></td>
</tr>

<!--
        {option_level}
          <a href="{www_dir}{index}/rfp/{option_value}">{option_name}</a>&nbsp;
-->
<!-- END category_value_tpl -->

<!-- BEGIN file_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="{www_dir}/admin/images/document.gif" height="16" width="16" border="0" alt="" align="top" />&nbsp;

<span class="subdiv">

	<a href="{www_dir}{index}/filemanager/{option_value}">{option_name}</a>&nbsp;
</span>
	</td>
</tr>
<!-- END file_value_tpl -->

<!-- BEGIN value_tpl -->

<!-- END value_tpl -->
</table>
<br />
