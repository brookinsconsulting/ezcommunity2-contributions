<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="#f08c00">
	<div class="headline">{category_name}</div>
	</td>
</tr>
</table>
<br />

<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

<!-- BEGIN category_list_tpl -->

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN category_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/{module}/{module_list}/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
</tr>
<!-- END category_tpl -->

</table>

<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
<tr>
	<td>

	<div class="listproducts"><a class="listproducts" href="/{module}/{module_view}/{product_id}/{category_id}/">{product_name}</a></div>

<!-- BEGIN product_image_tpl -->
    <table align="right">
    <tr>
        <td>
	<a href="/{module}/{module_view}/{product_id}/{category_id}/">
        <img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
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
<div class="pris">{product_price}</div>
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
	<a class="path" href="/trade/productlist/{category_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
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
	&nbsp;<a class="path" href="/trade/productlist/{category_id}/{item_index}">{type_item_name}</a>&nbsp;|
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
	&nbsp;<a class="path" href="/trade/productlist/{category_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
