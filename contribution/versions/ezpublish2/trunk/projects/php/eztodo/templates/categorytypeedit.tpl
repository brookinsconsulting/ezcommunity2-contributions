<form method="post" action="index.php?page={document_root}categorytypeedit.php">
<h1>{head_line}</h1>

<p>{intl-name}<br>
<input type="text" name="Title" value="{category_type_name}"><br></p>

<input type="hidden" name="CategoryID" value="{category_type_id}">
<input type="hidden" name="Action" value="{action_value}">

<input type="submit" value="{submit_text}">

</form>