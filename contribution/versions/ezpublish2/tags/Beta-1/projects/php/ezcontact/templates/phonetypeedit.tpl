<form method="post" action="/contact/phonetypeedit/{action_value}/{phone_type_id}/">
<h1>{intl-headline}</h1>

<p>{intl-name}<br>
<input type="text" name="PhoneTypeName" value="{phone_type_name}"><br></p>

<input type="hidden" name="PID" value="{phone_type_id}">
<input type="hidden" name="Action" value="{action_value}">

<input type="submit" value="{submit_text}">

</form>