<?
/*
  Editerer en person.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezusergroup.php" );

include_once( "common/ezphputils.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezaddress.php" );
include_once( "ezcontact/classes/ezaddresstype.php" );
include_once( "ezcontact/classes/ezphone.php" );
include_once( "ezcontact/classes/ezphonetype.php" );
include_once( "ezcontact/classes/ezpersonphonedict.php" );
include_once( "ezcontact/classes/ezpersonaddressdict.php" );
include_once( "ezcontact/classes/ezpersonconsultdict.php" );
include_once( "ezcontact/classes/ezconsult.php" );
include_once( "ezcontact/classes/ezcompanyconsultdict.php" );
include_once( "ezcontact/topmenu.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
    if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {
        // Oppdatere informasjon.
        if ( $Action == "update" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Edit" ) == 1 ) 
        {
            $updatePerson = new eZPerson();
            $updatePerson->get( $PID );
            $updatePerson->setFirstName( $FirstName );
            $updatePerson->setLastName( $LastName );
            $updatePerson->setContactType( $PersonType );
            $updatePerson->setCompany( $CompanyID );
            $updatePerson->setComment( $Comment );
            $updatePerson->update();

            $Action = "edit";
        }

        // Slette person fra databasen.
        if ( $Action == "delete" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Delete" ) == 1 )
        {
            $deletePerson = new eZPerson();
            $deletePerson->get ( $PID );
            $deletePerson->delete();

            Header( "Location: index.php?page=" . $DOC_ROOT . "contactlist.php" ); 
        }

        // Legge til kontakt person.
        if ( $Action == "insert" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Add" ) == 1 )
        {
            $newPerson = new eZPerson();
            $newPerson->setFirstName( $FirstName );
            $newPerson->setLastName( $LastName );
            $newPerson->setContactType( $PersonType );

            $newPerson->setCompany( $CompanyID );
            $newPerson->setComment( $Comment );

            {
                // hente ut gjeldene bruker.
                $session = new eZSession();
                $session->get( $AuthenticatedSession ); 
                $usr = new eZUser();
                $usr->get( $session->userID() );
            }
            $newPerson->setOwner( $usr->id() );
            $pid = $newPerson->store();

            $PhoneNumber = "";
            $Action = "edit";
            $PID = $pid;
  
    
            //    $newAddress = new eZAddress();
            //    $newAddress->setStreet1( $Street1 );
            //    $newAddress->setStreet2( $Street2 );
            //    $newAddress->setZip( $Zip );
            //    $aid = $newAddress->store();
            //    $link = new eZPersonAddressDict();
            //    $link->setPersonID( $pid );
            //    $link->setAddressID( $aid );
            //    $link->store();
        }

        // Legge til telefon.
        if ( $PhoneAction == "AddPhone" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Add" ) == 1 )
        {
            $phone = new eZPhone();
            $phone->setNumber( $PhoneNumber );
            $phone->setType( $PhoneType );
            $pid = $phone->store();

            $phone_dict = new eZPersonPhoneDict();

            $phone_dict->setPersonID( $PID );
            $phone_dict->setPhoneID( $pid );
            $phone_dict->store();
            $PhoneNumber = "";    
        }

        // Oppdatere telefon.
        if ( $PhoneAction == "UpdatePhone" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Edit" ) == 1 )
        {
            $phone = new eZPhone();
            $phone->get( $PhoneID );
            $phone->setNumber( $PhoneNumber );
            $phone->setType( $PhoneType );
            $phone->update();
            $PhoneNumber = "";    

        }

        // Slette telefon.
        if ( $PhoneAction == "DeletePhone" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Delete" ) == 1 )
        {
            $phone = new eZPhone();
            $phone->get( $PhoneID );

            $dict = new eZPersonPhoneDict();
            $dict->getByPhone( $phone->id() );
    
            $phone->delete();
            $dict->delete();
        }

        // Oppdatere konsultasjon.
        if ( $ConsultAction == "UpdateConsult" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Edit" ) == 1 )
        {
            $consult = new eZConsult();
            $consult->get( $ConsultID );
            $consult->setTitle( $ConsultTitle );
            $consult->setBody( $ConsultBody );
            $consult->update();
        }

        // Legge til konsultasjon.
        if ( $ConsultAction == "AddConsult" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Add" ) == 1 )
        {

            // henter ut brukeren som er logget inn
            $session = new eZSession();

            if ( !$session->get( $AuthenticatedSession ) )
            {
                die( "Du må logge deg på." );    
            }

            $consult = new eZConsult();
            $consult->setTitle( $ConsultTitle );
            $consult->setBody( $ConsultBody );
            $consult->setUserID( $session->userID() );
            $cid = $consult->store();

            $dict = new eZPersonConsultDict();
            $dict->setPersonID( $PID );
            $dict->setConsultID( $cid );

            $dict->store();

            $Title = "";
            $Body = "";
        }

        // Slette konsultasjon.
        if ( $ConsultAction == "DeleteConsult" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Delete" ) == 1 )
        {
            $consult = new eZConsult();
            $consult->get( $ConsultID );

            $dict = new eZPersonConsultDict();
            $dict->getByConsult( $consult->id() );

            $consult->delete();
            $dict->delete();

            Header( "Location: index.php?page=ezcontact/personedit.php&Action=edit&PID=" . $PID );
        }

        // Legge til adresse.
        if ( $AddressAction == "AddAddress" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Add" ) == 1 )
        {
            $address = new eZAddress( );
            $address->setStreet1( $Street1 );
            $address->setStreet2( $Street2 );
            $address->setZip( $Zip );
            $address->setAddressType( $AddressType );
            $aid = $address->store();

            $dict = new eZPersonAddressDict();
            $dict->setPersonID( $PID );
            $dict->setAddressID( $aid );
            $dict->store();

            $Street1 = "";
            $Street2 = "";
            $Zip = "";    
        }

        // Oppgradere  adresse.
        if ( $AddressAction == "UpdateAddress" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Edit" ) == 1 )
        {
            $address = new eZAddress( );
            $address->get( $AddressID );
            $address->setStreet1( $Street1 );
            $address->setStreet2( $Street2 );
            $address->setZip( $Zip );    
            $address->setAddressType( $AddressType );
            $address->update();

            $Street1 = "";
            $Street2= "";
            $Zip = "";    
        }

        // Slette adresse.
        if ( $AddressAction == "DeleteAddress" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Delete" ) == 1 )
        {
            $address = new eZAddress( );
            $address->get( $AddressID );

            $dict = new eZPersonAddressDict();
            $dict->getByAddress( $address->id() );
    
            $address->delete();
            $dict->delete();
        }
       
        // Sette template.
        $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "personedit.php" );
        $t->setAllStrings();

        $t->set_file( array(                    
            "person_edit" => "personedit.tpl",
            "person_type_select" => "persontypeselect.tpl",
            "company_select" => "companyselect.tpl",
            "address_type_select" => "addresstypeselect.tpl",
            "phone_type_select" => "phonetypeselect.tpl",
            "phone_item" => "phoneitem.tpl",
            "address_item" => "addressitem.tpl",
            "consult_item" => "consultitem.tpl"
            ) );


        $message = "Registrer ny kontaktperson";
        $submit_text = "Legg til";
        $action_value = "insert";

        $person = new eZPerson();
        $personType = new eZPersonType();
        $company = new eZCompany();
        $addressType = new eZAddressType();
        $phoneType = new eZPhoneType();

        $person_type_array = $personType->getAll( );
        $company_array = $company->getAll( );
        $address_type_array = $addressType->getAll( );
        $phone_type_array = $phoneType->getAll( );

        $t->set_var( "phone_action_type", "hidden" );
        $t->set_var( "phone_list", "" );
        $t->set_var( "consult_list", "" );

        $t->set_var( "address_action_type", "hidden" );
        $t->set_var( "address_list", "" );


        // Address type selector.
        for ( $i=0; $i<count( $address_type_array ); $i++ )
        {
            $t->set_var( "address_type_id", $address_type_array[$i][ "ID" ] );
            $t->set_var( "address_type_name", $address_type_array[$i][ "Name" ] );
  
            if ( $Address_Type == $address_type_array[$i][ "ID" ] )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
            {
                $t->set_var( "is_selected", "" );    
            }

            $address_select_dict[ $address_type_array[$i][ "ID" ] ] = $i;
  
            $t->parse( "address_type", "address_type_select", true );
        }

        $phone_select_dict = "";
        
        // Telefon type selector.
        for ( $i=0; $i<count( $phone_type_array ); $i++ )
        {
            $t->set_var( "phone_type_id", $phone_type_array[$i][ "ID" ] );
            $t->set_var( "phone_type_name", $phone_type_array[$i][ "Name" ] );
  
            if ( $Phone_Type == $phone_type_array[$i][ "ID" ] )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
            {
                $t->set_var( "is_selected", "" );    
            }

            $phone_select_dict[ $phone_type_array[$i][ "ID" ] ] = $i;
  
            $t->parse( "phone_type", "phone_type_select", true );
        }

        // Editere kontakt person.
        if ( $Action == "edit" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Edit" ) == 1 )
        {
            $editPerson = new eZPerson();
            $editPerson->get( $PID );
    
            $FirstName = $editPerson->firstName();
            $LastName = $editPerson->lastName();
            $PersonType = $editPerson->contactType();

            $CompanyID = $editPerson->company();
            $Comment = $editPerson->comment();

            $message = "Rediger personinformasjon";
            $submit_text = "Endre informasjon";    
            $action_value = "update";
            $person_id = $PID;

            // Telefonliste.
            $phone = new eZPhone();
            $phone_dict = new eZPersonPhoneDict();
            $phone_dict_array = $phone_dict->getByPerson( $PID );

            for ( $i=0; $i<count( $phone_dict_array ); $i++ )
            {
                $phone->get( $phone_dict_array[ $i ][ "PhoneID" ] );
                $phoneType->get( $phone->type() );

                $t->set_var( "phone_id", $phone->id() );
                $t->set_var( "phone_number", $phone->number() );
                $t->set_var( "phone_type_name", $phoneType->name() );

                $t->set_var( "phone_type_id", $phone_select_dict[ $phoneType->id() ] );

                $t->set_var( "script_name", "personedit.php" );
        
                $t->parse( "phone_list", "phone_item", true );
            }


            // Adresseliste.
            $address = new eZAddress();
            $address_dict = new eZPersonAddressDict();
            $address_dict_array = $address_dict->getByPerson( $PID );
    
            for ( $i=0; $i<count( $address_dict_array ); $i++ )
            {
                $address->get( $address_dict_array[ $i ][ "AddressID" ] );
                $addressType->get( $address->addressType() );
        
                $t->set_var( "address_id", $address->id() );
                $t->set_var( "address_street1", $address->street1() );
                $t->set_var( "address_street2", $address->street2() );
                $t->set_var( "address_zip", $address->zip() );
                $t->set_var( "address_type_name", $addressType->name() );

                $t->set_var( "address_type_id", $address_select_dict[ $addressType->id() ] );

                $t->set_var( "person_id", $PID );

                $t->set_var( "script_name", "personedit.php" );        
        
                $t->parse( "address_list", "address_item", true );                
            }

            // Konsultasjonliste.
            $consult = new eZConsult();
            $consult_dict = new eZPersonConsultDict();
            $consult_dict_array = $consult_dict->getByPerson( $PID );

            for ( $i=0; $i<count( $consult_dict_array ); $i++ )
            {

                $consult->get( $consult_dict_array[ $i ][ "ConsultID" ] ) ;
                // $consult->get( $consult_dict_array[ $i ][ "PersonID" ] );

                $t->set_var( "consult_id", $consult->id() );
                $t->set_var( "consult_title", $consult->title() );
                $t->set_var( "consult_body", $consult->body() );

                $t->set_var( "person_id", $PID );
        

                $t->parse( "consult_list", "consult_item", true );
            }


            $t->set_var( "consult_action", "AddConsult" );
            $t->set_var( "consult_action_value", "Legg tillll" );
            $t->set_var( "consult_action_type", "submit" );
            $t->set_var( "consult_edit_id", "-1" );

            $t->set_var( "address_action", "AddAddress" );    
            $t->set_var( "address_action_value", "Legg til" );
            $t->set_var( "address_action_type", "submit" );    

            $t->set_var( "phone_action", "AddPhone" );
            $t->set_var( "phone_edit_id", "-1" );
            $t->set_var( "phone_action_value", "Legg til" );
            $t->set_var( "phone_action_type", "submit" );
        }


        // Person type selector.
        for ( $i=0; $i<count( $person_type_array ); $i++ )
        {
            $t->set_var( "person_type_id", $person_type_array[$i][ "ID" ] );
            $t->set_var( "type", $person_type_array[$i][ "Name" ] );
  
            if ( $PersonType == $person_type_array[$i][ "ID" ] )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
            {
                $t->set_var( "is_selected", "" );    
            }
  
            $t->parse( "person_type", "person_type_select", true );
        }

        // Company type selector.
        for ( $i=0; $i<count( $company_array ); $i++ )
        {
            $t->set_var( "company_type_id", $company_array[$i][ "ID" ] );
            $t->set_var( "company", $company_array[$i][ "Name" ] );
  
            if ( $CompanyID == $company_array[$i][ "ID" ] )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
            {
                $t->set_var( "is_selected", "" );    
            }
  
            $t->parse( "company_type", "company_select", true );
        }

        // Setter tempalte variabler.
        $t->set_var( "first_name", $FirstName );
        $t->set_var( "last_name", $LastName );

        $t->set_var( "comment", $Comment );

        $t->set_var( "street_1", "" );
        $t->set_var( "street_2", "" );
        $t->set_var( "zip_code", "" );

        $t->set_var( "consult_title", "" );
        $t->set_var( "consult_body", "" );
        $t->set_var( "consult_edit_id", $ConsultID );

        $t->set_var( "phone_edit_number", $PhoneNumber );
        $t->set_var( "phone_edit_id", $PhoneID );

        $t->set_var( "submit_text", $submit_text );
        $t->set_var( "action_value", $action_value );
        $t->set_var( "message", $message );

        $t->set_var( "document_root", $DOC_ROOT );
        $t->set_var( "person_id", $PID );

        $t->pparse( "output", "person_edit"  );
    }
    else
    {
        print( "\nDu har ikke rettigheter\n" );
    }
}
else
{
    Header( "Location: index.php?page=common/error.php" );
}
?>
