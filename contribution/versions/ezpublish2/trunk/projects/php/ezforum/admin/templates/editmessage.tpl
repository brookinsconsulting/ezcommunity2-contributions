{navigation-bar}
<form action="index.php4" method="post">
    <input type="hidden" name="page" value="{docroot}/admin/message.php4">
    <input type="hidden" name="category_id" value="{category_id}">
    <input type="hidden" name="message_id" value="{message_id}">
    <table  width="85%" border="0" cellspacing="1" cellpadding="5">
        <tr class="subject">
            <td width="60"> Emne: </td>
            
            <td width="768">
               <input type="text" name="Topic" size="25" value="{topic}">
            </td>
        </tr>
        
        <tr>
            <td width="60"> Forfatter: </td>
            <td width="768" class="author">{user}</td>
        </tr>
    
        <tr>
            <td colspan="2">
                <textarea name="Body" rows="14" cols="25" class="body">{body}</textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="modifymessage" value="Endre">
		<input type="checkbox" name="notice" {email-notice}>Emailnotis
            </td>
        </tr>
    </table>
</form>
{navigation-bar-bottom}
