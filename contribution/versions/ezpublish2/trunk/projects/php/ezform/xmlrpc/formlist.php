<?
// eZ form classes
include_once( "ezform/classes/ezform.php" );

$forms = array();

$formList =& eZForm::getAll( 0, false );
foreach ( $formList as $form )
{
    $forms[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezform",
                                                                    "form",
                                                                    $form->id() ),
                                          "Name" => new eZXMLRPCString( $form->name() ) ) );
}

$part_arr = array( "Offset" => new eZXMLRPCInt( 0 ),
                   "Total" => new eZXMLRPCInt( count( $formList ) ),
                   "Begin" => new eZXMLRPCBool( true ),
                   "End" => new eZXMLRPCBool( true ) );
$part = new eZXMLRPCStruct( $part_arr );

$ReturnData = new eZXMLRPCStruct( array( "Catalogues" => array(),
                                         "Elements" => $forms,
                                         "Part" => $part ) ); // array starting with top level catalogue, ending with parent.
?>
