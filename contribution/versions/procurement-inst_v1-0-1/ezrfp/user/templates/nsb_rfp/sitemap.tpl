
<div id="LayerContent" class="LayerContent" style="">
<h1>{intl-site_map}</h1>

<table width="100%" cellpadding="2" cellspacing="0" border="0">
<!-- BEGIN category_value_tpl -->
<tr>
	<td>
	{option_level}
<!--
	<img src="{www_dir}/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" />&nbsp;
-->
	<span class="subdiv"><a href="{www_dir}{index}/rfp/archive/{option_value}">{option_name}</a></span><br />
	</td>
</tr>
<!-- END category_value_tpl -->

<!-- BEGIN rfp_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="{www_dir}/admin/images/document.gif" height="16" width="16" border="0" alt="" align="top" />&nbsp;
<span class="subdiv">
	<a href="{www_dir}{index}/rfp/view/{option_value}/1/{category_id}">{option_name}</a></span><br />
	</td>
</tr>
<!-- END rfp_value_tpl -->

<!-- BEGIN value_tpl -->

<!-- END value_tpl -->
</table>
<br />

</div>