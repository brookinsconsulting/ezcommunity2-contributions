<form method="post" action="index.php4?page={document_root}persontypeedit.php4">
<h1>{head_line}</h1>

Navn:<br>
<input type="text" name="PersonTypeName" value="{persontype_name}"><br>
Description:<br>
<textarea rows="5" name="PersonTypeDescription">{description}</textarea>



<input type="hidden" name="PID" value="{persontype_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>
