<form action="index.php4" method="post">
    <input type="hidden" name="page" value="{docroot}/admin/category.php4">
    <input type="hidden" name="category_id" value="{category_id}">
    <h1>Endre kategori</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white"><b>Identifikasjon</b></p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	<p>Navn:<br>
	<input type="text" name="Name" value="{category-name}"></p>
	<p>Beskrivelse:<br>
	<input type="text" name="Description" value="{category-description}"></p>
	<input type="checkbox" name="Private" {category-private}>&nbsp;Privat
	</td>
</tr>
<tr><td bgcolor="#f0f0f0"><br></td></tr>
</table>

<br>
<input type="submit" name="modifyCategory" value="Endre">

</form>