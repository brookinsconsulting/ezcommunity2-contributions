<?
include  "template.inc";
require "ezphputils.php";

require "classes/ezperson.php";
require "classes/ezpersontype.php";
require "classes/ezsession.php";
require "classes/ezuser.php";
require "classes/ezcompany.php";
require "classes/ezcompanytype.php";
require "classes/ezaddress.php";
require "classes/ezphone.php";
require "classes/ezphonetype.php";
require "classes/ezzip.php";
require "classes/ezcompanyaddressdict.php";
require "classes/ezcompanyphonedict.php";
require "classes/ezaddresstype.php";

$t = new Template( ".");
$t->set_file( array(
    "address_info" =>  "templates/addressinfo.tpl",
    "phone_info" =>  "templates/phoneinfo.tpl",
    "company_info" =>  "templates/companyinfo.tpl" ) );

$company = new eZCompany();
$company->get( $CID );

$company_type = new eZCompanyType();
$company_type->get( $company->contactType() );
$t->set_var( "company_name", $company->name() );
$t->set_var( "comment", $company->comment() );
$t->set_var( "contact_name", $company->contactType() );

$usr = new eZUser();
$usr->get( $company->owner() );
$t->set_var( "owner", $usr->login() );


// adresser:
{
    $dict = new eZCompanyAddressDict();
    $dict_array = $dict->getByCompany( $company->id() );

    for ( $i=0; $i<count( $dict_array ); $i++ )
    {
        $address = new eZAddress();
        $address->get( $dict_array[ $i ][ "AddressID" ] );
        
        $address_type = new eZAddressType();
        $address_type->get( $address->addressType() );    
        
        $t->set_var( "address_type", $address_type->name() );
        

        $t->set_var( "street1", $address->street1()  );
        $t->set_var( "street2", $address->street2() );
        $t->set_var( "zip", $address->zip()  );

        $zip = new eZZip();
        $zip->get( $address->zip() );
         
        $t->set_var( "place", $zip->place() );
        $t->parse( "address_info_list", "address_info", true );
    }
}

//telefonnummer
$dict = new eZCompanyPhoneDict();
$dict_array = $dict->getByCompany( $company->id() );

for ( $i=0; $i<count( $dict_array ); $i++ )
{
    $phone = new eZPhone();
    $phone->get( $dict_array[ $i ][ "PhoneID" ] );

    $phone_type = new eZPhoneType();
    $phone_type->get( $phone->type() );    

    $t->set_var( "phone_type", $phone_type->name() );
    
    $t->set_var( "phone_number", $phone->number() );

    $t->parse( "phone_info_list", "phone_info", true );
}


$t->pparse( "output", "company_info" );
?>
