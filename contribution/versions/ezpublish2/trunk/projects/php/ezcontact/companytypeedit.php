<?
/*
  Editerer firma typer.
*/

include_once( "classes/class.INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezusergroup.php" );
include_once( "classes/ezsession.php" );
include_once( "common/ezphputils.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
    if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {


        // Legge til firma type.
        if ( $Action == "insert" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminAdd" ) == 1 ) )
        {
            $type = new eZCompanyType();
            $type->setName( $CompanyTypeName );
            $type->setDescription( $CompanyTypeDescription );
            $type->store(); 

            Header( "Location: index.php?page=" . $DOC_ROOT . "companytypelist.php" ); 
        }

        // Oppdatere firma type.
        if ( $Action == "update" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminEdit" ) == 1 ) )
        {
            $type = new eZCompanyType();
            $type->get( $CID );
  
            $type->setName( $CompanyTypeName );
            $type->setDescription( $CompanyTypeDescription );
            $type->update();

            Header( "Location: index.php?page=" . $DOC_ROOT . "companytypelist.php" ); 
        }

        // Slette firma type.
        if ( $Action == "delete" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminDelete" ) == 1 ) )
        {
            $type = new eZCompanyType();
            $type->get( $CID );
            $type->delete( );

            Header( "Location: index.php?page=" . $DOC_ROOT . "companytypelist.php" ); 
        }

        // Setter template.
        $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "companytypeedit.php" );
        $t->setAllStrings();

        $t->set_file( array(
            "companytype_edit_page" => "companytypeedit.tpl"
            ) );    

        $t->set_var( "submit_text", "Legg til" );
        $t->set_var( "action_value", "insert" );
        $t->set_var( "companytype_id", "" );
        $t->set_var( "head_line", "Legg til ny firmatype" );

        // Editere firma type.
        if ( $Action == "edit" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminEdit" ) == 1 ) )
        {
            $type = new eZCompanyType();
            $type->get( $CID );
  
            $CompanyTypeName = $type->name();
            $CompanyTypeDescription = $type->description();

            $t->set_var( "submit_text", "Lagre endringer" );
            $t->set_var( "action_value", "update" );
            $t->set_var( "companytype_id", $CID );
            $t->set_var( "head_line", "Rediger firmatype" );
        }

        // Sette template variabler.
        $t->set_var( "document_root", $DOC_ROOT );

        $t->set_var( "companytype_name", $CompanyTypeName );
        $t->set_var( "description", $CompanyTypeDescription );

        $t->pparse( "output", "companytype_edit_page" );
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
