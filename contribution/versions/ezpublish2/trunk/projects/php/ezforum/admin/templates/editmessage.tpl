<h2>{info}</h2>

<form action="index.php4" method="post">
    <input type="hidden" name="page" value="{docroot}/forum.php4">
    <input type="hidden" name="forum_id" value="{forum_id}">
    <input type="hidden" name="category_id" value="{category_id}">
    <table  width="85%" border="0" cellspacing="1" cellpadding="5">
        <tr class="subject">
            <td width="60"> Emne: </td>
            
            <td width="768">
               <input type="text" name="Topic" size="25">
            </td>
        </tr>
        
        <tr>
            <td width="60"> Forfatter: </td>
            <td width="768" class="author">{user}</td>
        </tr>
    
        <tr>
            <td colspan="2">
                <textarea name="Body" rows="14" cols="25" class="body"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="preview" value="Forhåndsvisning">
                <input type="submit" name="post" value="Post!">
                <input type="reset" name="empty" value="Tøm"><br>
		<input type="checkbox" name="notice">Emailnotis
            </td>
        </tr>
    </table>
</form>
{navigation-bar-bottom}