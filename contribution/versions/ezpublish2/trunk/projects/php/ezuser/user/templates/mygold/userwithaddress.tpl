
<form method="post" action="{www_dir}{index}/user/userwithaddress/{action_value}/{user_id}/">
  <!-- BEGIN new_user_tpl -->
  <h1>{intl-head_line}</h1>
  <!-- END new_user_tpl -->
  <!-- BEGIN edit_user_tpl -->
  <h1>{intl-edit_head_line}</h1>
  <!-- END edit_user_tpl -->
  <hr noshade size="1" />
  <!-- BEGIN info_item_tpl -->
  <ul>
    <!-- BEGIN info_updated_tpl -->
    <li>{intl-info_update_user}</li>
    <!-- END info_updated_tpl -->
  </ul>
  <hr noshade size="1"/>
  <!-- END info_item_tpl -->
  <!-- BEGIN errors_item_tpl -->
  <h3 class="error">{intl-error_headline}</h3>
  <ul>
    <!-- BEGIN error_login_tpl -->
    <li>{intl-error_login}</li>
    <!-- END error_login_tpl -->
    <!-- BEGIN error_login_exists_tpl -->
    <li>{intl-error_login_exists}</li>
    <!-- END error_login_exists_tpl -->
    <!-- BEGIN error_first_name_tpl -->
    <li>{intl-error_first_name}</li>
    <!-- END error_first_name_tpl -->
    <!-- BEGIN error_last_name_tpl -->
    <li>{intl-error_last_name}</li>
    <!-- END error_last_name_tpl -->
    <!-- BEGIN error_email_tpl -->
    <li>{intl-error_email}</li>
    <!-- END error_email_tpl -->
    <!-- BEGIN error_email_not_valid_tpl -->
    <li>{intl-error_email_not_valid}</li>
    <!-- END error_email_not_valid_tpl -->
    <!-- BEGIN error_password_too_short_tpl -->
    <li>{intl-error_password_too_short}</li>
    <!-- END error_password_too_short_tpl -->
    <!-- BEGIN error_password_match_tpl -->
    <li>{intl-error_passwordmatch_item}</li>
    <!-- END error_password_match_tpl -->
    <!-- BEGIN error_address_street1_tpl -->
    <li>{intl-error_street1}</li>
    <!-- END error_address_street1_tpl -->
    <!-- BEGIN error_address_street2_tpl -->
    <li>{intl-error_street2}</li>
    <!-- END error_address_street2_tpl -->
    <!-- BEGIN error_address_zip_tpl -->
    <li>{intl-error_zip}</li>
    <!-- END error_address_zip_tpl -->
    <!-- BEGIN error_address_place_tpl -->
    <li>{intl-error_place}</li>
    <!-- END error_address_place_tpl -->
    <!-- BEGIN error_missing_address_tpl -->
    <li>{intl-error_missing_address}</li>
    <!-- END error_missing_address_tpl -->
    <!-- BEGIN error_missing_country_tpl -->
    <li>{intl-error_missing_country}</li>
    <!-- END error_missing_country_tpl -->
  </ul>
  <hr noshade size="1"/>
  <!-- END errors_item_tpl -->
  <!-- BEGIN edit_user_info_tpl -->
  <p>{intl-edit_usage}</p>
  <!-- END edit_user_info_tpl -->
  <br />
  <table width="100%" cellspacing="0" cellpadding="3" border="0">
    <tr> 
      <td width="1%"> <b>{intl-firstname}:</b><br />
        <input type="text" size="20" name="FirstName" value="{first_name_value}"/>
      </td>
      <td> <b>{intl-lastname}:</b><br />
        <input type="text" size="20" name="LastName" value="{last_name_value}"/>
      </td>
    </tr>
    <tr> 
      <td colspan="2"><b>{intl-login}:</b><br />
        <!-- BEGIN login_item_tpl -->
        <input type="text" size="20" name="Login" value="{login_value}"/>
        <!-- END login_item_tpl -->
        <!-- BEGIN disabled_login_item_tpl -->
        {login_value} 
        <!-- END disabled_login_item_tpl -->
      </td>
    </tr>
    <tr> 
      <td colspan="2"><b>{intl-email}:</b><br />
        <input type="text" size="20" name="Email" value="{email_value}"/>
      </td>
    </tr>
    <tr> 
      <td> <b>{intl-password}:</b><br />
        <input type="password" size="20" name="Password" value="{password_value}"/>
      </td>
      <td> <b>{intl-verifypassword}:</b><br />
        <input type="password" size="20" name="VerifyPassword" value="{verify_password_value}"/>
      </td>
    </tr>

    <tr> 
      <td colspan="2"> 
        <hr noshade size="1"/>
      </td>
    </tr>
    <tr> 
      <td valign="top"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <!-- BEGIN address_tpl -->
		  <tr> 
            <td> 
              <h2>{intl-address_number} {address_number} </h2>
              <input type="hidden" name="AddressArrayID[]" value="{address_id}">
            </td>
          </tr>
          <tr> 
            <td> 
              <!-- BEGIN main_address_tpl -->
              <input {is_checked} type="radio" class="white" name="MainAddressID" value="{address_id}">
              <span class="check">{intl-main_address}</span> 
              <!-- END main_address_tpl -->
              <!-- BEGIN delete_address_tpl -->
              <input type="checkbox" class="white" name="DeleteAddressArrayID[]" value="{address_id}">
              {intl-delete} 
              <!-- END delete_address_tpl -->
              <input type="hidden" name="AddressID[]" value="{address_id}"/>
            </td>
          </tr>
          <tr> 
            <td><b>{intl-street1}:</b><br />
              <input type="text" size="20" name="Street1[]" value="{street1_value}"/>
            </td>
          </tr>
          <tr> 
            <td>{intl-street2}:<br />
              <input type="text" size="20" name="Street2[]" value="{street2_value}"/>
            </td>
          </tr>
          <tr> 
            <td><b>{intl-zip}:</b><br />
              <input type="text" size="20" name="Zip[]" value="{zip_value}"/>
            </td>
          </tr>
          <tr> 
            <td><b>{intl-place}:</b><br />
              <input type="text" size="20" name="Place[]" value="{place_value}"/>
            </td>
          </tr>
          <tr> 
            <td> 
              <!-- BEGIN country_tpl -->
              <b>{intl-country}:</b><br />
              <select name="CountryID[]">
                <!-- BEGIN country_option_tpl -->
                <option {is_selected} value="{country_id}">{country_name}</option>
                <!-- END country_option_tpl -->
              </select>
              <!-- END country_tpl -->
            </td>
          </tr>
         <!-- END address_tpl -->		  
        </table>
      </td>
      <td valign="top"> <br>
        <table width="95%" border="0" cellspacing="0" cellpadding="1" bgcolor="#003366" align="center">
          <tr> 
            <td> 
              <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#FFFFFF">
                <tr> 
                  <td class="small"> <b>RECHNUNGS- UND LIEFERADRESSEN ERSTELLEN</b><br />
                    Sie haben hier die M&ouml;glichkeit eine oder mehrere Adressen 
                    anzulegen. Sie m&uuml;ssen mindestens eine Adresse angeben, 
                    an die wir die Rechnung und auch die Lieferung schicken k&ouml;nnen. 
                    Sollten Sie die Lieferung an eine andere Adresse w&uuml;nschen, 
                    bet&auml;tigen Sie nachdem Sie die erste Adresse ausgef&uuml;llt 
                    haben den Button &quot;Weitere Adresse hinzuf&uuml;gen&quot; 
                    und f&uuml;llen danach die Felder mit einer weiteren Adresse 
                    aus. Sie k&ouml;nne dies beliebig oft wiederholen. <br />
                    Bitte kennzeichnen Sie Ihre Rechnungsadresse als solche indem 
                    Sie den Radiobutton &quot;Rechnungsadresse&quot; bei der entsprechenden 
                    Adresse anw&auml;hlen.<br />
                    Um eine Adresse zu l&ouml;schen markieren Sie die Checkbox 
                    &quot;l&ouml;schen&quot; aus und bet&auml;tigen Sie danach 
                    durch das Bet&auml;tigen des &quot;Ausgew&auml;hlte Adresse 
                    l&ouml;schen&quot; Buttons.<br />
                    Wenn Sie alle Adressen hinzugef&uuml;gt haben w&auml;hlen 
                    Sie den Button &quot;OK&quot; und Sie gelangen zu der Seite 
                    zur&uuml;ck, von der Sie gekommen sind. </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <hr noshade size="1"/>      
        <b>Fettgedruckte</b> Felder m&uuml;ssen ausgef&uumlllt werden.<br />
        <input type="checkbox" class="white" name="AutoCookieLogin" {is_cookie_selected} />
        {intl-auto_cookie_login}<br />
        <input class="white" {info_subscription} type="checkbox" name="InfoSubscription" />
        &nbsp;{intl-infosubscription}
        <hr noshade size="1"/>
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <!-- BEGIN address_actions_tpl -->
        <input class="okbutton" type="submit" value="{intl-new_address}" name="NewAddress" />
        <input class="okbutton" type="submit" value="{intl-delete_address}" name="DeleteAddress" />
        <!-- END address_actions_tpl -->
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <hr noshade size="1" />
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <input type="hidden" name="UserID" value="{user_id}" />
        <!-- BEGIN ok_button_tpl -->
        <input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
        <!-- END ok_button_tpl -->
        <!-- BEGIN submit_button_tpl -->
        <input class="okbutton" type="submit" name="OK" value="{intl-submit}" />
        <!-- END submit_button_tpl -->
        <input type="hidden" name="RedirectURL" value="{redirect_url}" />
      </td>
    </tr>
  </table>
  
  <br />
</form>
