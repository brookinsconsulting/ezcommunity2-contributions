<form method="post" action="index.php4?page={document_root}noteedit.php4">

<h1>{message}</h1>
<input type="text" name="Title" value="{title}"><br>
<textarea rows="5" name="Body">{body}</textarea><br>

<input type="hidden" name="Action" value="{action}">
<input type="submit" value="{submit_text}">

</form>