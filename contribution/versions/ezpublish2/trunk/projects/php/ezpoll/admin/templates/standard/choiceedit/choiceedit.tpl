<form method="post" action="/poll/choiceedit/{action_value}/{poll_id}/{choice_id}/">

<h1>{head_line}</h1>

<hr noshade size="4">

<p class="boxtext">{intl-name}</p>
<input type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-offset}</p>
<input type="text" size="10" name="Offset" value="{offset_value}"/>
<br /><br />

<hr noshade size="4">

<input type="hidden" name="PollID" value="{poll_id}" />
<input class="okbutton" type="submit" value="OK" />

<form method="post" action="/poll/polledit/"><input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>

</form>


