<form action="/message/edit/" method="post">

<h1>{intl-message_edit} </h1>

<hr size="4" noshade="noshade" />
<br />

<p class="boxtext">{intl-receiver}:</p>
<input class="box" type="text" name="Receiver" size="40" value="" />
<br /><br />


<p class="boxtext">{intl-subject}:</p>
<input class="box" type="text" name="Subject" size="40" value="" />
<br /><br />

<p class="boxtext">{intl-description}:</p>
<textarea class="box" name="Description" cols="40" rows="5" wrap="soft"></textarea>
<br /><br />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="SendMessage" value="{intl-send}" />

</form>
