<form method="post" action="index.php4?prePage={document_root}companytypeedit.php4">
<h2>{head_line}</h2>

<p>
Navn:<br>
<input type="text" name="CompanyTypeName" value="{companytype_name}">
</p>

<p>
Beskrivelse:<br>
<textarea rows="5" name="CompanyTypeDescription">{description}</textarea>
</p>

<input type="hidden" name="CID" value="{companytype_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>
