<h1>Bilder til {product_name}</h1>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<th>
	Bildetekst:
	</th>

	<th>
	Forhåndsvisning:
	</th>

	<th>
	Rediger:
	</th>

	<th>
	Slett:
	</th>
</tr>
{image_list}
</table>

<br/>

<form action="/trade/productedit/imageedit/new/{product_id}/" method="post">

<input type="submit" value="nytt bilde" />

</form>

<form action="/trade/productedit/edit/{product_id}/" method="post">

<input type="submit" value="tilbake" />

</form>

