<<<<<<< noteedit.tpl
<form method="post" action="index.php?page={document_root}noteedit.php">
=======
<form method="post" action="index.php?prePage={document_root}noteedit.php">
>>>>>>> 1.5

<h1>{intl-headline}</h1>
<p>{intl-title}<br>
<input type="text" name="Title" value="{title}"></p>

<p>{intl-text}<br>
<textarea rows="5" name="Body">{body}</textarea></p>

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="NID" value="{note_id}">
<input type="submit" value="{submit_text}">

</form>