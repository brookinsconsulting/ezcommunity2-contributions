<h1>Bilder til {product_name}</h1>

<form action="/trade/productedit/imageedit/storedef/{product_id}/" method="post">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	Bildetekst:
	</th>

	<th>
	Forhåndsvisning:
	</th>

	<th>
	Hovedbilde:
	</th>

	<th>
	Minibilde:
	</th>

	<th>
	Rediger:
	</th>

	<th>
	Slett:
	</th>
</tr>
<!-- BEGIN image_tpl -->
<tr>
	<td class="{td_class}">
	{image_name}
	</td>
	<td class="{td_class}">
	<img src="{image_url}" width="{image_width}" height="{image_height}" border="2" />
	</td>
	<td class="{td_class}">
	<input type="radio" {main_image_checked} name="MainImageID" value="{image_id}" />
	</td>
	<td class="{td_class}">
	<input type="radio" {thumbnail_image_checked} name="ThumbnailImageID" value="{image_id}" />
	</td>
	<td class="{td_class}">
	<a href="/trade/productedit/imageedit/edit/{image_id}/{product_id}/">[ Rediger ]</a>
	</td>
	<td class="{td_class}">
	<a href="/trade/productedit/imageedit/delete/{image_id}/{product_id}/">[ Slett ]</a>
	</td>	
</tr>
<!-- END image_tpl -->
</table>

<br/>

<input type="submit" value="lagre endringer" />

</form>

<form action="/trade/productedit/imageedit/new/{product_id}/" method="post">

<input type="submit" value="nytt bilde" />

</form>

<form action="/trade/productedit/edit/{product_id}/" method="post">

<input type="submit" value="tilbake" />

</form>

