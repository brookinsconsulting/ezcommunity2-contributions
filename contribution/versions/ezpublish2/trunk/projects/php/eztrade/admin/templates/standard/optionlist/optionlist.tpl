<h1>Opsjonsoversikt</h1>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<th>
	Opsjoner for: {product_name}
	</th>

	<th>
	Rediger:
	</th>

	<th>
	Slett:
	</th>
</tr>
<!-- BEGIN option_tpl -->
<tr>
	<td class="{td_class}">
	{option_name}
	</td>
	<td class="{td_class}">
	<a href="/trade/productedit/optionedit/edit/{option_id}/{product_id}/">[ Rediger ]</a>
	</td>
	<td class="{td_class}">
	<a href="/trade/productedit/optionedit/delete/{option_id}/{product_id}/">[ Slett ]</a>
	</td>	
</tr>
<!-- END option_tpl -->
</table>

<br/>

<form action="/trade/productedit/optionedit/new/{product_id}/" method="post">

<input type="submit" value="ny opsjon" />

</form>
<form action="/trade/productedit/edit/{product_id}/" method="post">

<input type="submit" value="tilbake" />

</form>

