<form method="post" action="/todo/prioritytypeedit/">
<h1>{head_line}</h1>

<p>{intl-name}<br>
<input type="text" name="Title" value="{priority_type_name}"><br></p>

<input type="hidden" name="PriorityID" value="{priority_type_id}">
<input type="hidden" name="Action" value="{action_value}">

<input type="submit" value="{submit_text}">

</form>