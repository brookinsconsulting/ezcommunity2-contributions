<form method="post" action="/contact/companytypeedit/{action_value}/{companytype_id}/">
<h1>{intl-headline}</h1>

<p>
{intl-name}<br>
<input type="text" name="CompanyTypeName" value="{companytype_name}">
</p>

<p>
{intl-desc}<br>
<textarea rows="5" name="CompanyTypeDescription">{description}</textarea>
</p>

<input type="hidden" name="CID" value="{companytype_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>
