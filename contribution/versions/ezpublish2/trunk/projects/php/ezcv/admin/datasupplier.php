<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "cv":
    {
        $Action = $url_array[3];
        $CVID = $url_array[4];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "insert":
            case "edit":
            case "update":
            case "delete":
            {
                include( "ezcv/admin/cvedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcv/admin/cvlist.php" );
                break;
            }
            case "view":
            {
                include( "ezcv/admin/cvlist.php" );
                break;
            }
        }
        break;
    }

    case "extracurricular":
    {
        $Action = $url_array[3];
        $ExtracurricularID = $url_array[4];
        
        switch( $Action )
        {
            // intentional fall through
            case "new":
            case "insert":
            case "edit":
            case "update":
            case "delete":
            {
                include( "ezcv/admin/extracurricularedit.php" );
                break;
            }
            default:
                header( "Location: /error.php?type=404&reason=missingpage&hint[]=/cv/extracurricular/$operation/&module=ezcv" );
                exit();               
        }
        break;
    }
    case "experience":
    {
        $Action = $url_array[3];
        $ExperienceID = $url_array[4];
        
        switch( $Action )
        {
            // intentional fall through
            case "new":
            case "insert":
            case "edit":
            case "update":
            case "delete":
            {
                include( "ezcv/admin/experienceedit.php" );
                break;
            }
            default:
                header( "Location: /error.php?type=404&reason=missingpage&hint[]=/cv/experience/$operation/&module=ezcv" );
                exit();               
        }
        break;
    }
    case "certificate":
    {
        $Action = $url_array[3];
        $CertificateID = $url_array[4];
        if( !is_numeric( $CVID ) )
        {
            header( "Location: /error.php?type=404&reason=missinginfo&hint[]=/cv/certificate/$operation/&module=ezcv" );
            exit();               
        }
        
        switch( $Action )
        {
            // intentional fall through
            case "new":
            case "insert":
            case "edit":
            case "update":
            case "delete":
            {
                include( "ezcv/admin/certificateedit.php" );
                break;
            }
            default:
                header( "Location: /error.php?type=404&reason=missingpage&hint[]=/cv/certificate/$operation/&module=ezcv" );
                exit();
                break;             
        }
        break;
    }
    case "education":
    {
        $Action = $url_array[3];
        $EducationID = $url_array[4];
        
        switch( $Action )
        {
            // intentional fall through.
            case "new":
            case "insert":
            case "update":
            case "delete":
            case "edit":
            {
                include( "ezcv/admin/educationedit.php" );
                break;
            }
            default:
                header( "Location: /error.php?type=404&reason=missingpage&hint[]=/cv/education/$operation/&module=ezcv" );
                exit();               
        }
        break;
    }
    case "certificatecategory":
    {
        $Action = $url_array[3];
        $TypeID = $url_array[4];
        switch( $Action )
        {
            // intentional fall through.
            case "new":
            case "insert":
            case "update":
            case "delete":
            case "edit":
            {
                include( "ezcv/admin/certificatecategoryedit.php" );
                break;
            }
            case "view":
                include( "ezcv/admin/certificatecategoryview.php" );
                break;
            case "list":
                include( "ezcv/admin/certificatecategoryview.php" );
                break;
        }
        break;
    }
    case "certificatetype":
    {
        $Action = $url_array[3];
        $TypeID = $url_array[4];
        switch( $Action )
        {
            // intentional fall through
            case "new":
            case "insert":
            case "edit":
            case "update":
            case "delete":
            {
                include( "ezcv/admin/certificatetypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcv/admin/certificatetypeview.php" );
                break;
            }
            case "view":
            {
                include( "ezcv/admin/certificatetypeview.php" );
                break;
            }
        }
        break;
    }
    case "sex":
    {
        $Action = $url_array[3];
        $SexID = $url_array[4];
        switch( $url_array[3] )
        {
            // intentional fall through
            case "new":
            case "insert":
            case "edit":
            case "update":
            case "delete":
            {
                include( "ezcv/admin/sexedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcv/admin/sexlist.php" );
                break;
            }
            case "view":
            {
                include( "ezcv/admin/sexview.php" );
                break;
            }
        }
        break;
    }

    case "armystatus":
    {
        $Action = $url_array[3];
        $ArmyStatusID = $url_array[4];
        switch( $url_array[3] )
        {
            // intentional fall through
            case "new":
            case "insert":
            case "edit":
            case "update":
            case "delete":
            {
                include( "ezcv/admin/armystatusedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcv/admin/armystatuslist.php" );
                break;
            }
            case "view":
            {
                include( "ezcv/admin/armystatusview.php" );
                break;
            }
        }
        break;
    }

    case "workstatus":
    {
        $Action = $url_array[3];
        $WorkStatusID = $url_array[4];
        switch( $url_array[3] )
        {
            // intentional fall through
            case "new":
            case "insert":
            case "edit":
            case "update":
            case "delete":
            {
                include( "ezcv/admin/workstatusedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcv/admin/workstatuslist.php" );
                break;
            }
            case "view":
            {
                include( "ezcv/admin/workstatusview.php" );
                break;
            }
        }
        break;
    }

    case "maritalstatus":
    {
        $Action = $url_array[3];
        $MaritalStatusID = $url_array[4];
        switch( $url_array[3] )
        {
            // intentional fall through
            case "new":
            case "insert":
            case "edit":
            case "update":
            case "delete":
            {
                include( "ezcv/admin/maritalstatusedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcv/admin/maritalstatuslist.php" );
                break;
            }
            case "view":
            {
                include( "ezcv/admin/maritalstatusview.php" );
                break;
            }
        }
        break;
    }

    default :
        header( "Location: /error.php?type=404&reason=missingpage&hint[]=/contact/company/list/&hint[]=/contact/person/list&module=ezcv" );
        break;
}

?>
