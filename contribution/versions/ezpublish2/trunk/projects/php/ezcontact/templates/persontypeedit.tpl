<form method="post" action="index.php?page={document_root}persontypeedit.php">
<h1>{intl-headline}</h1>

<p>
{intl-name}<br>
<input type="text" name="PersonTypeName" value="{persontype_name}">
</p>

<p>
{intl-desc}<br>
<textarea rows="5" name="PersonTypeDescription">{description}</textarea>
</p>

<input type="hidden" name="PID" value="{persontype_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>
