<img src="/sitedesign/nca1/images/sidebeskrivelser/forsiden.gif" width="530" height="54" border="0" alt="">
<div class="path">
	<!-- BEGIN path_item_tpl -->
	<a href="{www_dir}{index}/{module}/{module_list}/{category_id}/">{category_name}</a>
	<img src="/sitedesign/nca1/images/path_arrow.gif" width="14" height="12" border="0" alt="">
	<!-- END path_item_tpl -->
</div>
<img src="/sitedesign/nca1/images/header/header_section_{section_id}_type2.gif" width="530" height="25" border="0" alt="">
<table width="530" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td colspan="2"><img src="/sitedesign/nca1/images/spacer.gif" width="530" height="10" border="0" alt=""></td>
	</tr>
	<!-- BEGIN category_list_tpl -->
	<!-- BEGIN category_tpl -->
	<tr>
		<td>&nbsp;</td>
		<td>
			<p>
				<span class="h3"><a href="{www_dir}{index}/{module}/{module_list}/{category_id}/">{category_name}</a></span>
				<br>
				{category_description}
				<br>
				<br>
			</p>
		</td>
	</tr>
	<!-- END category_tpl -->
	<!-- END category_list_tpl -->
</table>

<!-- BEGIN product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
<tr>
	<td>

	<div class="listproducts"><a class="listproducts" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">{product_name}</a></div>

<!-- BEGIN product_image_tpl -->
    <table align="right">
    <tr>
        <td>
	<a href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">
        <img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
	</a>
        </td>
    </tr>
    <tr>
        <td class="pictext">
        {thumbnail_image_caption}
        </td>
    </tr>
    </table>
<!-- END product_image_tpl -->

<div class="p">{product_intro_text}</div>

<!-- BEGIN price_tpl -->
<div class="spacer"><div class="pris">{product_price}</div></div>
<!-- END price_tpl -->

	</td>
</tr>
<!-- END product_tpl -->
</table>

<!-- END product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_index}">{type_item_name}</a>&nbsp;|
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	&nbsp;&lt;{type_item_name}&gt;&nbsp;|
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
	</td>
</tr>

</table>
