<form action="index.php4" method="get">
    <input type="hidden" name="page" value="category.php4">
    <input type="hidden" name="category_id" value="{Id}">
    <table border="0" cellspacing="1" cellpadding="5">
        <tr class="head">
            <td colspan="4">
                Endre kategori
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
                <input type="text" name="Name" value="{category-name}">
            </td>
            <td>
                <input type="text" name="Description" value="{category-description}">
            </td>
            <td align="center">
                <input type="checkbox" name="Private" {category-private}>
            </td>
            <td align="center">
                <input type="submit" name="add" value="Endre">
            </td>
        </tr>
    </table>
</form>