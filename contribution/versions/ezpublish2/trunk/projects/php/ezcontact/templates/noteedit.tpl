<form method="post" action="index.php4?prePage={document_root}noteedit.php4">

<h1>{message}</h1>
<input type="text" name="Title" value="{title}"><br>
<textarea rows="5" name="Body">{body}</textarea><br>

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="NID" value="{note_id}">
<input type="submit" value="{submit_text}">

</form>