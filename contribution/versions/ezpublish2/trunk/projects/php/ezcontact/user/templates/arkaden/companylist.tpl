<!-- 
<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />
-->

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<!-- BEGIN category_list_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN image_item_tpl -->
<!-- <p class="boxtext">{intl-th_type_current_image}:</p> -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" /><br />
	</td>
</tr>
</table>
<br />
<!-- END image_item_tpl -->

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-name}:
	</th>

	<td align="right">
	<b>{intl-telephone}:</b>
	</td>
</tr>
<!-- BEGIN company_list_tpl -->
<!-- BEGIN company_item_tpl -->
<!-- BEGIN no_image_tpl -->

<!-- END no_image_tpl -->

<tr>
	<td class="{td_class}">
	{company_name}
	</td>
	<td align="right" class="{td_class}">
	{company_telephone}
	</td>
</tr>

<!-- END company_item_tpl -->
<!-- END company_list_tpl -->

<!-- BEGIN error_tpl -->

<!-- END error_tpl -->

</table>


