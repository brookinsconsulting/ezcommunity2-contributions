<form method="post" action="/contact/addresstypeedit/{action_value}/{address_type_id}/">
<h1>{intl-headline}</h1>

<p>{intl-name}<br>
<input type="text" name="AddressTypeName" value="{address_type_name}"><br></p>

<input type="hidden" name="AID" value="{address_type_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>