<!-- BEGIN tac_tpl -->
<h1>{intl-TaC_headline}</h1>
<hr noshade="noshade" size="4" />

<form method="post" action="{www_dir}{index}/user/userwithaddress/{action_value}/">
<div>{tac_text}</div>
<input type="hidden" name="GlobalSectionIDOverride" value="{global_section_id}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />

<hr noshade="noshade" size="4" />
<p class="boxtext">{intl-agree_text}</p><br />
<input class="button" type="submit" name="Agreement" value="{intl-yes}" />
<input class="button" type="submit" name="Agreement" value="{intl-no}" />
</form>

<!-- END tac_tpl -->


<!-- BEGIN tac_denied_tpl -->
<h1>{intl-TaC_denied_headline}</h1>
<hr noshade="noshade" size="4" />

<p>{no_tac_text}</p>

<!-- END tac_denied_tpl -->

<!-- BEGIN tac_text_only_tpl -->
<h1>{intl-TaC_headline}</h1>
<hr noshade="noshade" size="4" />

<p>{tac_text}</p>

<!-- END tac_text_only_tpl -->
