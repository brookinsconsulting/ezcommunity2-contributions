
<h1>{intl-welcome} - {first_name} {last_name}</h1>

<hr noshade="noshade" size="4" />

{intl-welcome_message}

<br />

<!-- BEGIN error_tpl -->
<hr noshade="noshade" size="4" />
<h3 class="error">{intl-error_headline}</h3>
<ul>
     <!-- BEGIN libxml_error_tpl -->
     <li />{intl-libxml_error} <a href="{www_dir}{index}{libxml_location}">{libxml_location}</a>
     <!-- END libxml_error_tpl -->

     <!-- BEGIN qtdom_error_tpl -->
     <li />{intl-qtdom_error} <a href="{www_dir}{index}{qtdom_location}">{qtdom_location}</a>
     <!-- END qtdom_error_tpl -->

     <!-- BEGIN convert_error_tpl -->
     <li />{intl-convert_error} <a href="{www_dir}{index}{convert_location}">{convert_location}</a>
     <!-- END convert_error_tpl -->
</ul>
<!-- END error_tpl -->
