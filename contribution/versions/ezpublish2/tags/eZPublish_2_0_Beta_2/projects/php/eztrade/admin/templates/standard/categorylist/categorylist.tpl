<h1>{intl-productlist}</h1>

<hr noshade="noshade" size="4" />

<img src="/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="0" />
<a class="path" href="/trade/categorylist/parent/0/">{intl-top}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="/trade/categorylist/parent/{category_id}/">{category_name}</a>

<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN category_list_tpl -->
<form method="post" action="/trade/categoryedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>


	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN category_item_tpl -->
<tr>
	<td width="49%" class="{td_class}">
	<a href="/trade/categorylist/parent/{category_id}/">{category_name}&nbsp;</a>
	</td>
	<td width="49%" class="{td_class}">
	{category_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{category_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="eztc{category_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="CategoryArrayID[]" value="{category_id}">
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" Name="DeleteCategories" value="{intl-deletecategories}">
</form>

<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->
<form method="post" action="/trade/productedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>{intl-product}:</th>
	<td class="path" align="right">{intl-price}:</td>
	<!-- BEGIN absolute_placement_header_tpl -->
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<!-- END absolute_placement_header_tpl -->

	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN product_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/trade/productedit/productpreview/{product_id}/">{product_name}&nbsp;</a>
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
	<!-- BEGIN absolute_placement_item_tpl -->
	<td width="1%" class="{td_class}">
	<a href="/trade/categorylist/parent/{category_id}/?MoveDown={product_id}"><img src="/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/categorylist/parent/{category_id}/?MoveUp={product_id}"><img src="/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a>
	</td>
	<!-- END absolute_placement_item_tpl -->
	<td width="1%" class="{td_class}">
	<a href="/trade/productedit/edit/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezti{product_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="ezti{product_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="ProductArrayID[]" value="{product_id}">
	</td>
</tr>
<!-- END product_item_tpl -->
</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteProducts" value="{intl-deleteproducts}">
</form>
<!-- END product_list_tpl -->




