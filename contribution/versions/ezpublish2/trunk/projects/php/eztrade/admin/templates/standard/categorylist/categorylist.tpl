<h1>Produktkatalog</h1>

<hr noshade size="4"/>
/ <a href="/trade/categorylist/parent/0/">Hovedkategori</a> / 
<!-- BEGIN path_item_tpl -->
<a href="/trade/categorylist/parent/{category_id}/">{category_name}</a> / 
<!-- END path_item_tpl -->

<hr noshade size="4"/>


<!-- BEGIN category_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	Kategori:
	</td>

	<td>
	Beskrivelse:
	</td>

	<td>
	Rediger:
	</td>

	<td>
	Slett:
	</td>
</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/trade/categorylist/parent/{category_id}/">{category_name}</a>
	</td>
	<td class="{td_class}">
	{category_description}
	</td>
	<td class="{td_class}">
	<a href="/trade/categoryedit/edit/{category_id}/">[ Rediger ]</a>
	</td>
	<td class="{td_class}">
	<a href="/trade/categoryedit/delete/{category_id}/">[ slett ]</a>
	</td>	
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade size="4"/>
<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	Produkt:
	</td>
	<td align="right">
	Pris:
	</td>
	<td>
	Rediger:
	</td>
	<td>
	Slett:
	</td>
</tr>
<!-- BEGIN product_item_tpl -->
<tr>
	<td class="{td_class}">
	{product_name}
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
	<td class="{td_class}">
	<a href="/trade/productedit/edit/{product_id}/">[ Rediger ]</a>
	</td>
	<td class="{td_class}">
	<a href="/trade/productedit/delete/{product_id}/">[ Slett ]</a>
	</td>
</tr>
<!-- END product_item_tpl -->
</table>
<!-- END product_list_tpl -->




