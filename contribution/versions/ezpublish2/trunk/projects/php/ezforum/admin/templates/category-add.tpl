<form action="index.php4" method="post">
    <input type="hidden" name="page" value="{docroot}/admin/category.php4">
    <h1>Legg til ny kategori</h1>
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
                <input type="text" name="Name"><br><br>
            </td>
			<td align="left">
                &nbsp;&nbsp;<input type="checkbox" name="Private"><br><br>
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
                <input type="text" name="Description">
            </td>
            <td align="center">
                &nbsp;&nbsp;<input type="submit" name="add" value="Legg til">
            </td>
		</tr>    
</table>
</form>