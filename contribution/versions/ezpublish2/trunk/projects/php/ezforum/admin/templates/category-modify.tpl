<form action="index.php4" method="post">
    <input type="hidden" name="page" value="{docroot}/admin/category.php4">
    <input type="hidden" name="category_id" value="{category_id}">
    <h1>Endre kategori</h1>
	<table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <p>Navn:</p>
            </td>
            <td>
                <p>&nbsp;&nbsp;Privat:</p>
            </td>
		</tr>
        <tr>
			<td>
                <input type="text" name="Name" value="{category-name}"><br><br>
            </td>
		    <td align="left">
                &nbsp;&nbsp;<input type="checkbox" name="Private" {category-private}><br><br>
            </td>
		</tr>
		<tr>
		    <td>
                <p>Beskrivelse:</p>
            </td>
			<td>
                &nbsp;
            </td>
        </tr>
		<tr>
		    <td>
                <input type="text" name="Description" value="{category-description}">
            </td>
			<td align="center">
                &nbsp;&nbsp;<input type="submit" name="modifyCategory" value="Endre">
            </td>
		</tr>
</table>
</form>