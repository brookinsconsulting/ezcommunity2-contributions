<form action="{www_dir}{index}/form/report/setup/text/store/{report_id}/{table_id}/{element_id}/" method="post">

<h1>{intl-text_setup}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-original_text}:</p>
<div>{original_text}</div><br />
<textarea class="box" name="OverrideText" cols="40" rows="5" wrap="soft">{field_value}</textarea><br />
<input type="checkbox" value="Delete" name="NoOverride" />{intl-no_override}

<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
