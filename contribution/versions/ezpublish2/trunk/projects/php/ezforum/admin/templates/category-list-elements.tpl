<tr bgcolor="{color}">
    <td>
        <p>{list-Name}</p>
    </td>
    <td>
        <p>{list-Description}</p>
    </td>
    <td align="center">
        <p>{list-Private}</p>
    </td>
    
    <td>
        <a href="index.php4?page={docroot}/admin/category.php4&action=modify&category_id={list-Id}">Endre</a>
    </td>
    <td>
        <a href="index.php4?page={docroot}/admin/category.php4&action=delete&category_id={list-Id}">Slett</a>
    </td>
    <td>
        <a href="index.php4?page={docroot}/admin/forum.php4&category_id={list-Id}">Forum...</a>
    </td>
</tr>
