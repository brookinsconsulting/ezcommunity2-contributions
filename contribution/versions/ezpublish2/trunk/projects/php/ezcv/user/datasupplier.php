<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "cv":
    {
        switch ( $url_array[3] )
        {
            case "list":
            {
                $Action = "list";
                $CVID = $url_array[4];
                include( "ezcv/user/cvlist.php" );
                break;
            }
            case "view":
            {
                $Action = "view";
                $CVID = $url_array[4];
                include( "ezcv/user/cvlist.php" );
                break;
            }
            case "new":
            {
                $Action = "new";
                include( "ezcv/user/cvedit.php" );
                break;
            }
            case "insert":
            {
                $Action = "insert";
                include( "ezcv/user/cvedit.php" );
                break;
            }
            case "edit":
            {
                $Action = "edit";
                $CVID = $url_array[4];
                include( "ezcv/user/cvedit.php" );
                break;
            }

            case "update":
            {
                $Action = "update";
                $CVID = $url_array[4];
                include( "ezcv/user/cvedit.php" );
                break;
            }
            
            case "delete":
            {
                $Action = "delete";
                $CVID = $url_array[4];
                include( "ezcv/user/cvedit.php" );
                break;
            }
        }
        break;
    }

    case "extracurricular":
    {
        $operation = $url_array[3];
        
        switch( $operation )
        {
            case "new":
                $Action = "new";
                include( "ezcv/user/extracurricularedit.php" );
                break;
            case "edit":
                $Action = "edit";
                $ExtracurricularID = $url_array[4];
                include( "ezcv/user/extracurricularedit.php" );
                break;
            case "insert":
                $Action = "insert";
                $ExtracurricularID = $url_array[4];
                include( "ezcv/user/extracurricularedit.php" );
                break;
            case "update":
                $Action = "insert";
                $ExtracurricularID = $url_array[4];
                include( "ezcv/user/extracurricularedit.php" );
                break;
            case "delete":
                $Action = "delete";
                $ExtracurricularID = $url_array[4];
                include( "ezcv/user/extracurricularedit.php" );
                break;
            default:
                header( "Location: /error.php?type=404&reason=missingpage&hint[]=/cv/extracurricular/$operation/&module=ezcv" );
                exit();               
        }
        break;
    }
    case "experience":
    {
        $operation = $url_array[3];
        
        switch( $operation )
        {
            case "new":
                $Action = "new";
                include( "ezcv/user/experienceedit.php" );
                break;
            case "edit":
                $Action = "edit";
                $ExperienceID = $url_array[4];
                include( "ezcv/user/experienceedit.php" );
                break;
            case "insert":
                $Action = "insert";
                $ExperienceID = $url_array[4];
                include( "ezcv/user/experienceedit.php" );
                break;
            case "update":
                $Action = "insert";
                $ExperienceID = $url_array[4];
                include( "ezcv/user/experienceedit.php" );
                break;
            case "delete":
                $Action = "delete";
                $ExperienceID = $url_array[4];
                include( "ezcv/user/experienceedit.php" );
                break;
            default:
                header( "Location: /error.php?type=404&reason=missingpage&hint[]=/cv/experience/$operation/&module=ezcv" );
                exit();               
        }
        break;
    }
    case "certificate":
    {
        $operation = $url_array[3];
        if( !is_numeric( $CVID ) )
        {
            header( "Location: /error.php?type=404&reason=missinginfo&hint[]=/cv/certificate/$operation/&module=ezcv" );
            exit();               
        }
        
        switch( $operation )
        {
            case "new":
                $Action = "new";
                include( "ezcv/user/certificateedit.php" );
                break;
            case "edit":
                $Action = "edit";
                $CertificateID = $url_array[4];
                include( "ezcv/user/certificateedit.php" );
                break;
            case "insert":
                $Action = "insert";
                $CertificateID = $url_array[4];
                include( "ezcv/user/certificateedit.php" );
                break;
            case "update":
                $Action = "insert";
                $CertificateID = $url_array[4];
                include( "ezcv/user/certificateedit.php" );
                break;
            case "delete":
                $Action = "insert";
                $CertificateID = $url_array[4];
                include( "ezcv/user/certificateedit.php" );
                break;
            default:
                header( "Location: /error.php?type=404&reason=missingpage&hint[]=/cv/certificate/$operation/&module=ezcv" );
                exit();               
        }
        break;
    }
    case "education":
    {
        $operation = $url_array[3];
        
        switch( $operation )
        {
            case "new":
                $Action = "new";
                include( "ezcv/user/educationedit.php" );
                break;
            case "edit":
                $Action = "edit";
                $EducationID = $url_array[4];
                include( "ezcv/user/educationedit.php" );
                break;
            case "insert":
                $Action = "insert";
                $EducationID = $url_array[4];
                include( "ezcv/user/educationedit.php" );
                break;
            case "update":
                $Action = "insert";
                $EducationID = $url_array[4];
                include( "ezcv/user/educationedit.php" );
                break;
            case "delete":
                $Action = "delete";
                $EducationID = $url_array[4];
                include( "ezcv/user/educationedit.php" );
                break;
            default:
                header( "Location: /error.php?type=404&reason=missingpage&hint[]=/cv/education/$operation/&module=ezcv" );
                exit();               
        }
        break;
    }
    case "certificatetype":
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                $PersonID = $url_array[4];
                $Action = "list";
                include( "ezcv/user/personlist.php" );
                break;
            }
            case "new":
            {
                $Action = "new";
                include( "ezcv/user/personedit.php" );
                break;
            }
            case "insert":
            {
                $PersonID = $url_array[4];
                $Action = "insert";
                include( "ezcv/user/personview.php" );
                break;
            }
            case "view":
            {
                $PersonID = $url_array[4];
                $Action = "view";
                include( "ezcv/user/personview.php" );
                break;
            }
            case "edit":
            {
                $PersonID = $url_array[4];
                $Action = "edit";
                include( "ezcv/user/personedit.php" );
                break;
            }
            case "update":
            {
                $PersonID = $url_array[4];
                $Action = "update";
                include( "ezcv/user/personedit.php" );
                break;
            }
            case "delete":
            {
                $PersonID = $url_array[4];
                $Action = "delete";
                include( "ezcv/user/persondelete.php" );
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
