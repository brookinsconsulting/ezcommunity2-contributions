<?
include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

if( $Command == "data" ) // return all the data in the category
{
    $file = new eZVirtualFile();
    if ( $file->get( $ID ) )
    {
        $writeGroups = eZObjectPermission::getGroups( $ID, "filemanager_file", 'w', false );
        $readGroups = eZObjectPermission::getGroups( $ID, "filemanager_file", 'r', false );
        $rgp = array();
        $wgp = array();
        foreach( $readGroups as $group )
            $rgp[] = new eZXMLRPCInt( $group );
        foreach( $writeGroups as $group )
            $wgp[] = new eZXMLRPCInt( $group );
        $originalFileName = $file->originalFileName();
        $filePath = $file->filePath( true );
        $location = "/filemanager/filedownload/$filePath/$originalFileName";
        $ReturnData = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $file->name() ),
                                                 "Description" => new eZXMLRPCString( $file->description() ),
                                                 "WebURL" => new eZXMLRPCString( $location ),
                                                 "FileName" => new eZXMLRPCString( $file->fileName() ),
                                                 "OriginalFileName" => new eZXMLRPCString( $file->originalFileName() ),
                                                 "UserID" => new eZXMLRPCInt( $file->user( false ) ),
                                                 "FileSize" => new eZXMLRPCInt( $file->fileSize() ),
                                                 "ReadGroups" => new eZXMLRPCArray( $rgp ),
                                                 "WriteGroups" => new eZXMLRPCArray( $wgp )
                                                 ) );
    }
    else
    {
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
}
else if ( $Command == "storedata" )
{
    eZLog::writeNotice( "file: $ID" );
    if ( isset( $Data["Name"] ) and isset( $Data["Description"] ) )
    {
        $name = $Data["Name"]->value();
        $description = $Data["Description"]->value();
        $file = new eZVirtualFile();
        if ( $ID != 0 )
        {
            if ( !$file->get( $ID ) )
                $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
        }
        if ( !$Error )
        {
            $file->setName( $name );
            $file->setDescription( $description );
            if ( isset( $Data["File"] ) and isset( $Data["FileName"] ) )
            {
                $file_data = $Data["File"]->value();
                $orig_file = $Data["FileName"]->value();
                $file_store = new eZFile();
                $file_store->dumpDataToFile( $file_data, $orig_file );
                if ( !$file->setFile( $file_store ) )
                {
                    $Error = createErrorMessage( EZERROR_CUSTOM, "Failed to set file sent by client to virtual file $ID",
                                                 EZFILEMANAGER_BAD_FILE );
                }
            }
            if ( !$Error )
            {
                $file->store();

                $ID = $file->id();

                $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezfilemanager", "file", $ID ),
                                                         // TODO: Fix Path
                                                         "Path" => new eZXMLRPCArray( array() ),
                                                         "UpdateType" => new eZXMLRPCString( $Command )
                                                         )
                                                  );
                $Command = "update";
            }
        }
    }
    else
    {
        $Error = createErrorMessage( EZERROR_BAD_REQUEST_DATA );
    }
}
else if( $Command == "delete" )
{
    $file = new eZVirtualFile();
    if ( $file->get( $ID ) )
    {
        $file->delete();
        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezfilemanager", "file", $ID ),
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                                 ) );
        $Command = "update";
    }
    else
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
}
?>
