<?
/*
  Editere firma.
*/

include_once( "classes/class.INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezusergroup.php" );
include_once( "classes/ezuser.php" );

include_once( "common/ezphputils.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezaddress.php" );
include_once( "ezcontact/classes/ezaddresstype.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
include_once( "ezcontact/classes/ezphone.php" );
include_once( "ezcontact/classes/ezphonetype.php" );
include_once( "ezcontact/classes/ezcompanyphonedict.php" );
include_once( "ezcontact/classes/ezcompanyaddressdict.php" );
include_once( "ezcontact/classes/ezconsult.php" );
include_once( "ezcontact/classes/ezcompanyconsultdict.php" );
include_once( "ezcontact/topmenu.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
    if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {
        // Legger til et firma.
        if ( $Action == "insert" )
        {
            $newCompany = new eZCompany();
            $newCompany->setName( $CompanyName );  
            $newCompany->setContactType( $CompanyType );

            $newCompany->setComment( $Comment );

            { // Hente ut gjeldene bruker.
                $session = new eZSession();
                $session->get( $AuthenticatedSession ); 
                $usr = new eZUser();
                $usr->get( $session->userID() );
            }
            $newCompany->setOwner( $usr->id() );
            $cid = $newCompany->store();

            $CID = $cid;
            $Action = "edit";

            //
            // Adresse og telefonnummer funker ikke med oppretting
            // pga at det er ulike forms.. adresser og telefonnummer
            // må derfor legges til etterpå.
            //
    
            // adresss

//      $newAddress = new eZAddress();
//      $newAddress->setStreet1( $Street1 );
//      $newAddress->setStreet2( $Street2 );
//      $newAddress->setZip( $Zip );
//      $newAddress->setAddressType( $AddressType );
//      $aid = $newAddress->store();

//      $dict = new eZCompanyAddressDict( );
//      $dict->setCompanyID( $cid );
//      $dict->setAddressID( $aid );
//      $dict->store();

            // telefonnummer
//      $phone = new eZPhone( );
//      $phone->setNumber( $PhoneNumber );
//      $phone->setType( $PhoneType );
//      $pid = $phone->store();    
  
//      $dict = new eZCompanyPhoneDict();
  
//      $dict->setCompanyID( $cid );
//      $dict->setPhoneID( $pid );
//      $dict->store();  
        }

        // Oppdaterer et firma.
        if ( $Action == "update" )
        {
            $company = new eZCompany();
            $company->get( $CID  );
    
            $company->setName( $CompanyName );  
            $company->setContactType( $CompanyType );

            $company->setComment( $Comment );

            $company->update();
        }

        // Slette fra company list.
        if ( $Action == "delete" )
        {
            $deleteCompany = new eZCompany();
            $deleteCompany->get( $CID );
            $deleteCompany->delete();

            Header( "Location: index.php?page=" . $DOC_ROOT . "contactlist.php" );
        }

        // Legger til telefon.
        if ( $PhoneAction == "AddPhone" )
        {
            $phone = new eZPhone( );
            $phone->setNumber( $PhoneNumber );
            $phone->setType( $PhoneType );
            $pid = $phone->store();    
    
            $dict = new eZCompanyPhoneDict();

            $dict->setCompanyID( $CID );
            $dict->setPhoneID( $pid );
            $dict->store();
            $PhoneNumber = "";
        }

        // Oppgraderer telefon.
        if ( $PhoneAction == "UpdatePhone" )
        {
            $phone = new eZPhone( );
            $phone->get( $PhoneID );
    
            $phone->setNumber( $PhoneNumber );
            $phone->setType( $PhoneType );
            $phone->update();

            $PhoneNumber = "";    
        }

        // Sletter telefon.
        if ( $PhoneAction == "DeletePhone" )
        {
            $phone = new eZPhone( );
            $phone->get( $PhoneID );

            $dict = new eZCompanyPhoneDict();
            $dict->getByPhone( $phone->id() );
    
            $phone->delete();
            $dict->delete();
        }

        // Oppdatere en konsultasjon
        if ( $ConsultAction == "UpdateConsult" )
        {
            $consult = new eZConsult();
            $consult->get( $ConsultID );
            $consult->setTitle( $ConsultTitle );
            $consult->setBody( $ConsultBody );
            $consult->update();
        }

        // Legge til en konsultasjon.
        if ( $ConsultAction == "AddConsult" )
        {
            // Henter ut brukeren som er logget inn.
            $session = new eZSession();

            if ( !$session->get( $AuthenticatedSession ) )
            {
                die( "Du må logge den på" );
            }

            $consult = new eZConsult();
            $consult->setTitle( $ConsultTitle );
            $consult->setBody( $ConsultBody );
            $consult->setUserID( $session->userID() );
            $cid = $consult->store();

            $dict = new eZCompanyConsultDict();
            $dict->setCompanyID( $CID );
            $dict->setConsultID( $cid );

            $dict->store();

            $Title = "";
            $Body = "";
        }

        // Sletter en konsultasjon.
        if ( $ConsultAction == "DeleteConsult" )
        {
            $consult = new eZConsult();
            $consult->get( $ConsultID );

            $dict = new eZCompanyConsultDict();
            $dict->getByConsult( $consult->id());

            $consult->delete();
            $dict->delete();

            Header( "Location: index.php?page=ezcontact/companyedit.php&Action=edit&CID=" . $CID );
        }

       // Legger til addresse.
        if ( $AddressAction == "AddAddress" )
        {
            $address = new eZAddress( );
            $address->setStreet1( $Street1 );
            $address->setStreet2( $Street2 );
            $address->setZip( $Zip );
            $address->setAddressType( $AddressType );
            $pid = $address->store();

            $dict = new eZCompanyAddressDict();

            $dict->setCompanyID( $CID );
            $dict->setAddressID( $pid );
            $dict->store();

            $Street1 = "";
            $Street2= "";
            $Zip = "";    
        }

        // Oppdaterer en adresse.
        if ( $AddressAction == "UpdateAddress" )
        {
            $address = new eZAddress( );
            $address->get( $AddressID );
            $address->setStreet1( $Street1 );
            $address->setStreet2( $Street2 );
            $address->setZip( $Zip );
            $address->setAddressType( $AddressType );
            $address->update();

            //      $dict = new eZCompanyAddressDict();

            //      $dict->setCompanyID( $CID );
            //      $dict->setAddressID( $pid );
            //      $dict->store();
            $Street1 = "";
            $Street2= "";
            $Zip = "";
        }

        // Sletter en adresse.
        if ( $AddressAction == "DeleteAddress" )
        {
            $address = new eZAddress( );
            $address->get( $AddressID );

            $dict = new eZCompanyAddressDict();
            $dict->getByAddress( $address->id() );
    
            $address->delete();
            $dict->delete();
        }

        // Setter template.
        $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "companyedit.php" );
        $t->setAllStrings();

        $t->set_file( array(                    
            "company_edit" => "companyedit.tpl",
            "company_type_select" => "companytypeselect.tpl",
            "address_type_select" => "addresstypeselect.tpl",
            "phone_type_select" => "phonetypeselect.tpl",
            "phone_item" => "phoneitem.tpl",
            "address_item" => "addressitem.tpl",
            "consult_item" => "consultcompanyitem.tpl"
            ) );

        if ( !isset( $Action ) )
            $Action = "insert";

        $company = new eZCompany();
        $companyType = new eZCompanyType();
        $company = new eZCompany();
        $addressType = new eZAddressType();
        $phoneType = new eZPhoneType();

        $message = "Registrer nytt kontaktfirma";

        $company_type_array = $companyType->getAll( );
        $address_type_array = $addressType->getAll( );
        $phone_type_array = $phoneType->getAll();

        $t->set_var( "phone_action_type", "hidden" );
        $t->set_var( "phone_list", "" );
        $t->set_var( "consult_list", "" );

        $t->set_var( "address_action_type", "hidden" );
        $t->set_var( "address_list", "" );



        $address_select_dict = "";
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

        // Redigering av firma.
        if ( $Action == "edit" )
        {
            $company = new eZCompany();
            $company->get( $CID );
    
            $CompanyName = $company->name();
            $Comment = $company->comment();
            $CompanyType = $company->contactType();

            $message = "Rediger firmainformasjon";

            $phone = new eZPhone( );
    
            $phone_dict = new eZCompanyPhoneDict();
    
            $phone_dict_array = $phone_dict->getByCompany( $CID );

            // Telefonliste.
            for ( $i=0; $i<count( $phone_dict_array ); $i++ )
            {
                $phone->get( $phone_dict_array[ $i ][ "PhoneID" ] );
                $phoneType->get( $phone->type() );
        
                $t->set_var( "phone_id", $phone->id() );
                $t->set_var( "phone_number", $phone->number() );
                $t->set_var( "phone_type_name", $phoneType->name() );

                $t->set_var( "phone_type_id", $phone_select_dict[ $phoneType->id() ] );

                $t->set_var( "script_name", "companyedit.php" );
                $t->set_var( "company_id", $CID );
                
                $t->parse( "phone_list", "phone_item", true );                
            }

            $address = new eZAddress();
            $address_dict = new eZCompanyAddressDict();
            $address_dict_array = $address_dict->getByCompany( $CID );
    
            // Adresseliste.
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

                $t->set_var( "company_id", $CID );
        
                $t->set_var( "script_name", "companyedit.php" );
        
                $t->parse( "address_list", "address_item", true );                
            }

            $consult = new eZConsult();
            $consult_dict = new eZCompanyConsultDict();
            $consult_dict_array = $consult_dict->getByCompany( $CID );

            // Konsultasjonliste.
            for ( $i=0; $i<count( $consult_dict_array); $i++ )
            {
                $consult->get( $consult_dict_array[ $i ][ "ConsultID" ] );

                $t->set_var( "consult_id", $consult->id() );
                $t->set_var( "consult_title", $consult->title() );
                $t->set_var( "consult_body", $consult->body() );
                $t->set_var( "company_id", $CID );

                $t->parse( "consult_list", "consult_item", true );
            }


            // Template variabler.
            $Action = "update";

            $t->set_var( "consult_action", "AddConsult" );
            $t->set_var( "consult_action_value", "Legg til" );
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

        // Company type selector må være UNDER edit fordi at rett firmatype
        // skal bli satt..
        for ( $i=0; $i<count( $company_type_array ); $i++ )
        {
            $t->set_var( "company_type_id", $company_type_array[$i][ "ID" ] );
            $t->set_var( "company_type_name", $company_type_array[$i][ "Name" ] );
  
            if ( $CompanyType == $company_type_array[$i][ "ID" ] )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
            {
                $t->set_var( "is_selected", "" );    
            }
  
            $t->parse( "company_type", "company_type_select", true );
        }

        // Template variabler.
        $t->set_var( "company_name", $CompanyName );

        $t->set_var( "company_comment", $Comment );

        $t->set_var( "street_1", $Street1 );
        $t->set_var( "street_2", $Street2 );
        $t->set_var( "zip_code", $Zip );

        $t->set_var( "consult_title", "" );
        $t->set_var( "consult_body", "" );
        $t->set_var( "consult_edit_id", $ConsultID );

        $t->set_var( "phone_edit_number", $PhoneNumber );
        $t->set_var( "phone_edit_id", $PhoneID );

        $t->set_var( "submit_text", "lagre endringer" );

        $t->set_var( "message", $message );
        $t->set_var( "document_root", $DOC_ROOT );

        $t->set_var( "edit_mode", $Action );
        $t->set_var( "company_id", $CID );

        $t->pparse( "output", "company_edit"  );
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
