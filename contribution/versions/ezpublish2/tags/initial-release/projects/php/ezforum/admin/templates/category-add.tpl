<form action="index.php4" method="get">
    <input type="hidden" name="page" value="admin/category.php4">
    <table border="0" cellspacing="1" cellpadding="5">
        <tr class="head">
            <td colspan="4">
                Legg til kategori
            </td>
        </tr>
        <tr>
            <td>
                Navn:
            </td>  
            <td>
                Beskrivelse:
            </td>
            <td>
                Privat:
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        
        <tr>
            <td>
                <input type="text" name="Name">
            </td>
            <td>
                <input type="text" name="Description">
            </td>
            <td align="center">
                <input type="checkbox" name="Private">
            </td>
            <td align="center">
                <input type="submit" name="add" value="Legg til">
            </td>
        </tr>
    </table>
</form>