<?php
//
// $Id: ezrfp.php,v 1.1.2.01 2003/10/07 10:17:10 gb Exp $
//
// Definition of eZRfp class
//
// Created on: <07-Oct-2003 06:38:24 gb>
//
// This source file is part of xpublish, publishing software.
//
// Copyright (C) 2001-2003 Brookins Consulting.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZRfp
//! eZRfp handles rfps.
/*!

  Example code:
  \code
  $category = new eZRfpCategory();
  $category->setName( "Programming" );
  $category->setDescription( "Lots of programming rfps" );

  $category->store();

  $rfp = new eZRfp( );
  $rfp->setName( "C++" );
  $rfp->setContents( "An rfp about the fine art of C++ .... .... ... ... .... ... " );
  $rfp->setAuthorText( "Bård Farstad" );

  $rfp->store();

  $category->addRfp( $rfp );
  \endcode

  \sa eZRfpCategory

*/

/*!TODO
  Add delayed fetching of the rfp contents.

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/eztexttool.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezauthor.php" );

include_once( "ezcontact/classes/ezperson.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );

// include_once( "ezmediacatalogue/classes/ezmedia.php" );

// include_once( "ezforum/classes/ezforum.php" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfpattribute.php" );
include_once( "ezrfp/classes/ezrfptype.php" );
include_once( "ezprocurement/classes/ezprocurementbid.php" );

include_once( "ezrfp/classes/fnc_viewArray.php" );
include_once( "ezrfp/classes/fnc_viewArray3.php" );

// #####################################################################################

function in_array_multi($needle, $haystack) {
   if (!is_array($haystack)) return false;
   while (list($key, $value) = each($haystack)) {
       if (is_array($value) && in_array_multi($needle, $value) || $value === $needle) {
           return true;
       }
   }
   return false;
}

function diff_days($start_date, $end_date)
{
  $diff = $start_date - $end_date;
   return floor(abs($diff/86400));
}

// #####################################################################################


class eZRfp
{
    /*!
      Constructs a new eZRfp object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZRfp( $id="" )
    {
        // default value
        $this->IsPublished = "0";
        $this->PublishDate = 0;
        $this->ResponseDueDate = 0;

        $this->PublishedOverride = 0;

        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a product to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name = $db->escapeString( $this->Name );
        $contents = $db->escapeString( $this->Contents );
        $authortext = $db->escapeString( $this->AuthorText );
        $authoremail = $db->escapeString( $this->AuthorEmail );
        $keywords = $db->escapeString( $this->Keywords );
        $importID = $db->escapeString( $this->ImportID );
        $linktext = $db->escapeString( $this->LinkText );

	$contentsWriters = $db->escapeString( $this->ContentsWriters );
        //	echo("this is after serialization before storage: $contentsWriters<br>");

	//v_array($contentsWriters);
	//$contentsWriters = serialize( $contentsWriters );
	//echo("the string after serialization == $contentsWriters");
	//	die("<br>exiting at store function ~163");
        $projectEstimate = $db->escapeString( $this->ProjectEstimate );
        $projectNumber = $db->escapeString( $this->ProjectNumber );

        if ( is_object( $this->PublishDate ) and $this->PublishDate->isValid() )
            $publishDate = $this->PublishDate->timeStamp();
        else
            $publishDate = $this->PublishDate;

        if ( is_object( $this->ResponseDueDate ) and $this->ResponseDueDate->isValid() )
            $responceDueDate = $this->ResponseDueDate->timeStamp();
        else
            $responceDueDate = $this->ResponseDueDate;

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZRfp_Rfp" );

            $nextID = $db->nextID( "eZRfp_Rfp", "ID" );

            $timeStamp =& eZDateTime::timeStamp( true );

            if ( $this->PublishedOverride != 0 )
                $published = $this->PublishedOverride;
            else
                $published = $timeStamp;


            // fix for informix blob field
            $contentsStr = "'$contents'";

            if ( $db->isA() == "informix" )
            {
                $textid = ifx_create_blob( 0, 0, $this->Contents );

//                $textid = ifx_create_char( $contents );
                $blobIDArray[] = $textid;
                $contentsStr = "?";
                $db->setBlobArray( $blobIDArray );

            }

            $ret = $db->query( "INSERT INTO eZRfp_Rfp
		                        ( ID,
                                 Name,
                                 Contents,
                                 HolderID,
				 LinkText,
                                 PageCount,
                                 IsPublished,
                                 Keywords,
                                 Discuss,
                                 ContentsWriterID,
				 ContentsWriters,
                                 TopicID,
                                 PublishDate,
                                 ResponseDueDate,
                                 Modified,
                                 Published,
                                 Created,
                                 ImportID,
				 ProjectEstimate,
                                 ProjectNumber )
                                 VALUES
                                 ( '$nextID',
		                   '$name',
                                    $contentsStr,
                                   '$this->HolderID',
                                   '$linktext',
                                   '$this->PageCount',
                                   '$this->IsPublished',
                                   '$keywords',
                                   '$this->Discuss',
                                   '$this->ContentsWriterID',
				   '$contentsWriters',
                                   '$this->TopicID',
                                   '$publishDate',
                                   '$responceDueDate',
                                   '$timeStamp',
                                   '$published',
                                   '$timeStamp',
                                   '$importID',
				   '$projectEstimate',
                                   '$projectNumber'  )
                                 " );

			$this->ID = $nextID;
        }
        else
        {

            // fix for informix blob field
            $contentsStr = "Contents='$contents',";

            if ( $db->isA() == "informix" )
            {
                ifx_textasvarchar(0);
                $db->array_query( $res, "SELECT ID, Contents FROM eZRfp_Rfp WHERE ID='$this->ID'" );


                $bid = $res[0][$db->fieldName("Contents")];
                // fetch the blob id
                $res = ifx_update_blob( $bid, $this->Contents );

                if ( !$res  )
                {
                    print( "Error updating informix text blob" );
                    die();
                }

                $blobIDArray[] = $bid;
                $db->setBlobArray( $blobIDArray );

                $contentsStr = "Contents=?,";
                ifx_textasvarchar(1);
            }


            $db->array_query( $res, "SELECT ID FROM eZRfp_Rfp WHERE IsPublished='0' AND ID='$this->ID'" );

            $timeStamp =& eZDateTime::timeStamp( true );

            if ( $this->PublishedOverride != 0 )
                $published = $this->PublishedOverride;
            else
                $published = $timeStamp;

            if ( ( count( $res ) > 0 ) && ( $this->IsPublished == "1" ) )
            {
                $ret = $db->query( "UPDATE eZRfp_Rfp SET
		                         Name='$name',
                                 $contentsStr
                                 LinkText='$linktext',
                                 PageCount='$this->PageCount',
                                 HolderID='$this->HolderID',
                                 IsPublished='$this->IsPublished',
                                 Keywords='$keywords',
                                 Discuss='$this->Discuss',
                                 ContentsWriterID='$this->ContentsWriterID',
				 ContentsWriters='$this->ContentsWriters',
                                 TopicID='$this->TopicID',
                                 PublishDate='$publishDate',
                                 ResponseDueDate='$responceDueDate',
                                 Published='$published',
                                 Modified='$timeStamp',
                                 ImportID='$importID',
				 ProjectEstimate='$projectEstimate',
                                 ProjectNumber='$projectNumber'

                                 WHERE ID='$this->ID'
                                 " );
            }
            else
            {
                if ( $this->PublishedOverride != 0 )
                    $published = $this->PublishedOverride;
                else
                    $published = $this->Published;

                $ret = $db->query( "UPDATE eZRfp_Rfp SET
		                         Name='$name',
                                 $contentsStr
                                 LinkText='$linktext',
                                 PageCount='$this->PageCount',
                                 HolderID='$this->HolderID',
                                 IsPublished='$this->IsPublished',
                                 Keywords='$keywords',
                                 Discuss='$this->Discuss',
                                 ContentsWriterID='$this->ContentsWriterID',
				 ContentsWriters='$this->ContentsWriters',
                                 TopicID='$this->TopicID',
                                 Published='$published',
                                 PublishDate='$publishDate',
                                 ResponseDueDate='$responceDueDate',
                                 Modified='$timeStamp',
                                 ImportID='$importID',
				 ProjectEstimate='$projectEstimate',
                                 ProjectNumber='$projectNumber'

                                 WHERE ID='$this->ID'
                                 " );
            }
        }

        $db->unlock();

        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit();

        // index this rfp
        $this->createIndex();

        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $rfp_array, "SELECT * FROM eZRfp_Rfp WHERE ID='$id'" );
            if ( count( $rfp_array ) > 1 )
            {
                die( "Error: Rfp's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $rfp_array ) == 1 )
            {
                $this->fill( $rfp_array[0] );
                $ret = true;
            }
        }
        return $ret;
    }

    function fill( $rfp_array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $rfp_array[$db->fieldName("ID")];
        $this->Name =& $rfp_array[$db->fieldName("Name")];
        $this->Contents =& $rfp_array[$db->fieldName("Contents")];
        $this->AuthorText =& $rfp_array[$db->fieldName("AuthorText")];
        $this->AuthorEmail =& $rfp_array[$db->fieldName("AuthorEmail")];
        $this->HolderID =& $rfp_array[$db->fieldName("HolderID")];
        $this->LinkText =& $article_array[$db->fieldName("LinkText")];
        $this->ProjectEstimate =& $rfp_array[$db->fieldName("ProjectEstimate")];
        $this->ProjectNumber =& $rfp_array[$db->fieldName("ProjectNumber")];

        $this->Modified =& $rfp_array[$db->fieldName("Modified")];
        $this->Created =& $rfp_array[$db->fieldName("Created")];
        $this->Published =& $rfp_array[$db->fieldName("Published")];

        $this->PageCount =& $rfp_array[$db->fieldName("PageCount")];
        $this->IsPublished =& $rfp_array[$db->fieldName("IsPublished")];
        $this->Keywords =& $rfp_array[$db->fieldName("Keywords")];
        $this->Discuss =& $rfp_array[$db->fieldName("Discuss")];


        $this->ContentsWriterID =& $rfp_array[$db->fieldName("ContentsWriterID")];

	// this will turn it into an array - dylan note
	//$this->ContentsWriterID = unserialize($this->ContentsWritersID);
	/*
  	 $this->ContentsWriters =& $rfp_array[$db->fieldName("ContentsWriters")];
	*/
        $contentsWriters = & $rfp_array[$db->fieldName("ContentsWriters")];
        $contentsWriters = unserialize( $contentsWriters );

	$this->ContentsWriters =& $contentsWriters;
	$this->oContentsWriters = $this->ContentsWriters;

        $this->PublishDate =& $rfp_array[$db->fieldName("PublishDate")];
        $this->ResponseDueDate =& $rfp_array[$db->fieldName("ResponseDueDate")];

        $this->ImportID =& $rfp_array[$db->fieldName("ImportID")];

        if ( $this->PublishDate == 0 )
            $this->PublishDate = false;
        if ( $this->ResponseDueDate == 0 )
            $this->ResponseDueDate = false;
    }

    /*!
        \static
        Returns the one, and only if one exists, rfp with the name

        Returns an object of eZRfp.
     */
    function &getByName( $name )
    {
        $db =& eZDB::globalDatabase();

        $topic =& new eZRfp();

        $name = $db->escapeString( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZRfp_Rfp WHERE Name='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic =& new eZRfp( $author_array[0] );
            }
        }

        return $topic;
    }
    /*!
        \static
        Returns the one, and only if one exists, rfp with the import id

        Returns an object of eZRfp.
     */
    function &getByImportID( $name )
    {
        $db =& eZDB::globalDatabase();

        $topic =& new eZRfp();

        $name = $db->escapeString( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZRfp_Rfp WHERE ImportID='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic =& new eZRfp( $author_array[0] );
            }
        }

        return $topic;
    }


    /*!
      Deletes a eZRfp object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isSet( $this->ID ) )
        {
            $imageList =& $this->images();
            $fileList =& $this->files();
            foreach( $imageList as $image )
            {
//                print_r( $image );

//                $image->delete();
            }
            foreach( $fileList as $file )
            {
//                $file->delete();
            }
            $db->begin();

//            $forum = $this->forum();
//            $forum->delete();
            $res = array();

            $res[] = $db->query( "DELETE FROM eZRfp_RfpCategoryLink WHERE RfpID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZRfp_RfpCategoryDefinition WHERE RfpID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZRfp_RfpImageLink WHERE RfpID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZRfp_RfpImageDefinition WHERE RfpID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZRfp_RfpPermission WHERE ObjectID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZRfp_Rfp WHERE ID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZRfp_AttributeValue WHERE RfpID='$this->ID'" );
//            $res[] = $db->query( "DELETE FROM eZRfp_RfpForumLink WHERE RfpID='$this->ID'" );

            if ( in_array( false, $res ) )
                $db->rollback( );
            else
                $db->commit();

        }

        return true;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the rfp name / title.
    */
    function &name( $asHTML = true )
    {
        if( $asHTML == true )
            return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Name ) );
        return $this->Name;
    }

    /*!
      Returns the rfp import id.
    */
    function &importID( )
    {
        return $this->ImportID;
    }

    /*!
      Returns the rfp project estimate value no $
    */
    function &projectEstimate( )
    {
        return $this->ProjectEstimate;
    }


    /*!
      Sets the rfp project estiamte.
    */
    function setProjectEstimate( $value )
    {
        $this->ProjectEstimate = $value;
    }


    /*!
      Returns the rfp project number value no $
    */
    function &projectNumber( )
    {
        return $this->ProjectNumber;
    }

    /*!
      Sets the rfp project number.
    */
    function setProjectNumber( $value )
    {
        $this->ProjectNumber = $value;
    }


    /*!
      Returns the rfp contents.

      The contents is internally stored as XML.
    */
    function &contents()
    {
        $db =& eZDB::globalDatabase();

        return $this->Contents;
    }



    /*!
      Returns the author text contents.
    */
    function &authorText( $asHTML = true )
    {

        //   $author = new eZAuthor( $this->ContentsWriterID );
	//	$author = new eZUser( $this->ContentsWriterID );

	$ContentsWriterUserSingle = $this->ContentsWriters[0];
	$author = new eZPerson( $ContentsWriterUserSingle );

/*	
	foreach ( $this->ContentsWriters as $Writer) {
		$author = new eZPerson( $Writer );
		//$authors[] .= new eZUser( $Writer );
		$authorNames[] .= $author->name();
		$author_name = $author->name();
		$Names = $author_name .", Jack Durden ";
	}
return $Names;
*/
        if( $asHTML == true )
            return eZTextTool::fixhtmlentities( htmlspecialchars( $author->name() ) );

	// single view
        return $author->name();
	

    }

    /*!
      Returns the author email contents.
    */
    function &authorEmail( $asHTML = true )
    {
        $author = new eZAuthor( $this->ContentsWriterID );

        if( $asHTML == true )
            return htmlspecialchars( $author->email() );
        return $author->email();
    }

	
    /*!
      Returns the link text.
    */
    function &linkText( $asHTML = true )
    {
        if(  $asHTML )
            return eZTextTool::fixhtmlentities( htmlspecialchars( $this->LinkText ) );
        return $this->LinkText;
    }


    /*!
      Returns the author as a eZUser object.
    */
    function &author( $as_object = true )
    {
        if( $as_object )
            $author = new eZPerson( $this->HolderID[2] );
        else
            $author = $this->HolderID;
        return $author;
    }

    /*!
      Returns the categories an rfp is assigned to.

      The categories are returned as an array of eZRfpCategory objects.
    */
    function authors( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $ret = array();
        $db->array_query( $category_array, "SELECT ContentWriterID FROM eZRfp_Rfp WHERE RfpID='$this->ID'" );

        if ( $as_object )
        {
            foreach ( $category_array as $category )
            {
                $ret[] = new eZPerson( $category[$db->fieldName("ContentWriterID")] );
            }
        }
        else
        {
            foreach ( $category_array as $category )
            {
                $ret[] = $category[$db->fieldName("ContentWriterID")];
            }
        }

        return $ret;
    }

    /*!
      Removes every category assignments from the current rfp.
    */
    function removeAuthor()
    {
        $db =& eZDB::globalDatabase();
//	 $db->query( "DELETE FROM eZRfp_Rfp WHERE RfpID='$this->ID'" );

//        $db->query( "DELETE FROM eZRfp_RfpCategoryLink WHERE RfpID='$this->ID'" );

/*
        $ret = array();
        $db->array_query( $category_array, "SELECT ContentWriterID FROM eZRfp_Rfp WHERE RfpID='$this->ID'" );
*/
	 $as_object = true;

        if ( $as_object )
        {
            foreach ( $category_array as $category )
            {
                $ret[] = new eZPerson( $category[$db->fieldName("ContentWriterID")] );
            }
        }
        else
        {
            foreach ( $category_array as $category )
            {
                $ret[] = $category[$db->fieldName("ContentWriterID")];
            }
        }

	//        return $ret;
    }



    /*!
      Returns the number of pages in the rfp.
    */
    function &pageCount()
    {
        return substr_count( $this->Contents, "<page>" );
    }

    /*!
      Returns the creation time of the rfp.

      The time is returned as a eZDateTime object.
    */
    function &created()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Created );

        return $dateTime;
    }

    /*!
      Returns the modification time of the rfp.

      The time is returned as a eZDateTime object.
    */
    function &modified()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Modified );

        return $dateTime;
    }

    /*!
      Returns the keywords of an rfp.
    */
    function keywords( )
    {
        return $this->Keywords;
    }

    /*!
      Returns the discuss value of an rfp.
    */
    function discuss( )
    {
        $ret = false;
        if ( $this->Discuss == 1 )
        {
            $ret = true;
        }
        return $ret;
    }


    /*!
      Returns the last time the rfp was published.

      The time is returned as a eZDateTime object.
    */
    function &published()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Published );

        return $dateTime;
    }

    /*!
      Returns true if the rfp is published false if not.
    */
    function isPublished()
    {
        $ret = false;
        if ( $this->IsPublished == "1" )
        {
            $ret = 1;
        }
        elseif ( $this->IsPublished == "2" )
        {
            $ret = 2;
        }
        return $ret;
    }

    /*!
      Returns the start date of the rfp.
    */
    function &publishDate( $as_object=true )
    {
        if ( $as_object )
        {
            $ret = new eZDateTime();
            $ret->setTimeStamp( $this->PublishDate );
            return $ret;
        }
        else
            return $this->PublishDate;
    }

    /*!
      Returns the responceDue date of the rfp.
    */
    function &responceDueDate( $as_object=true )
    {
        if ( $as_object )
        {
            $ret = new eZDateTime();
            $ret->setTimeStamp( $this->ResponseDueDate );
            return $ret;
        }
        else
            return $this->ResponseDueDate;
    }

    /*!
      Sets the rfp name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the rfp import id.
    */
    function setImportID( $value )
    {
        $this->ImportID = $value;
    }

    /*!
      Sets the contents name.
    */
    function setContents( $value )
    {
        $this->Contents = $value;
    }

    /*!
      Sets the author text.
    */
    function setAuthorText( $value )
    {
        $this->AuthorText = $value;
    }

    /*!
      Sets the author email.
    */
    function setAuthorEmail( $value )
    {
        $this->AuthorEmail = $value;
    }
	
    /*!
      Sets the link text.
    */
    function setLinkText( $value )
    {
        $this->LinkText = $value;
    }


    /*!
      Sets the author of the rfp.
    */
    function setAuthor( $users )
    {

/*
	foreach( $users as $user ){
	  if ( get_class( $users ) == "ezuser" )
          {
	    // $this->HolderID = $user->id();
          }
	}
*/

        if ( get_class( $users ) == "ezuser" )
        {
	  //	    v_array($users);

            // $this->HolderID = $user->id();
	   $this->HolderID = $user->ID;
        //	$this->HolderID = $user->id();

	}
    }

    /*!
      Sets the number of pages in the rfp.
    */
    function setPageCount( $value )
    {
        $this->PageCount = $value;
    }

    /*!
      Sets the keywords to an rfp. Theese words are used in the search.
    */
    function setKeywords( $keywords )
    {
        // replace newlines with space
        $keywords = str_replace ("\n\r", " ", $keywords );
        $keywords = str_replace ("\r\n", " ", $keywords );
        $keywords = str_replace ("\n", " ", $keywords );
        $keywords = str_replace ("\r", " ", $keywords );
        $this->Keywords = $keywords;
    }

    /*!
      \private
      will index the rfp keywords (fetched from Contents) and name for fulltext search.

this section desperate ly will need to be reviewed pending release for inconsistencies.
    */
    function createIndex()
    {
        // generate keywords
        $tmpContents = $this->Contents;

        $tmpContents = str_replace ("</intro>", " ", $tmpContents );
        $tmpContents = str_replace ("</page>", " ", $tmpContents );

        $contents = strtolower( strip_tags( $tmpContents ) ) . " " . $this->Name;
        $contents = str_replace ("\n", "", $contents );
        $contents = str_replace ("\r", "", $contents );
        $contents = str_replace ("(", " ", $contents );
        $contents = str_replace (")", " ", $contents );
        $contents = str_replace (",", " ", $contents );
        $contents = str_replace (".", " ", $contents );
        $contents = str_replace ("/", " ", $contents );
        $contents = str_replace ("-", " ", $contents );
        $contents = str_replace ("_", " ", $contents );
        $contents = str_replace ("\"", " ", $contents );
        $contents = str_replace ("'", " ", $contents );
        $contents = str_replace (":", " ", $contents );
        $contents = str_replace ("?", " ", $contents );
        $contents = str_replace ("!", " ", $contents );
        $contents = str_replace ("\"", " ", $contents );
        $contents = str_replace ("|", " ", $contents );
        $contents = str_replace ("qdom", " ", $contents );
        $contents = str_replace ("tech", " ", $contents );

        // strip &quot; combinations
        $contents = preg_replace("(&.+?;)", " ", $contents );

        // strip multiple whitespaces
        $contents = preg_replace("(\s+)", " ", $contents );

        $contents_array =& split( " ", $contents );
        $contents_array =& array_merge( $contents_array, $this->manualKeywords( true ) );

        $totalWordCount = count( $contents_array );
        $wordCount = array_count_values( $contents_array );

        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            if ( strlen( $word ) >= 2 )
            {
                $keywords .= $word . " ";
            }
        }

        $this->Keywords = $keywords;

        $db =& eZDB::globalDatabase();
        $ret = array();

        $ret[] = $db->query( "DELETE FROM eZRfp_RfpWordLink WHERE RfpID='$this->ID'" );

        // get total number of rfps
        $db->array_query( $rfp_array, "SELECT COUNT(*) AS Count FROM eZRfp_Rfp" );
        $rfpCount = $rfp_array[0][$db->fieldName( "Count" )];

        $db->begin( );

        foreach ( $contents_array as $word )
        {
            if ( strlen( $word ) >= 2 )
            {
                $indexWord = $word;

                $indexWord = $db->escapeString( $indexWord );


                // find the frequency
                $count = $wordCount[$indexWord];

                $freq = ( $count / $totalWordCount );

                $query = "SELECT ID FROM eZRfp_Word
                      WHERE Word='$indexWord'";

                $db->array_query( $word_array, $query );


                if ( count( $word_array ) == 1 )
                {
                    // word exists create reference
                    $wordID = $word_array[0][$db->fieldName("ID")];

                    // number of links to this word
                    $db->array_query( $rfp_array, "SELECT COUNT(*) AS Count FROM eZRfp_RfpWordLink WHERE WordID='$wordID'" );
                    $wordUsageCount = $rfp_array[0][$db->fieldName( "Count" )];

                    $wordFreq = ( $wordUsageCount + 1 )  / $rfpCount;

                    // update word frequency
                    $ret[] = $db->query( "UPDATE  eZRfp_Word SET Frequency='$wordFreq' WHERE ID='$wordID'" );


                    $ret[] = $db->query( "INSERT INTO eZRfp_RfpWordLink ( RfpID, WordID, Frequency ) VALUES
                                      ( '$this->ID',
                                        '$wordID',
                                        '$freq' )" );
                }
                else
                {
                    // lock the table
                    $db->lock( "eZRfp_Word" );

                    $wordFreq = 1 / $rfpCount;

                    // new word, create word
                    $nextID = $db->nextID( "eZRfp_Word", "ID" );
                    $ret[] = $db->query( "INSERT INTO eZRfp_Word ( ID, Word, Frequency ) VALUES
                                      ( '$nextID',
                                        '$indexWord',
                                        '$wordFreq' )" );
                    $db->unlock();

                    $ret[] = $db->query( "INSERT INTO eZRfp_RfpWordLink ( RfpID, WordID, Frequency ) VALUES
                                      ( '$this->ID',
                                        '$nextID',
                                        '$freq' )" );

                }
            }
        }
        eZDB::finish( $ret, $db );

    }



        function extArray($arr)
        {
//      echo '<td>';
//      echo '<table cellpadding="0" cellspacing="0" border="1">';
        foreach ($arr as $key => $elem) {
//      echo '<tr>';
        echo ''.$key.'<br />';
        if (is_array($elem)) { extArray($elem); }
        else { echo '<td>'.htmlspecialchars($elem).'&nbsp;</td>'; }
//      echo '</tr>';
        }
//      echo '</table>';
//      echo '</td>';
        }


        function viewArray($arr)
        {
           echo '';
           foreach ($arr as $key1 => $elem1) {
               echo '';
               echo ''.$key1.' ';
               if (is_array($elem1)) { extArray($elem1); }
               else { echo ''.$elem1."\n"; }
               echo '';
           }
           echo "\n\n";
        }



    /*!
      \private
      will index the rfp keywords (fetched from Contents) and name for fulltext search.
      this section desperate ly will need to be reviewed pending release for inconsistencies.
    */
    function emailRfpReminders()
    {

       $db =& eZDB::globalDatabase();
       $rfp_array =& new eZRfp();

	   // use this->rfpAttribute var style (remember this loops)

       // current date & printable current date
       $current_date = new eZDate();
       $current_date_stamp = $current_date->timeStamp();

	   print( $current_date_stamp ."\n" ); 


	   $future_date = new eZDate();
	   $future_date->move(0,0,-7);
       $future_date_stamp = $future_date->timeStamp();

	   print( $future_date_stamp ."\n");
	
	   $date_gt = $future_date->isGreater($current_date);
       // $date_gt = $current_date->isGreater($future_date);
	   $date_gt = count(rfp_array);
	   print($date_gt."\n");

       // if ($future_date_stamp < $current_date_stamp ) {

	   if (  $future_date->isGreater($current_date) ) {
	   print("Future Date is Greateer Then Current Date <br />");
	   } else {
	   print("Future Date is not Greater Then Current Date <br />");
       }

       $rfpResponceDateMysql = $future_date_stamp;
       // $rfpResponceDateMysql = "bob";

       //      $date = new eZDate( 2000, 9, 2 );
//       $rfpResponceDeadline = $rfp->responceDueDate(true);

       // responce due date & printable
	   //        $rfpResponceDeadline = $rfp->responceDueDate();
	   //
	
//	   $rfpResponceDeadlineStamp = $rfpResponceDeadline->timeStamp();

	   //        $rfpResponceDateMysql = $db->escapeString( $current_date_stamp );
	   //	$rfpResponceDateCheck = $db->escapeString( $rfpResponseDueDate );

       if( $rfpResponceDateMysql != "" )
       {
            $db->array_query( $rfp_array, "SELECT * FROM eZRfp_Rfp WHERE ResponseDueDate <='$rfpResponceDateMysql'" );
			//     
       		$db->array_query( $rfp_array, "SELECT * FROM eZRfp_Rfp" );
	    	$db->array_query( $rfp_array, "SELECT * FROM eZRfp_Rfp WHERE ID = '$this->ID'" );
			/*
            if( count( $rfp_array ) == 1 )
            {
                $rfp_array =& new eZRfp( $rfp_array[0] );
            }
			*/
        }

		// return $rfp_array;
	
//        viewArray($rfp_array);
        // exit();

        //##############################################
//dylan doing stuff here
        include_once( "classes/ezdate.php" );


        $rfp = new eZRfp();

	    $rfps =& $rfp->getAll();
		foreach ( $rfps as $rfp )
		{
        	$deadlineNotice = false;

        	// current date & printable current date
        	$current_date = new eZDate();
        	$current_date_stamp = $current_date->timeStamp();
			

        	//      $date = new eZDate( 2000, 9, 2 );
        	//       $rfpResponceDeadline = $rfp->responceDueDate(true);
			
        	// responce due date & printable
        	$rfpResponceDeadline = $rfp->responceDueDate();
        	$rfpResponceDeadlineStamp = $rfpResponceDeadline->timeStamp();
			
	        $rfpResponceDeadlineMonth = $rfpResponceDeadline->month();
	        $rfpResponceDeadlineDay = $rfpResponceDeadline->day();
	        $rfpResponceDeadlineYear = $rfpResponceDeadline->year();
			$threeDaysAway = strtotime("+3 days");
			if ($rfpResponceDeadlineStamp != 0) {
				 $responseDateCheck = true;
			     }else { $responseDateCheck = false;
			}
			$rfpDateFormatted = date("m-d-Y H:i:s", $rfpResponceDeadlineStamp);
			$currentDateFormatted = date("m-d-Y H:i:s", $current_date_stamp);
			$threeDaysAwayFormatted = date("m-d-Y H:i:s", $threeDaysAway);
			print('<div style="padding: 5px;margin: 10px 10px 10px 10px; border: dashed 1px red;">');
			print("Current Date is: <b>$currentDateFormatted</b><br>");
			if (!$responseDateCheck) { 
			print("<font color=red><b>The Response Date is set at 0!</b></font><br>"); 
			} else {
			print("<font color=purple>Deadline Date is: <b>$rfpDateFormatted</b></font><br>");
			}
			print("<font color=blue>3 days after now is: <b>$threeDaysAwayFormatted</b></font><br>");
			//checks if the date is past
			if ( $rfpResponceDeadlineStamp < $current_date_stamp ) {
				print("<font color=orange><b>The deadline of $rfpDateFormatted has already passed!</b></font><br>");
			}
			//checks if the deadline is more than three days in the future
			else if ( $rfpResponceDeadlineStamp > $current_date_stamp && $rfpResponceDeadlineStamp > $threeDaysAway) {
				print("<font color=red><b>The deadline of $rfpDateFormatted is more than three days away from current date $currentDateFormatted using the check of $threeDaysAwayFormatted . This means we do not send email.</b></font><br>");
			
			} else {
				//if it's not, past, and it's not more than three days in the future, and it's not bigger than a breadbox, it must be a cantalope!
				print("<font color=green><b>The deadline of $rfpDateFormatted is less than three days away from current date $currentDateFormatted using the check of $threeDaysAwayFormatted . This means we send the email.</b></font><br><br />");
				//lets now find how soon it is
				$diffDay = diff_days($rfpResponceDeadlineStamp, $current_date_stamp); // holds how many days away it is
				if ($diffDay == 1) { $dayText = 'day'; } else { $dayText = 'days'; }
				$deadlineNotice = true;
			}
			print('</div>');
	        //        $greater=$date->isGreater($rfpResponceDeadline);

	        // get responce date - 7 days and if cur date <= responcedate-move(-7)
	        $wmonth = $current_date->month() + 1;
	        $wdate = new eZDate( $current_date->year(), $current_date->month(), $current_date->day() );
	        $wstamp = $wdate->timeStamp();

	        $wdates = $wmonth .'/'. $current_date->day() .'/'. $current_date->year();
	
			//      $rfpResponceDeadline = new eZDate();
        	$ana = $rfp->responceDueDate(true);
			//      $ana->move(0,0,-7);
        	$anaa = $ana->timeStamp();


		/*
        if ( $rfpResponceDeadlineStamp >= $current_date_stamp ) {
                print("$anaa \n\n $wdates\n");
                // print("$greater!!\n");
                print("$rfpResponceDeadlineMonth/$rfpResponceDeadlineDay/$rfpResponceDeadlineYear ");
                print("R_C : $rfpResponceDeadlineStamp >= $current_date_stamp \n");
                print("C_H : $rfpResponceDeadlineStamp >= $now_date\n\n");
                $deadlineNotice = true;
        }
		*/
		//      print("Not Null : $now_date\n");

		//      print("PublishXX : $current_date_stamp \n");

		//        print("Publish : $rfp->Published\n");
		//        print("Responce Deadline : $rfpResponceDeadline\n");


        if ( $rfp->$Published == '') {
                // print("Not Null : $now_date\n");
        }

        if ( $rfp->$PublishDate == '') {
                // print("Not Null : $now_date\n");
        }


		// $rfp->publishDate()
		// actual rfp date function w/ date format
		// date time not just date on rfps!!
		
		//            $ret = new eZDateTime();
		 //           $ret->setTimeStamp( $this->PublishDate );
		//
		// logic should say, get current date for now (), then get rfp date, compare dates?
		// if year current, if month current, if day close (24/48 hours) then email rfp holder?
		// no! email the users who downloaded the rfp, using user table list of emails user::getAll()
		// then for each user from get All that matches IDS with RFP Download Listuse their email address to send out a notice to each user.
		
		//    print( "indexing rfp: " .  $rfp->name() . "- Publish Date: ". $rfp->$PublishDate ."--". " responce due date: " . $rfp->$ResponseDueDate ."\n" );
		
		// $deadlineNotice = true;
		
		// include user & mail resources to get email address & use it if it matches rfp with date paternmatch.
		//------------------------------------------------
		
		// include_once( "ezuser/classes/ezuser.php" );
		// include_once( "ezmail/classes/ezmail.php" );
		
		if ( $deadlineNotice == 'true')
		        {
			    //print("It's true<br />");
		        // get rfp id, category, author, login,
		        $rfpID = $rfp->id();
				print("His ID is: $rfpID <br>");
		        $rfpCategories = $rfp->categories(false);
		        $rfpCategoryID = $rfpCategories[0];
				$rfpAuthorList = $rfp->ContentsWriters();
				if (is_array($rfpAuthorList)) {
					foreach ($rfpAuthorList as $theHolder => $theAuthor) {
						$authorEmail = $theAuthor->email();
						$authorUserName = $theAuthor->login();
						$authorDeadlineReminder = $theAuthor->deadlineReminders();
						if($authorDeadlineReminder) { //send the email
						echo("<b>Deadline reminder is true, so we send.</b>");
				print("<div style='border:dotted 2px blue; margin: 10px; padding: 10px;'>
				Mail To: $authorEmail <br>
				Mail From: nospam@ladivaloca.org <br>
				Message Subject: Request For Proposal Deadline Reminder Email <br>
				<u>Message Body</u>
				<br>Dear $authorUserName,<br><br>Your request for proposal is drawing near it's final deadline for responces in $diffDay $dayText.<br><br>Please review:<br>http://ladivaloca.org/index.php/rfp/view/$rfpID/1/$rfpCategoryID<br>
				</div><br>");

#################### BUNCH OF MAIL STUFF GOES DOWN HERE #########################
						//      $mailTo = 'info@brookinsconsulting.com';
		        $mailTo = $authorEmail;
		//      $mailFrom = 'info@brookinsconsulting.com';
		        $mailFrom = 'nospam@ladivaloca.org';
		        $mailSubject = 'Request For Proposal Deadline Reminder Email';
		//      $UserName = 'UserName!';
		        $mailBody = "\nDear $authorUserName,\n\nYour request for proposal is drawing near it's final deadline for responces in $diffDay $dayText.\n\nPlease review:\nhttp://ladivaloca.org/index.php/rfp/view/$rfpID/1/$rfpCategoryID \n\n";
				//print("<br /><b>$mailTo</b> <br />");
				  $mail = new eZMail();
		    $mail->setFrom( $mailFrom );
		    $mail->setTo( $mailTo );
		    $mail->setSubject( $mailSubject );

//		print("\n\n $mailTo \n\n");
// exit();
		    // set and send customer email
		    $mail->setBody( $mailBody );
		    $mail->send();
		
		    $result = 'success';
############################ BUNCH OF MAIL IS ENDING  #############################
						} // ends deadline reminder if
					}
				}
		        //$rfpUser = $rfp->author(true); // why the wrong login? duplicate loops instead of this->variable useage?
				//dylan note: doesn't the above need to be an object?
				//$rfpAuthorEmail = $rfpUser->email();
		     /*   $UserName = $rfpUser->login(); // dylan is taking more stuff out
				print("His UserName is: $UserName <br>");
		        if ( $rfpUser->email() != 'null@gci.net' ) {
		                $UserNameEmail = $rfpUser->email();
				print('His email is: ' .$UserNameEmail);
		        } else {
		           $UserNameEmail = 'graham@brookinsconsulting.com';
		        }
		*/
		//include_once( "ezrfp/user/rfpdeadlinereminders.php" );
		//include( "ezrfp/user/rfpdeadlinereminders.php" );
		
		// bla, this is from the above file, cause i want to keep this to a simple call for the main function and a larger background set of code?
		
		/*
		xxx
		*/
		
		$rfpList =& eZRfp::rfps();
		
		//$rfpID = 25;
		//$rfpCategoryID = 20;
		
		// foreach() {
		// variable assignment
		//$rfpID = $rfp->id();
		//$rfpUser = $rfp->user();
		//$rfpDeadlineResponceDate = $rfp->deadlineResponce();
		// $currentDateTimeStamp = $rfpDeadlineResponceDate;
		// $currentDateTimeStamp = new eZDateTime();
		// $currentDateTimeStamp = xxxx?
		
		// if ($rfpDeadlineResponceDate) {
		// if ($rfpDeadlineResponceDate == $currentDateTimeStamp) {
		// if ($rfpDeadlineResponceDate <== $currentDateTimeStamp) {
		// print("boooooo");
		// }
		
		
		// &rfps( $sortMode="time", $fetchNonPublished=true,
		  //                      $offset=0, $limit=50 )
		
		
		
		// mail variables
		
		//    $mailTemplate->set_var( "site_url", $SiteURL );
		//    $mailBody = $mailTemplate->parse( "dummy", "full_cart_tpl" );
		
		//      $mailTo = 'info@brookinsconsulting.com';
		        $mailTo = $UserNameEmail;
		//      $mailFrom = 'info@brookinsconsulting.com';
		        $mailFrom = 'nospam@ladivaloca.org';
		        $mailSubject = 'Request For Proposal Deadline Reminder Email';
		//      $UserName = 'UserName!';
		        $mailBody = "\nDear $UserName,\n\nYour request for proposal is drawing near it's final deadline for responces in xx days.\n\nPlease review:\nhttp://ladivaloca.org/index.php/rfp/view/$rfpID/1/$rfpCategoryID \n\n";
				print("<br /><b>$mailTo</b> <br />");
				
		//{rfp_id}/1/{category_id}
		
		//    $mailBody .= "\r\n";
		
		        // questions: who to mail
		        // answere: look in db table where a date = now (test frist)
		        // then out of that list foreach lookup user and see if the user
		        // wants an email notification. for each user build an aray of eZUsers / RFPID
		        // for each send email w/ link back to ssystem.
		
		//        /*
		
		//    $mailTemplate->set_var( "site_url", $SiteURL );
		//    $mailBody = $mailTemplate->parse( "dummy", "full_cart_tpl" );
		//    $subjectINI = new INIFile( "ezarticle/user/intl/" . $Language . "/mailorder.php.ini", false );
		
		 //   $mailSubjectUser = $subjectINI->read_var( "strings", "mail_subject_user" ) . " " . $ini->read_var( "site", "SiteURL" );
		//    $mailSubject = $subjectINI->read_var( "strings", "mail_subject_admin" ) . " " . $ini->read_var( "site", "SiteURL" );
		
		//    $paymentMethod = $instance->paymentName( $order->paymentMethod() );
		//    $mailTemplate->set_var( "payment_method", $paymentMethod );
		//    $mailTemplate->set_var( "comment", $order->comment() );
		
		//    $shippingType = $order->shippingType();
		 //   if ( $shippingType )
		  //  {
		   //     $mailTemplate->set_var( "shipping_type", $shippingType->name() );
		//    }
		//    $mailTemplate->set_var( "order_vat_sum", $locale->format( $currency ) );
		
		//    $mailTemplate->set_var( "order_id", $order->id() );
		//    $mailTemplate->set_var( "credit_card_information", "" );
		
		// */
		
		// #############################################################################
		// Begin Mail
		// #############################################################################
		//Dylan is turning off the email
		    // Send E-mail
		 
		
		//
		        }
		}
// print("\n\n $mailTo \n\n");
		
		//------------------------------------------------
		// print date to log file (basic trigger log)
		// when?
		/*			
		        $ini =& INIFile::globalINI();
		        $Language = $ini->read_var( "eZRfpMain", "Language" );
		        $adminSiteURL = $ini->read_var( "site", "AdminSiteURL" );
		        $index = $ini->Index;
		        $wwwDir = $ini->WWWDir;
		        global $HTTP_HOST;
		
		        $definition = $this->categoryDefinition();
		
		        $editorGroup = $definition->editorGroup();
		
		
		        $mailTemplate = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
		                                        "ezrfp/admin/intl", $Language, "pendingmail.php" );
		
		        $mailTemplate->set_file( "pending_mail_tpl", "pendingmail.tpl" );
		        $mailTemplate->setAllStrings();
		
		
		        $mail = new eZMail();

// replace this single author code with function that returns array,
		
		        $author = $this->contentsWriter();

		// replace with user id, need admin interphase from users into rfp new / edit
		// to make sure back end inserts user ids into these content writer fields so
		// i can switch the object functions.
		
		        $authorEmail = $author->email();
		        $name = $author->name();
		
		//        $mailTemplate->set_var( "author", $name . " " . $authorEmail );
		//        $mailTemplate->set_var( "url", "http://" . $HTTP_HOST . $wwwDir . $index . "/rfp/rfppreview/" . $this->ID . "/" );
		//        $mailBody = $mailTemplate->parse( "dummy", "pending_mail_tpl" );
		        $mail->setBody( $mailBody );
		
		        $mail->setFrom( $authorEmail );
		*/ 
		// WTHWEREYOU?
				// $rfp = new eZRfp();
				// $rfp = $rfp->getAllRfp();
				/*
				foreach ( rfps as $rfp ) 
				{
				$publish_date =	$rfp->PublishedResponceDate();
				// if date compare? php logic on a sql data set that returns user objects not ids, that i can source from user->email();
		
		            $mail->setTo( $user->email() );
		            $mail->send();
		
				}
				*/
		/*		
		//        $users = $editorGroup->users();
		        foreach ( $users as $userItem )
		        {
		            $mail->setTo( $userItem->email() );
		            $mail->send();
		        }
		*/
				//######################################################
	
		/*
	
	
        // generate keywords
        $tmpContents = $this->Contents;

        $tmpContents = str_replace ("</intro>", " ", $tmpContents );
        $tmpContents = str_replace ("</page>", " ", $tmpContents );

        $contents = strtolower( strip_tags( $tmpContents ) ) . " " . $this->Name;
        $contents = str_replace ("\n", "", $contents );
        $contents = str_replace ("\r", "", $contents );
        $contents = str_replace ("(", " ", $contents );
        $contents = str_replace (")", " ", $contents );
        $contents = str_replace (",", " ", $contents );
        $contents = str_replace (".", " ", $contents );
        $contents = str_replace ("/", " ", $contents );
        $contents = str_replace ("-", " ", $contents );
        $contents = str_replace ("_", " ", $contents );
        $contents = str_replace ("\"", " ", $contents );
        $contents = str_replace ("'", " ", $contents );
        $contents = str_replace (":", " ", $contents );
        $contents = str_replace ("?", " ", $contents );
        $contents = str_replace ("!", " ", $contents );
        $contents = str_replace ("\"", " ", $contents );
        $contents = str_replace ("|", " ", $contents );
        $contents = str_replace ("qdom", " ", $contents );
        $contents = str_replace ("tech", " ", $contents );

        // strip &quot; combinations
        $contents = preg_replace("(&.+?;)", " ", $contents );

        // strip multiple whitespaces
        $contents = preg_replace("(\s+)", " ", $contents );

        $contents_array =& split( " ", $contents );
        $contents_array =& array_merge( $contents_array, $this->manualKeywords( true ) );

        $totalWordCount = count( $contents_array );
        $wordCount = array_count_values( $contents_array );

        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            if ( strlen( $word ) >= 2 )
            {
                $keywords .= $word . " ";
            }
        }

        $this->Keywords = $keywords;

        $db =& eZDB::globalDatabase();
        $ret = array();

        $ret[] = $db->query( "DELETE FROM eZRfp_RfpWordLink WHERE RfpID='$this->ID'" );

        // get total number of rfps
        $db->array_query( $rfp_array, "SELECT COUNT(*) AS Count FROM eZRfp_Rfp" );
        $rfpCount = $rfp_array[0][$db->fieldName( "Count" )];

        $db->begin( );

        foreach ( $contents_array as $word )
        {
            if ( strlen( $word ) >= 2 )
            {
                $indexWord = $word;

                $indexWord = $db->escapeString( $indexWord );


                // find the frequency
                $count = $wordCount[$indexWord];

                $freq = ( $count / $totalWordCount );

                $query = "SELECT ID FROM eZRfp_Word
                      WHERE Word='$indexWord'";

                $db->array_query( $word_array, $query );


                if ( count( $word_array ) == 1 )
                {
                    // word exists create reference
                    $wordID = $word_array[0][$db->fieldName("ID")];

                    // number of links to this word
                    $db->array_query( $rfp_array, "SELECT COUNT(*) AS Count FROM eZRfp_RfpWordLink WHERE WordID='$wordID'" );
                    $wordUsageCount = $rfp_array[0][$db->fieldName( "Count" )];

                    $wordFreq = ( $wordUsageCount + 1 )  / $rfpCount;

                    // update word frequency
                    $ret[] = $db->query( "UPDATE  eZRfp_Word SET Frequency='$wordFreq' WHERE ID='$wordID'" );


                    $ret[] = $db->query( "INSERT INTO eZRfp_RfpWordLink ( RfpID, WordID, Frequency ) VALUES
                                      ( '$this->ID',
                                        '$wordID',
                                        '$freq' )" );
                }
                else
                {
                    // lock the table
                    $db->lock( "eZRfp_Word" );

                    $wordFreq = 1 / $rfpCount;

                    // new word, create word
                    $nextID = $db->nextID( "eZRfp_Word", "ID" );
                    $ret[] = $db->query( "INSERT INTO eZRfp_Word ( ID, Word, Frequency ) VALUES
                                      ( '$nextID',
                                        '$indexWord',
                                        '$wordFreq' )" );
                    $db->unlock();

                    $ret[] = $db->query( "INSERT INTO eZRfp_RfpWordLink ( RfpID, WordID, Frequency ) VALUES
                                      ( '$this->ID',
                                        '$nextID',
                                        '$freq' )" );

                }
            }
        }
        eZDB::finish( $ret, $db );

		*/
		
    }
	// End F.U.N.ction



		
	






    /*!
      Sets the discuss value to an rfp.
    */
    function setDiscuss( $discuss )
    {
        if ( $discuss == true )
            $this->Discuss = 1;
        else
            $this->Discuss = 0;
    }

    /*!
      Sets the manual keywords to an rfp. Theese words are used in the search and for the keyword index.
    */
    function setManualKeywords( $keywords, $toLower = true )
    {
        if ( !is_array( $keywords ) )
        {
            $words = explode( ",", $keywords );
            $keywords = array();
            foreach( $words as $word )
            {
                if( $toLower )
                {
                    $keyword = strtolower( trim( $word ) );
                }
                else
                {
                    $keyword = trim( $word );
                }
                if ( $keyword != "" )
                    $keywords[] = $keyword;
            }
        }
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZRfp_RfpKeyword WHERE RfpID='$this->ID' AND Automatic='0'" );
        foreach( $keywords as $keyword )
        {
            $db->begin( );

            $keyword = $db->escapeString( $keyword );

            $db->lock( "eZRfp_RfpKeyword" );

            $nextID = $db->nextID( "eZRfp_RfpKeyword", "ID" );

            $res = $db->query( "INSERT INTO eZRfp_RfpKeyword
                         ( ID,
                         RfpID,
                         Keyword,
                         Automatic )
                         VALUES
                        ( '$nextID',
                          '$this->ID',
                          '$keyword',
                         '0' )" );


            $db->unlock();


            // Create first letter cache table
            $db->array_query( $letter_array, "SELECT SUBSTRING( Keyword from 1 for 1 ) AS Letter
                                               FROM eZRfp_RfpKeyword GROUP BY Letter" );

            $db->lock( "eZRfp_RfpKeywordFirstLetter" );

            $db->query( "DELETE FROM eZRfp_RfpKeywordFirstLetter" );

            foreach ( $letter_array as $letter )
            {
                $nextID = $db->nextID( "eZRfp_RfpKeywordFirstLetter", "ID" );

                $letter = strToLower( $letter[$db->fieldName("Letter")] );

                $res = $db->query( "INSERT INTO eZRfp_RfpKeywordFirstLetter
                         ( ID,
                         Letter )
                         VALUES
                        ( '$nextID',
                          '$letter' )" );
            }

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      \private
      \static

      Returns an array of letters which is the unique first character of all keywords.
    */
    function &keywordFirstLetters( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $letter_array, "SELECT SUBSTRING( Keyword from 1 for 1 ) AS Letter
                                          FROM eZRfp_RfpKeyword GROUP BY Letter ORDER BY Letter" );

        $retArray = array();
        foreach ( $letter_array as $letter )
        {
            $nextID = $db->nextID( "eZRfp_RfpKeywordFirstLetter", "ID" );

            $letter = strToLower( $letter[$db->fieldName("Letter")] );
            $retArray[] = $letter;
        }

        return $retArray;
    }

    /*!
      Sets the start date for the rfp.
    */
    function setPublishDate( &$date )
    {
        if ( get_class( $date ) == "ezdatetime" )
        {
            if ( !( $date->year() == "1970" && $date->month() == "1" && $date->day() == "1" ) )
            {
                $this->PublishDate = $date;

                $now = eZDateTime::timeStamp( true );
                $publishDate = $date->timeStamp();

                if ( ( $publishDate < $now ) and ( $date->year() != "1970" )  )
                {
                    $this->PublishedOverride = $publishDate;
                    $this->IsPublished = 1;
                    $this->PublishDate = 0;
                }
            }
        }
        else
            $this->PublishDate = 0;

    }

    /*!
      Sets the start date for the rfp.
    */
    function setResponseDueDate( &$date )
    {
        if ( get_class ( $date ) == "ezdatetime" )
            $this->ResponseDueDate = $date;
        else
            $this->ResponseDueDate = 0;
    }

    /*!
      Returns the manual keywords for an rfp.
      It is either returned as an array or as a comma separated string.
    */
    function &manualKeywords( $as_array = false )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query($keywords, "SELECT Keyword FROM eZRfp_RfpKeyword
                                      WHERE RfpID='$this->ID' AND Automatic='0'" );
        $ret = array();
        foreach( $keywords as $keyword )
        {
            $ret[] = $keyword[$db->fieldName("Keyword")];
        }
        if ( !$as_array )
            $ret = implode( ", ", $ret );
        return $ret;
    }

    /*!
      \static
      Returns an index of keywords found in all rfps.
      It returns an array of unique keywords.
      If $firstLetter is set only the keywords with this first letter are returned.
    */
    function &manualKeywordIndex( $firstLetter = false )
    {
        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        if ( $user )
        {
            $groups =& $user->groups( false );

            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";

                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Rfp.HolderID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' ) AND Permission.ReadPermission='1') ) AND";

        $firstLetterSQL = "";
        if ( $firstLetter != false )
        {
            $firstLetterSQL = " HAVING Letter='$firstLetter' ";
        }
        $db =& eZDB::globalDatabase();
        $db->array_query( $keywords, "SELECT ArtKey.Keyword AS Keyword, SUBSTRING( ArtKey.Keyword from 1 for 1 ) AS Letter
                  FROM eZRfp_RfpCategoryLink as Link,
                       eZRfp_RfpPermission AS Permission,
                       eZRfp_Category AS Category,
                       eZRfp_Rfp AS Rfp LEFT JOIN eZRfp_RfpKeyword AS ArtKey ON
                       Rfp.ID=ArtKey.RfpID
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        AND Rfp.IsPublished = '1'
                        AND Permission.ObjectID=Rfp.ID
                        AND Link.RfpID=Rfp.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ExcludeFromSearch = '0'
                        AND ArtKey.Keyword is not NULL
                                      GROUP BY Keyword $firstLetterSQL ORDER BY Keyword", 0, -1, "Keyword" );
        return $keywords;
    }

    /*!
      Sets the contents author.
    */
    function setContentsWriter( $authors )
    {
	if (!is_array) {
	  $this->ContentsWriters = $authors;
	} else {
	  //don't serialize
          $authors = $authors;
	  // $authors = serialize($authors);
	  $this->ContentsWriters = $authors;
	}

	/* single view
        if ( is_numeric( $author ) )
            $this->ContentsWriterID = $author;
        else
            $this->ContentsWriterID = $author->id();
	*/

	/* let's get outofthis
	foreach ( $authors as $author ) 
	{
		$theClass = get_class($author);
		$temparr = get_object_vars($author);
		v_array($temparr);
		$authID = $author->id();
		$authName = $author->Name();
	  echo("We are adding author: $authName <br>");
	  echo("Who has the id of: $authID <br>");
	  echo("And the class is: $theClass <br>");
	  $authorArray[] = $author->id();
	  // x
	}
	print("<br>ok, we have done the whole foreach loop<br>");
	viewArray($this->ContentsWriterID);
	viewArray($authorArray);
//	$authorArray = $this->ContentsWriterID;
	$this->ContentsWriterID = $authorArray;
	print("\n<br>".$this->ContentsWriterID);

exit();
	$this->ContentsWriters = $authorArray;
*/

    }

    /*!
      Returns the contentswriter of the rfp.
    */
    function contentsWriter( $returnObject = true )
    {
	//            return new eZAuthor( $this->ContentsWriterID );

        if ( $returnObject )
		return new eZPerson( $this->ContentsWriterID );
        else
            return $this->ContentsWriterID;
    }

    /*!
      Returns the contentswriter of the rfp.
    */
    function contentsWriters( $returnObject = true )
    {

        $ret = false;

//        $writersArray = explode( ",", $this->ContentsWriters );

        $writersArray = $this->ContentsWriters;

// bad form jack!
//	viewArray($this->ContentsWriters);
	//	die();
//	$writers = $this->ContentsWriters;

/*
			if(is_object($writersArray)) echo("<br>is object<br>");
			if(is_array($writersArray)) echo ("<br>is array<br>");
			if(empty($writersArray)) echo("<br>empty<br>");
*/

// if ( count($theContentsWriters) > 0 ){

if ( !empty($writersArray) ){

            foreach( $writersArray as $writer )
            {

		print($writer ."<br><br>");

		if ( $returnObject ) {
		  //			$writers = new eZPerson( $writer );
		  // $writers = new eZUser( $this->ContentsWriters );
		  $writers = $this->ContentsWriters;
			$f_writers[] = $writers;
        	}else{
            		$writers = $this->ContentsWriters;
			//			$writers = new eZPerson( $writer );
			$f_writers[] = $writers;
		}
	    }
        $ret = $f_writers;	
	return $ret;
}else {
	// return admin user if none set
	$f_writers[] = new eZPerson( 1 );
	return $f_writers;
//	return $ret;
}

    }



    /*!
      \static
      Returns an array of rfps which match short contents and the keywords.
    */
    function &searchByShortContent( $short_content, $keywords, $offset = 0, $max = -1, $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $content_sql = "";
        $keyword_sql = "";
        if ( count( $keywords ) > 0 )
        {
            foreach( $keywords as $keyword )
            {
                if ( $keyword_sql != "" )
                    $keyword_sql .= " OR ";
                $keyword_sql .= "ArtKey.Keyword='$keyword'";
            }
            if ( $content_sql != "" )
                $keyword_sql = "AND ( $keyword_sql )";
        }
        $conditions = "";
        if ( $content_sql != "" or $keyword_sql != "" )
        {
            $conditions = "AND $content_sql $keyword_sql";
        }
        $limit_sql = "";
        if ( !is_bool( $offset ) and $max > 0 )
        {
            $limit_sql = "LIMIT $offset, $max";
        }

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        if ( $user )
        {
            $groups = $user->groups( false );

            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";

                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Rfp.HolderID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' ) AND Permission.ReadPermission='1') ) AND";

        $select_sql = "";
        if ( is_bool( $offset ) )
        {
            $select_sql = "count( DISTINCT Rfp.ID ) as RfpCount";
        }
        else
        {
            $select_sql = "DISTINCT Rfp.ID as RfpID";
        }
        $sql = "SELECT $select_sql, Rfp.Name
                  FROM eZRfp_RfpCategoryLink as Link,
                       eZRfp_RfpPermission AS Permission,
                       eZRfp_Category AS Category,
                       eZRfp_Rfp AS Rfp LEFT JOIN eZRfp_RfpKeyword AS ArtKey ON
                       Rfp.ID=ArtKey.RfpID
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        AND Rfp.IsPublished = '1'
                        AND Permission.ObjectID=Rfp.ID
                        AND Link.RfpID=Rfp.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ExcludeFromSearch = '0'
                        $conditions
                 ORDER BY Rfp.Name
                 $limit_sql";
        $db->array_query( $contents, $sql );
        if ( !is_bool( $offset ) )
        {
            $ret = array();
            foreach( $contents as $content )
            {
                $ret[] = $as_object ? new eZRfp( $content[$db->fieldName("RfpID")] ) : $content[$db->fieldName("RfpID")];
            }
        }
        else
            $ret = $contents[0][$db->fieldName("RfpCount")];
        return $ret;
    }

    /*!
     Sets the rfp to published or not.
    */
    function setIsPublished( $value, $user = false, $as_script = false )
    {
        if ( get_class( $user ) != "ezuser" )
            $user =& eZUser::currentUser();

        $category = $this->categoryDefinition();
        if ( get_class( $category ) == "ezrfpcategory" )
            $editorID = $category->editorGroup( false );

        if ( is_numeric ( $editorID ) && ( $editorID > 0 ) && $as_script == false )
        {
            $group = new eZUserGroup( $editorID );
            if ( $group->isMember( $user ) or ( $user->hasRootAccess() )  )
            {
                if ( $value == 0 )
                    $this->IsPublished = "0";
                else
                    $this->IsPublished = "1";
            }
            else
            {
                if ( $value == 0 )
                    $this->IsPublished = "0";
                else
                {
                    $this->IsPublished = "2";
                    $this->sendPendingMail( );
                }
            }
        }
        else if ( $value == true )
        {
            $this->IsPublished = "1";
        }
        else
        {
            $this->IsPublished = "0";
        }
    }


    /*!
      Returns the categories an rfp is assigned to.

      The categories are returned as an array of eZRfpCategory objects.
    */
    function categories( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $ret = array();
        $db->array_query( $category_array, "SELECT * FROM eZRfp_RfpCategoryLink WHERE RfpID='$this->ID'" );

        if ( $as_object )
        {
            foreach ( $category_array as $category )
            {
                $ret[] = new eZRfpCategory( $category[$db->fieldName("CategoryID")] );
            }
        }
        else
        {
            foreach ( $category_array as $category )
            {
                $ret[] = $category[$db->fieldName("CategoryID")];
            }
        }

        return $ret;
    }

    /*!
      Removes every category assignments from the current rfp.
    */
    function removeFromCategories()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZRfp_RfpCategoryLink WHERE RfpID='$this->ID'" );
    }


    /*!
      Returns the categories an rfp is assigned to.

      The categories are returned as an array of eZRfpCategory objects.
    */
    function planholders( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $ret = array();

        $db->array_query( $category_array, "SELECT * FROM eZProcurement_ProcurementHolderDefinition WHERE ProcurementID='$this->ID'" );

	// die("SELECT * FROM eZProcurement_ProcurementHolderDefinition WHERE ProcurementID='$this->ID' ");


        if ( $as_object )
	{
	  //default : eZ User
            foreach ( $category_array as $category )
	    {
                $ret[] = new eZUser( $category[$db->fieldName("UserID")] );
	    }
	}
        else
	{
            foreach ( $category_array as $category )
	    {
                $ret[] = $category[$db->fieldName("UserID")];
	    }

	    //$this>Planholders = $ret;
	}

        return $ret;
     }


    /*!
      Removes every planholder assignments from the current procurement.
    */
    function removePlanHolders()
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZProcurement_ProcurementHolderDefinition WHERE ProcurementID='$this->ID'" );

	/*
	$db->commit();
	print( "DELETE FROM eZProcurement_ProcurementHolderDefinition WHERE ProcurementID='$this->ID'" );
        die("<br /> Got Silk?");
	*/
    }

    /*!
      Set's the planholders for the procurementy.
    */
    function setPlanholders( $value )
      {

	//        if ( get_class( $value ) == "ezuser" )
	// {


            $db =& eZDB::globalDatabase();



	    //	    die( $value[0] ." ". $value[1]." ". $value[2] );

	    /*
	    if ( is_array( $id ) )
	    {
		$this->fill( $id );
	    }
	    else if ( $id != "" )
	    {
		$this->ID = $id;
		$this->get( $this->ID );
            }
	    */

            // if ( is_array( $value ) ) {
      
            // remove all existing records
            $this->removePlanHolders();

	    foreach( $value as $single ) 
            {
              // prepare variables for insert
              $single_id = $single;
              $single = new eZUser($single);
	      $PlanHolderUserID = $single->id();
	      $PlanHolderPersonID = $single->personID();

	      /*
	      $res-del[] = $db->query( "DELETE FROM eZProcurement_ProcurementHolderDefinition
                                     WHERE ProcurementID='$this->ID'" );
              // $res-del[] = $db->query( $query );
              $db->unlock();

              if ( in_array( false, $res ) )
                $db->rollback( );
              else
                $db->commit();
	      */

	      //              $db->begin( );
	      //	      $db->lock( "eZProcurement_ProcurementHolderDefinition" );
	      $nextID = $db->nextID( "eZProcurement_ProcurementHolderDefinition", "ID" );
	      
	      $query = "INSERT INTO
                           eZProcurement_ProcurementHolderDefinition
                           ( ID, UserID, PersonID, ProcurementID )
                      VALUES
                           ( '$nextID',
                             '$PlanHolderUserID',
                             '$PlanHolderPersonID',
                             '$this->ID' )";


              print("INSERT INTO
                           eZProcurement_ProcurementHolderDefinition
                           ( ID, UserID, PersonID, ProcurementID )
                      VALUES
                           ( '$nextID',
                             '$PlanHolderUserID',
                             '$PlanHolderPersonID',
                             '$this->ID' ) <br /><br />");


	      $res[] = $db->query( $query );

	      //$db->unlock();
	      //$db->commit();

	      /*
	      if ( in_array( false, $res ) )
		$db->rollback( );
	      else
		$db->commit();
	      */

	      print( $single_id ."<br />"); 
	    } // end of foreach

	    print( $single_id ."<br />");
	    // }
        //  }
      }

    /*!
      Adds an eZUser ID to the rfp, unless the user is allready added for this rfp.
    */
    function addPlanholder($holder) {
      $dbs =& eZDB::globalDatabase();
      $db =& eZDB::globalDatabase();
      $ret = false;

      $PlanHolderUserID = $holder->id();
      $PlanHolderPersonID = $holder->personId();

      $results_array = array();
      $dbs->array_query( $results_array, "SELECT * FROM eZProcurement_ProcurementHolderDefinition WHERE ProcurementID='$this->ID' AND UserID ='$PlanHolderUserID'" );

      // print(" SELECT * FROM eZProcurement_ProcurementHolderDefinition WHERE ProcurementID='$this->ID' AND UserID ='$PlanHolderUserID'" );
      //v_array($results_array);

      if ( !count($results_array) ) {
	//	print("inside");
        $nextID = $db->nextID( "eZProcurement_ProcurementHolderDefinition", "ID" );

        $query = "INSERT INTO
                eZProcurement_ProcurementHolderDefinition
                ( ID, UserID, PersonID, ProcurementID )
                VALUES
                ( '$nextID',
                '$PlanHolderUserID',
                '$PlanHolderPersonID',
                '$this->ID' )";

        $res[] = $db->query( $query );

        $ret = true;
      }
      //      die($ret );
      return $ret;
    }

    /*!
      Removes an eZUser ID to the rfp, unless the user is not associated with this rfp.
    */
    function removePlanholder($holder) {
      $dbs =& eZDB::globalDatabase();
      $db =& eZDB::globalDatabase();
      $ret = false;

      $PlanHolderUserID = $holder->id();
      $PlanHolderPersonID = $holder->personId();

      $results_array = array();
      $dbs->array_query( $results_array, "DELETE FROM eZProcurement_ProcurementHolderDefinition WHERE ProcurementID='$this->ID' AND UserID ='$PlanHolderUserID'" );

      $ret = true;

      return $ret;
    }

    /*!
      Adds an image to the rfp, unless the image is allready added for this rfp.
    */
    function addImage( $value, $placement = false )
    {
        $db =& eZDB::globalDatabase();

        if( get_class( $value ) == "ezimage" )
            $value = $value->id();

        $db->query_single( $res, "SELECT count( * ) as Count FROM eZRfp_RfpImageLink WHERE RfpID='$this->ID' AND ImageID='$value'" );
        if( $res[$db->fieldName("Count")] == 0 )
        {
            $db->begin( );

            $db->lock( "eZRfp_RfpImageLink" );

            if ( is_bool( $placement ) )
            {
                $db->array_query( $image_array, "SELECT ID, ImageID, Placement, Created FROM eZRfp_RfpImageLink WHERE RfpID='$this->ID' ORDER BY Placement DESC" );
                if ( $image_array[0][$db->fieldName("Placement")] == "0" )
                {
                    $placement=1;
                    for ( $i=0; $i < count($image_array); $i++ )
                    {
                        $imageLinkID = $image_array[$i][$db->fieldName("ID")];
                        $db->query( "UPDATE eZRfp_RfpImageLink SET Placement='$placement' WHERE ID='$imageLinkID'" );
                        $image_array[$i][$db->fieldName("Placement")] = $placement;
                        $placement++;
                    }
                }
                $placement = $image_array[0][$db->fieldName("Placement")] + 1;
            }

            $nextID = $db->nextID( "eZRfp_RfpImageLink", "ID" );
            $timeStamp = eZDateTime::timeStamp( true );

            $res = $db->query( "INSERT INTO eZRfp_RfpImageLink
                         ( ID, RfpID, ImageID, Created, Placement )
                         VALUES
                         ( '$nextID',  '$this->ID', '$value', '$timeStamp', '$placement' )" );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Deletes an image from the rfp.

      NOTE: the image does not get deleted from the image catalogue.
    */
    function deleteImage( $value )
    {
        if ( get_class( $value ) == "ezimage" )
        {
            $imageID = $value->id();
        }
        else
            $imageID = $value;

        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZRfp_RfpImageDefinition WHERE RfpID='$this->ID' AND ThumbnailImageID='$imageID'" );

        $db->query( "DELETE FROM eZRfp_RfpImageLink WHERE RfpID='$this->ID' AND ImageID='$imageID'" );
    }

    /*!
      Returns every image to a rfp as a array of eZImage objects.
    */
    function images( $asObject = true, $OrderBy ="Created" )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $image_array = array();

        $db->array_query( $image_array, "SELECT ID, ImageID, Placement, Created FROM eZRfp_RfpImageLink WHERE RfpID='$this->ID' ORDER BY $OrderBy" );

        // convert the database if placement is not set
        if ( count( $image_array ) > 0 )
        {
            if ( $image_array[0][$db->fieldName("Placement")] == "0" )
            {
                $placement=1;
                for ( $i=0; $i < count($image_array); $i++ )
                {
                    $imageLinkID = $image_array[$i][$db->fieldName("ID")];
                    $db->query( "UPDATE eZRfp_RfpImageLink SET Placement='$placement' WHERE ID='$imageLinkID'" );

                    $image_array[$i][$db->fieldName("Placement")] = $placement;
                    $placement++;
                }
            }
        }

        for ( $i=0; $i < count($image_array); $i++ )
        {
            $return_array[$i]["Image"] = $asObject ? new eZImage( $image_array[$i][$db->fieldName("ImageID")] ) : $image_array[$i][$db->fieldName("ImageID")];
            $return_array[$i]["Placement"] = $image_array[$i][$db->fieldName("Placement")];
        }

        return $return_array;
    }



    /*!
      Moves the image placement with the given ID up.
    */
    function moveImageUp( $id )
    {
        $db =& eZDB::globalDatabase();

	$db->query_single( $qry, "SELECT * FROM eZRfp_RfpImageLink
				  WHERE RfpID='$this->ID' AND ImageID='$id'" );
      
       if ( is_numeric( $qry[$db->fieldName("ID")] ) )
       {
           $linkID = $qry[$db->fieldName("ID")];
           
           $placement = $qry[$db->fieldName("Placement")];
           
           $db->query_single( $qry, "SELECT ID, Placement FROM eZRfp_RfpImageLink
                                    WHERE Placement<'$placement' AND RfpID='$this->ID'
                                    ORDER BY Placement DESC" );

           $newPlacement = $qry[$db->fieldName("Placement")];
           $listid = $qry[$db->fieldName("ID")];

           if ( $newPlacement == $placement )
           {
               $placement += 1;
           }

           if ( is_numeric( $listid ) )
           {           
               $db->query( "UPDATE eZRfp_RfpImageLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
               $db->query( "UPDATE eZRfp_RfpImageLink SET Placement='$placement' WHERE ID='$listid'" );
           } 
       } 
    }

    /*!
      Moves the Image placement with the given ID down.
    */
    function moveImageDown( $id )
    {
       $db =& eZDB::globalDatabase();

       $db->query_single( $qry, "SELECT * FROM eZRfp_RfpImageLink
                                  WHERE RfpID='$this->ID' AND ImageID='$id'" );

       if ( is_numeric( $qry[$db->fieldName("ID")] ) )
       {
           $linkID = $qry[$db->fieldName("ID")];
           
           $placement = $qry[$db->fieldName("Placement")];
           
           $db->query_single( $qry, "SELECT ID, Placement FROM eZRfp_RfpImageLink
                                    WHERE Placement>'$placement' AND RfpID='$this->ID' ORDER BY Placement ASC" );

           $newPlacement = $qry[$db->fieldName("Placement")];
           $listid = $qry[$db->fieldName("ID")];

           if ( $newPlacement == $placement )
           {
               $newPlacement += 1;
           }           

           if ( is_numeric( $listid ) )
           {
               $db->query( "UPDATE eZRfp_RfpImageLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
               $db->query( "UPDATE eZRfp_RfpImageLink SET Placement='$placement' WHERE ID='$listid'" );
           }
       }
    }

    /*!
      Sets the thumbnail image for the rfp.

      The argument must be an eZImage object, or false to unset the thumbnail image.
    */
    function setThumbnailImage( $image )
    {
        if ( get_class( $image ) == "ezimage" )
        {
            $db =& eZDB::globalDatabase();

            $imageID = $image->id();

            $db->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZRfp_RfpImageDefinition
                                                       WHERE
                                                       RfpID='$this->ID'" );

            if ( $res_array[0][$db->fieldName("Number")] == "1" )
            {
                $db->query( "UPDATE eZRfp_RfpImageDefinition
                                         SET
                                         ThumbnailImageID='$imageID'
                                         WHERE
                                         RfpID='$this->ID'" );
            }
            else
            {
                $db->begin( );


                $res = $db->query( "INSERT INTO eZRfp_RfpImageDefinition
                                         ( RfpID, ThumbnailImageID )
                                         VALUES
                                         ( '$this->ID', '$imageID' )" );
                $db->unlock();

                if ( $res == false )
                    $db->rollback( );
                else
                    $db->commit();

            }
        }
        else if ( $image == false )
        {
            $db =& eZDB::globalDatabase();

            $db->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZRfp_RfpImageDefinition
                                                       WHERE
                                                       RfpID='$this->ID'" );

            if ( $res_array[0][$db->fieldName("Number")] == "1" )
            {
                $db->query( "DELETE FROM eZRfp_RfpImageDefinition
                                         WHERE
                                         RfpID='$this->ID'" );
            }
        }
    }

    /*!
      Returns the thumbnail image of the rfp as a eZImage object.
    */
    function thumbnailImage( $as_object = true )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();

        $db->array_query( $res_array, "SELECT * FROM eZRfp_RfpImageDefinition
                                     WHERE
                                     RfpID='$this->ID'
                                   " );

        if ( count( $res_array ) == 1 )
        {
            $id = $res_array[0][$db->fieldName("ThumbnailImageID")];
            if ( $id != "NULL" )
            {
                $ret = $as_object ? new eZImage( $id ) : $id;
            }
        }

        return $ret;
    }

    /*!
      Adds an media to the rfp, unless the media is allready added for this rfp.
    */
    function addMedia( $value )
    {
        $db =& eZDB::globalDatabase();

        if( get_class( $value ) == "ezmedia" )
            $value = $value->id();

        $db->query_single( $res, "SELECT count( * ) as Count FROM eZRfp_RfpMediaLink WHERE RfpID='$this->ID' AND MediaID='$value'" );
        if( $res[$db->fieldName("Count")] == 0 )
        {
            $db->begin( );

            $db->lock( "eZRfp_RfpMediaLink" );

            $nextID = $db->nextID( "eZRfp_RfpMediaLink", "ID" );
            $timeStamp = eZDateTime::timeStamp( true );

            $res = $db->query( "INSERT INTO eZRfp_RfpMediaLink
                         ( ID, RfpID, MediaID, Created )
                         VALUES
                         ( '$nextID',  '$this->ID', '$value', '$timeStamp' )" );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Deletes an media from the rfp.

      NOTE: the media does not get deleted from the media catalogue.
    */
    function deleteMedia( $value )
    {
        if ( get_class( $value ) == "ezmedia" )
        {
            $mediaID = $value->id();
        }
        else
            $mediaID = $value;

        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZRfp_RfpMediaDefinition WHERE RfpID='$this->ID' AND ThumbnailMediaID='$mediaID'" );

        $db->query( "DELETE FROM eZRfp_RfpMediaLink WHERE RfpID='$this->ID' AND MediaID='$mediaID'" );
    }

    /*!
      Returns every media to a rfp as a array of eZMedia objects.
    */
    function media( $asObject = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $media_array = array();

        $db->array_query( $media_array, "SELECT MediaID, Created FROM eZRfp_RfpMediaLink WHERE RfpID='$this->ID' ORDER BY Created" );

        for ( $i=0; $i < count($media_array); $i++ )
        {
            $return_array[$i] = $asObject ? new eZMedia( $media_array[$i][$db->fieldName("MediaID")] ) : $media_array[$i][$db->fieldName("MediaID")];
        }

        return $return_array;
    }


    /*!
      Adds an file to the rfp.
      $value can either be a eZVirtualFile or an ID
    */
    function addFile( $value )
    {
        if ( get_class( $value ) == "ezvirtualfile" )
        {
            $fileID = $value->id();
        }
        else
            $fileID = $value;

        $db =& eZDB::globalDatabase();

        $db->begin( );

        $db->lock( "eZRfp_RfpFileLink" );

        $nextID = $db->nextID( "eZRfp_RfpFileLink", "ID" );

        $timeStamp = eZDateTime::timeStamp( true );

        $res = $db->query( "INSERT INTO eZRfp_RfpFileLink
                         ( ID, RfpID, FileID, Created ) VALUES ( '$nextID', '$this->ID', '$fileID', '$timeStamp' )" );

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Deletes an file from the rfp.
      $value can either be a eZVirtualFile or an ID

      NOTE: the file does not get deleted from the file catalogue.
    */
    function deleteFile( $value )
    {
        if ( get_class( $value ) == "ezvirtualfile" )
        {
            $fileID = $value->id();

        }
        else
            $fileID = $value;

        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZRfp_RfpFileLink WHERE RfpID='$this->ID' AND FileID='$fileID'" );
    }

    /*!
      Returns every file to a rfp as a array of eZFile objects.
    */
    function files( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $file_array = array();

        $db->array_query( $file_array, "SELECT FileID, Created FROM eZRfp_RfpFileLink WHERE RfpID='$this->ID' ORDER BY Created" );

        for ( $i=0; $i < count($file_array); $i++ )
        {
            $id = $file_array[$i][$db->fieldName("FileID")];
            $return_array[$i] = $as_object ? new eZVirtualFile( $id, false ) : $id;
        }

        return $return_array;
    }

    /*!
      Returns every bid to a rfp as a array of eZRfpBid objects.
    */
    function bids( $as_object = true)
    {

        // use eZProcurement:Bids Instead
        $bid = new eZProcurementBid();
        // $bids = $bid->getAll();
	$bids = $bid->getAllByProcurement($this->ID, false); // $as_object);

	return $bids;
      }


    /*!
      Deletes an attribute from an rfp.
    */
    function deleteAttribute( $value )
    {
        if ( get_class( $value ) == "ezrfpattribute" )
        {
            $db =& eZDB::globalDatabase();

            $attributeID = $value->id();

            $db->query( "DELETE FROM eZRfp_AttributeValue WHERE RfpID='$this->ID' AND AttributeID='$attributeID'" );
        }
    }

    /*!
      Returns every attribute belonging to an rfp as an array of eZRfpAttribute objects.
    */
    function attributes( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $attribute_array = array();

        $db->array_query( $attribute_array, "SELECT Value.AttributeID, Attr.Placement FROM eZRfp_AttributeValue as Value, eZRfp_Attribute as Attr
                                             WHERE Attr.ID = Value.AttributeID AND Value.RfpID='$this->ID' ORDER BY Attr.TypeID, Attr.Placement" );

        for ( $i=0; $i < count( $attribute_array ); $i++ )
        {
            $id = $attribute_array[$i][$db->fieldName("AttributeID")];
            $return_array[$i] = $as_object ? new eZRfpAttribute( $id, false ) : $id;
        }

        return $return_array;
    }


    /*!
      Deletes all attributes defined for this rfp which belongs to a certain type.
    */
    function deleteAttributesByType( $type )
    {
        $ret = false;

        if ( get_class( $type ) == "ezrfptype" )
        {
            $db =& eZDB::globalDatabase();

            $typeID = $type->id();

            $return_array = array();
            $attribute_array = array();

            $db->array_query( $attribute_array, "SELECT Value.ID FROM eZRfp_AttributeValue AS Value, eZRfp_Attribute AS Attr
                                                 WHERE Value.RfpID='$this->ID' AND Value.AttributeID=Attr.ID AND Attr.TypeID='$typeID'" );

            for ( $i=0; $i < count( $attribute_array ); $i++ )
            {
               $valueID =  $attribute_array[$i][$db->fieldName("ID")];
               $db->query( "DELETE FROM eZRfp_AttributeValue WHERE ID='$valueID'" );
            }

            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns every attribute type belonging to an rfp as an array of eZRfpType objects.
    */
    function types( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $type_array = array();

        $db->array_query( $type_array, "SELECT Attr.TypeID AS TypeID FROM eZRfp_Attribute AS Attr, eZRfp_AttributeValue AS Value WHERE Value.RfpID='$this->ID' AND Attr.ID = Value.AttributeID GROUP BY Attr.TypeID" );

        for ( $i=0; $i < count( $type_array ); $i++ )
        {
            $id = $type_array[$i][$db->fieldName("TypeID")];
            $return_array[$i] = $as_object ? new eZRfpType( $id, false ) : $id;
        }

        return $return_array;
    }

     /*!
      Returns true if the type given exists for this rfp.
    */
    function hasType( $type )
    {
        $ret = false;
        if ( get_class( $value ) == "ezrfptype" )
        {
            $typeID = $value->id();

            $db =& eZDB::globalDatabase();

            $return_array = array();
            $type_array = array();

            $db->array_query( $type_array, "SELECT Attr.TypeID AS TypeID FROM eZRfp_Attribute AS Attr, eZRfp_AttributeValue AS Value WHERE Value.RfpID='$this->ID' AND Attr.ID = Value.AttributeID AND Attr.TypeID='$typeID'" );

            if( count( $type_array ) > 0 )
            {
                $ret = true;
            }
        }
        return $ret;
    }

   /*!
      Returns true if the rfp is assigned to the category given
      as argument. False if not.
     */
    function existsInCategory( $category )
    {
        $ret = false;
        if ( get_class( $category ) == "ezrfpcategory" )
        {
            $db =& eZDB::globalDatabase();
            $catID = $category->id();

            $db->array_query( $ret_array, "SELECT ID FROM eZRfp_RfpCategoryLink
                                    WHERE RfpID='$this->ID' AND CategoryID='$catID'" );

            if ( count( $ret_array ) == 1 )
            {
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Does a search in the rfp archive.
      queryText is the text to search for
      sortMode is the way the result is sorted.
      fetchnonPublished can be either true or false.
      offset, limit are self explanatory.

      params is an associative array that can contain the following items
      FromDate an eZDate object.
      ToDate an eZDate object.
      Categories an array of Category ID's
      Type
      HolderID the ID of the author writing the rfp
      PhotographerID a photographer that has contributed to the rfp

      if SearchExcludedRfps is set to "true" rfps which is set non searchable will also be searched.
      $SearchTotalCount will return the total number of items found in the search
    */
    function &search( &$queryText, $sortMode=time, $fetchPublished=false, $offset=0, $limit=10, $params = array(), &$SearchTotalCount )
    {
        $db =& eZDB::globalDatabase();
	$queryTextEmpty = false;

        $queryText = $db->escapeString( $queryText );
	

	if ( $queryText == '' ){
	  $queryTextEmpty = true;
	}

        switch( $sortMode )
        {
            case "alpha" :
            {
                $OrderBy = "eZRfp_Rfp.Name DESC";
            }
            break;
        }

        if ( $fetchPublished == true )
        {
            $fetchText = "";
        }
        else
        {
            $fetchText = "AND eZRfp_Rfp.IsPublished = '1'";
        }

        $usePermission = true;

        $user =& eZUser::currentUser();

        // Build the permission
        $loggedInSQL = "";
        $groupSQL = "";
        if ( $user )
        {
            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( Permission.GroupID='$group' AND CategoryPermission.GroupID='$group' ) OR
                              ( Permission.GroupID='$group' AND CategoryPermission.GroupID='-1' ) OR
                              ( Permission.GroupID='-1' AND CategoryPermission.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $loggedInSQL = "eZRfp_Rfp.HolderID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }

        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1' ) ) AND";

        if ( $usePermission )
            $permissionSQL = $loggedInSQL;
       else
           $permissionSQL = "";

        // stop word frequency
        $ini =& INIFile::globalINI();
        $StopWordFrequency = $ini->read_var( "eZRfpMain", "StopWordFrequency" );

//	if ( $queryTextEmpty == false ){

        // Build the ORDER BY
        $OrderBy = "eZRfp_Word.Frequency DESC";

        $query = new eZQuery( "eZRfp_Word.Word", $queryText );
        $query->setIsLiteral( true );
        $query->setStopWordColumn(  "eZRfp_Word.Frequency" );
        $query->setStopWordPercent( $StopWordFrequency );
        $searchSQL = $query->buildQuery();
/*  
      }else {
	 // Build the ORDER BY
         $OrderBy = "eZRfp_Category.ID DESC";

         $query = new eZQuery( "eZRfp_Category.ID", $queryText );
         $query->setIsLiteral( true );
//         $query->setStopWordColumn(  "eZRfp_Category.ID" );
         // $query->setStopWordPercent( $StopWordFrequency );
         $searchSQL = $query->buildQuery();
	}
*/
        $dateSQL = "";
        $catSQL = "";
        $typeTables = "";
        $typeSQL = "";
        $sectionsSQL = "";

        if ( isSet( $params["FromDate"] ) )
        {
            $fromdate = $params["FromDate"];
            if( get_class( $fromdate ) == "ezdatetime" )
                $fromdate = $fromdate->timeStamp();
            $dateSQL .= "AND eZRfp_Rfp.Published >= '$fromdate'";
        }
        if ( isSet( $params["ToDate"] ) )
        {
            $todate = $params["ToDate"];
            if( get_class( $todate ) == "ezdatetime" )
                $todate = $todate->timeStamp();
            $dateSQL .= "AND eZRfp_Rfp.Published <= '$todate'";
        }
        if ( isSet( $params["Categories"] ) )
        {
            $cats = $params["Categories"];
            $sql = "";
            $i = 0;
            foreach( $cats as $cat )
            {
                if ( $i > 0 )
                    $sql .= "OR ";
                $sql .= "Category.ID = '$cat' ";
                ++$i;
            }
            if ( count( $cats ) > 0 )
            {
                $catSQL = "AND ( $sql ) AND Category.ID=Link.CategoryID
                            AND eZRfp_Rfp.ID=Link.RfpID";
            }
        }
        if ( isSet( $params["Type"] ) )
        {
            $type = $params["Type"];
            $typeSQL = "AND eZRfp_Attribute.TypeID='$type'
                        AND eZRfp_Attribute.ID=eZRfp_AttributeValue.AttributeID
                        AND eZRfp_AttributeValue.RfpID=eZRfp_Rfp.ID";
            $typeTables = "eZRfp_Attribute, eZRfp_AttributeValue, ";
        }

//        if ( isSet( $params["HolderID"] ) )

	if ( isSet( $params["ContentsWriterID"] ) )
        {

//            $author = $params["HolderID"];
//            $authorSQL = "AND eZRfp_Rfp.ContentsWriterID='$author'";

            $author = $params["ContentsWriterID"];
	    $author_user = new eZPerson($author);
	    $author_user_id = $author_user->ID;
	    $author = serialize( $author_user_id );

	print( $author . '______________________<br />');

	    $authorSQL = "AND eZRfp_Rfp.ContentsWriters ='$author'";

//            $authorSQL = "AND eZRfp_Rfp.ContentsWriterID='$author'";

        }
	if ( isSet( $params["SectionsList"] ) )
	{
	    $sectionsList = $params["SectionsList"];
	    $sectionsArray = explode( ",", $sectionsList );
	    if ( is_numeric( $sectionsArray[0] ) )
	    {
		$sectionsSQL .= "AND ( Category.SectionID='$sectionsArray[0]'";
		for ( $i=1; $i<count( $sectionsArray ); $i++ )
	        {
		     $sectionsSQL .= " OR Category.SectionID='$sectionsArray[$i]'";
		}
		$sectionsSQL .= " ) ";
            }
        }
        if ( isSet( $params["PhotographerID"] ) )
        {
            $photo = $params["PhotographerID"];
            $photoSQL = "AND eZImageCatalogue_Image.PhotographerID='$photo'
                         AND eZImageCatalogue_Image.ID=eZRfp_RfpImageLink.ImageID
                         AND eZRfp_Rfp.ID=eZRfp_RfpImageLink.RfpID";
            $photoTables = "eZRfp_RfpImageLink, eZImageCatalogue_Image,";
        }


        if ( $params["SearchExcludedRfps"] == "true" )
            $excludeFromSearchSQL = " ";
        else
            $excludeFromSearchSQL = " AND Category.ExcludeFromSearch = '0' ";

        // special search for MySQL, mimic subselects ;)
        if ( $db->isA() == "mysql" )
        {
            $queryArray = explode( " ", trim( $queryText ) );

            $db->query( "CREATE TEMPORARY TABLE eZRfp_SearchTemp( RfpID int )" );

            $count = 1;
            foreach ( $queryArray as $queryWord )
            {
                $queryWord = trim( $queryWord );

                $searchSQL = " ( eZRfp_Word.Word = '$queryWord' AND eZRfp_Word.Frequency < '$StopWordFrequency' ) ";

                $queryString = "INSERT INTO eZRfp_SearchTemp ( RfpID ) SELECT DISTINCT eZRfp_Rfp.ID AS RfpID
                 FROM eZRfp_Rfp,
                      eZRfp_RfpWordLink,
                      eZRfp_Word,
                      eZRfp_RfpCategoryLink as Link,
                      $typeTables
                      $photoTables
                      eZRfp_RfpCategoryDefinition as Definition,
                      eZRfp_RfpPermission as Permission,
                      eZRfp_Category AS Category,
                      eZRfp_CategoryPermission as CategoryPermission

                 WHERE
                       $permissionSQL
                       $searchSQL
                       $dateSQL
                       $catSQL
                       $typeSQL
                       $authorSQL
                       $photoSQL
		       $sectionsSQL
                       AND
                       ( eZRfp_Rfp.ID=eZRfp_RfpWordLink.RfpID
                         AND Definition.RfpID=eZRfp_Rfp.ID
                         AND Definition.CategoryID=Category.ID
                         $excludeFromSearchSQL
                         
			 AND eZRfp_RfpWordLink.WordID=eZRfp_Word.ID

                         AND Permission.ObjectID=eZRfp_Rfp.ID
                         AND CategoryPermission.ObjectID=Definition.CategoryID
                         $fetchText

                         AND Link.RfpID=eZRfp_Rfp.ID
                        )
                       ORDER BY $OrderBy";

                $db->query( $queryString );
		print($queryString ); 

                // check if this is a stop word
                $queryString = "SELECT Frequency FROM eZRfp_Word WHERE Word='$queryWord'";

                $db->query_single( $WordFreq, $queryString, array( "LIMIT" => 1 ) );

                if ( $WordFreq["Frequency"] <= $StopWordFrequency )
                    $count += 1;
            }
            $count -= 1;

            $queryString = "SELECT RfpID, Count(*) AS Count FROM eZRfp_SearchTemp GROUP BY RfpID HAVING Count>='$count'";

            $db->array_query( $rfp_array, $queryString );

//            $db->array_query( $rfp_array, $queryString, array( "Limit" => $limit, "Offset" => $offset ) );

            $db->query( "DROP  TABLE eZRfp_SearchTemp" );

            $SearchTotalCount = count( $rfp_array );
            if ( $limit >= 0 )
                $rfp_array =& array_slice( $rfp_array, $offset, $limit );
        }
        else
        {
            $queryString = "SELECT DISTINCT eZRfp_Rfp.ID AS RfpID, eZRfp_Rfp.Published, eZRfp_Rfp.Name, eZRfp_RfpWordLink.Frequency
                 FROM eZRfp_Rfp,
                      eZRfp_RfpWordLink,
                      eZRfp_Word,
                      eZRfp_RfpCategoryLink,
                      $catDefTable
                      $catTable
                      $typeTables
                      $photoTables
                      eZRfp_RfpPermission
                 WHERE
                       $searchSQL
                       $dateSQL
                       $catSQL
                       $typeSQL
                       $authorSQL
                       $photoSQL
                       AND
                       ( eZRfp_Rfp.ID=eZRfp_RfpWordLink.RfpID
                         AND eZRfp_RfpCategoryDefinition.RfpID=eZRfp_Rfp.ID
                         AND eZRfp_RfpCategoryDefinition.CategoryID=eZRfp_Category.ID
                         $excludeFromSearchSQL
                         AND eZRfp_RfpWordLink.WordID=eZRfp_Word.ID
                         AND eZRfp_RfpPermission.ObjectID=eZRfp_Rfp.ID
                         $fetchText
                         AND eZRfp_RfpCategoryLink.RfpID=eZRfp_Rfp.ID AND
                          ( $loggedInSQL ($groupSQL eZRfp_RfpPermission.GroupID='-1')
                            AND eZRfp_RfpPermission.ReadPermission='1'
                          )
                        )
                       ORDER BY $OrderBy";

            $db->array_query( $rfp_array, $queryString );

            $SearchTotalCount = count( $rfp_array );
            $rfp_array =& array_slice( $rfp_array, $offset, $limit );
        }

        for ( $i = 0; $i < count($rfp_array); $i++ )
        {
            $return_array[$i] = new eZRfp( $rfp_array[$i][$db->fieldName( "RfpID" )], false );
        }

        return $return_array;
    }

    /*!
      Does a search for titles in the rfp archive.
      queryText is the text to search for
      fetchnonPublished can be either true or false.

      $SearchTotalCount will return the total number of items found in the search
    */
    function &searchTitle( &$queryText, $fetchPublished=false, &$SearchTotalCount, $categoryID = false )
    {
        $db =& eZDB::globalDatabase();

        $queryText = $db->escapeString( $queryText );

        // Build the ORDER BY
        $OrderBy = "eZRfp_Rfp.Published DESC";

        if ( $fetchPublished == true )
            $fetchText = "";
        else
            $fetchText = "AND eZRfp_Rfp.IsPublished = '1'";

        if ( $categoryID )
            $categoryText = "AND eZRfp_RfpCategoryLink.CategoryID = '$categoryID'";
        else
            $categoryText = "";

        $usePermission = true;

        $user =& eZUser::currentUser();

        $query = "SELECT eZRfp_Rfp.ID AS RfpID FROM eZRfp_Rfp, eZRfp_RfpCategoryLink WHERE
                  eZRfp_Rfp.Name LIKE '%$queryText%' AND
                  eZRfp_Rfp.ID = eZRfp_RfpCategoryLink.RfpID
                  $fetchText
                  $categoryText GROUP BY eZRfp_Rfp.ID ORDER BY eZRfp_Rfp.Published";

        $db->array_query( $rfp_array, $query );
        $SearchTotalCount = count( $rfp_array );

        for ( $i = 0; $i < count( $rfp_array ); $i++ )
        {
            $return_array[$i] = new eZRfp( $rfp_array[$i][$db->fieldName( "RfpID" )], false );
        }

        return $return_array;
    }

    /*!
      Returns the number of rfps available, for the current user.
    */
   // function rfpCount( $fetchNonPublished=true, $excludeFromSearch=false )
    function rfpCount( $fetchNonPublished=true, $excludeFromSearch=false )
    {
        $db =& eZDB::globalDatabase();

        $OrderBy = "Rfp.Published DESC";
        //switch( $sortMode )
        //{
        //    case "alpha" :
        //    {
        //        $OrderBy = "Rfp.Name DESC";
        //    }
        //    break;
        //}

        $ini =& INIFile::globalINI();
        $ExcludeCategories = "";
        if ( $ini->has_var( "eZRfpMain", "ExcludeCategories" ) )
        {
            $ExcludeCategories = $ini->read_var( "eZRfpMain", "ExcludeCategories" );
        }


        $return_array = array();
        $rfp_array = array();

        $usePermission = true;

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        if ( $user )
        {
            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( Permission.GroupID='$group' AND CategoryPermission.GroupID='$group' ) OR
                              ( Permission.GroupID='$group' AND CategoryPermission.GroupID='-1' ) OR
                              ( Permission.GroupID='-1' AND CategoryPermission.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Rfp.HolderID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1' ) ) ";

        if ( $usePermission )
            $permissionSQL = $loggedInSQL;
        else
            $permissionSQL = "";

       // fetch only published rfps
       if ( $fetchNonPublished  == true )
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Rfp.IsPublished = '0' AND ";
           else
               $publishedSQL = " AND Rfp.IsPublished = '0' AND ";
       }
       // fetch only non-published rfps
       else
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Rfp.IsPublished = '1' AND ";
           else
               $publishedSQL = " AND Rfp.IsPublished = '1' AND ";
       }

       // fetch only published rfps
       if ( $fetchNonPublished  == "pending" )
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Rfp.IsPublished = '2' AND ";
           else
               $publishedSQL = " AND Rfp.IsPublished = '2' AND ";
       }

       if ( $excludeFromSearch )
           $excludeSQL = " AND Category.ExcludeFromSearch = '0'";
       else
           $excludeSQL = "";



       if ($ExcludeCategories && $ExcludeCategories<>"") $excludeSQL .= " AND Category.ID NOT IN (".$ExcludeCategories.")";


        $query = "SELECT COUNT( DISTINCT Rfp.ID ) as Count
                  FROM eZRfp_RfpCategoryDefinition as Definition,
                       eZRfp_Rfp AS Rfp,
                       eZRfp_RfpCategoryLink as Link,
                       eZRfp_RfpPermission AS Permission,
                       eZRfp_CategoryPermission as CategoryPermission,
                       eZRfp_Category AS Category
                  WHERE $permissionSQL
                        $publishedSQL
                        Permission.ObjectID=Rfp.ID
                        AND Link.RfpID=Rfp.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ID=Link.CategoryID
                        AND Definition.RfpID=Rfp.ID
                        AND CategoryPermission.ObjectID=Definition.CategoryID
                        $excludeSQL ";


        $db->array_query( $rfp_array, $query  );

        return  $rfp_array[0][$db->fieldName("Count")];
    }

    /*!
      Returns every rfp in every category sorted by time.
    */
    function &rfps( $sortMode="time", $fetchNonPublished=true,
                        $offset=0, $limit=50 )
    {
        $db =& eZDB::globalDatabase();

        $OrderBy = "Rfp.Published DESC";
        $GroupBy = "Rfp.Published";
        switch( $sortMode )
        {
            case "alpha" :
            {
                $GroupBy = "Rfp.Name";
                $OrderBy = "Rfp.Name DESC";
            }
            break;
        }

        $ini =& INIFile::globalINI();
        $ExcludeCategories = "";
        if ( $ini->has_var( "eZRfpMain", "ExcludeCategories" ) )
        {
            $ExcludeCategories = $ini->read_var( "eZRfpMain", "ExcludeCategories" );
        }


        $return_array = array();
        $rfp_array = array();

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        $usePermission = true;
        if ( $user )
        {
            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( Permission.GroupID='$group' AND CategoryPermission.GroupID='$group' ) OR
                              ( Permission.GroupID='$group' AND CategoryPermission.GroupID='-1' ) OR
                              ( Permission.GroupID='-1' AND CategoryPermission.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Rfp.HolderID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1' ) ) ";

        if ( $usePermission )
            $permissionSQL = $loggedInSQL;
       else
           $permissionSQL = "";

       $excludeSQL = " AND Category.ExcludeFromSearch = '0'";

       // fetch only published rfps
       if ( $fetchNonPublished  == true )
       {
           $excludeSQL = "";
           if ( $permissionSQL == "" )
               $publishedSQL = " Rfp.IsPublished = '0' AND ";
           else
               $publishedSQL = " AND Rfp.IsPublished = '0' AND ";
       }
       // fetch only non-published rfps
       else
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Rfp.IsPublished = '1' AND ";
           else
               $publishedSQL = " AND Rfp.IsPublished = '1' AND ";
       }

       // fetch only published rfps
       if ( $fetchNonPublished  == "pending" )
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Rfp.IsPublished = '2' AND ";
           else
               $publishedSQL = " AND Rfp.IsPublished = '2' AND ";
       }


        if ($ExcludeCategories && $ExcludeCategories<>"") $excludeSQL .= " AND Category.ID NOT IN (".$ExcludeCategories.")";

        $query = "SELECT Rfp.ID as RfpID
                  FROM eZRfp_RfpCategoryDefinition as Definition,
                       eZRfp_Rfp AS Rfp,
                       eZRfp_RfpCategoryLink as Link,
                       eZRfp_RfpPermission AS Permission,
                       eZRfp_CategoryPermission as CategoryPermission,
                       eZRfp_Category AS Category
                  WHERE $permissionSQL
                        $publishedSQL
                        Permission.ObjectID=Rfp.ID
                        AND Link.RfpID=Rfp.ID
                        AND Category.ID=Link.CategoryID
                        $excludeSQL
                        AND Definition.RfpID=Rfp.ID
                        AND CategoryPermission.ObjectID=Definition.CategoryID
                 GROUP BY Rfp.ID, Rfp.IsPublished, $GroupBy ORDER BY $OrderBy";


        $db->array_query( $rfp_array, $query, array( "Limit" => $limit, "Offset" => $offset )  );

        for ( $i=0; $i < count($rfp_array); $i++ )
        {
            $return_array[$i] = new eZRfp( $rfp_array[$i][$db->fieldName("RfpID")], false );
        }

        return $return_array;
    }

    /*!
      Set's the rfps defined category. This is the main category for the rfp.
      Additional categories can be added with eZRfpCategory::addRfp();
    */
    function setCategoryDefinition( $value )
    {
        if ( get_class( $value ) == "ezrfpcategory" )
        {
            $db =& eZDB::globalDatabase();

            $categoryID = $value->id();

            $db->begin( );

            $res[] = $db->query( "DELETE FROM eZRfp_RfpCategoryDefinition
                                     WHERE RfpID='$this->ID'" );


            $db->lock( "eZRfp_RfpCategoryDefinition" );
            $nextID = $db->nextID( "eZRfp_RfpCategoryDefinition", "ID" );

            $query = "INSERT INTO
                           eZRfp_RfpCategoryDefinition
                           ( ID, CategoryID, RfpID )
                      VALUES
                           ( '$nextID',
                             '$categoryID',
                             '$this->ID' )";


            $res[] = $db->query( $query );


            $db->unlock();

            if ( in_array( false, $res ) )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Returns the rfp's definition category.
    */
    function categoryDefinition( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT CategoryID FROM
                                            eZRfp_RfpCategoryDefinition
                                            WHERE RfpID='$this->ID'" );

        $category = false;
        if ( count( $res ) == 1 )
        {
            $id = $res[0][$db->fieldName("CategoryID")];
            $category = $as_object ? new eZRfpCategory( $id ) : $id;
        }
        else
        {
            print( "<br><b>Failed to get rfp category definition for ID $this->ID</b></br>" );
        }

        return $category;
    }

    /*!
      \static
      Returns the rfp definition id to the corresponding
      rfp id.

      false is returned if no rfp was found.
    */
    function categoryDefinitionStatic( $id )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT CategoryID FROM
                                            eZRfp_RfpCategoryDefinition
                                            WHERE RfpID='$id'" );

        if ( count( $res ) == 1 )
            return $res[0][$db->fieldName("CategoryID")];
        else
            return false;
    }

    /*!
      Returns the ID of a category
    */
    function GetCategory( $rfpSectionID = 0 )
    {

        $db =& eZDB::globalDatabase();

        $categoryQueryWithSection="SELECT DISTINCT eZRfp_Category.ID AS CategoryID
            FROM
            eZRfp_Rfp, eZRfp_Category, eZRfp_RfpCategoryLink
	    WHERE
	    eZRfp_Category.ID=eZRfp_RfpCategoryLink.CategoryID
	    AND eZRfp_Rfp.ID=eZRfp_RfpCategoryLink.RfpID
	    AND eZRfp_Rfp.ID=$this->ID
	    AND eZRfp_Category.SectionID='$rfpSectionID'";

	$categoryQueryWithoutSection="SELECT DISTINCT eZRfp_RfpCategoryDefinition.CategoryID AS CategoryID
            FROM
            eZRfp_Rfp, eZRfp_RfpCategoryDefinition
	    WHERE
	    eZRfp_Rfp.ID=eZRfp_RfpCategoryDefinition.RfpID
	    AND eZRfp_Rfp.ID=$this->ID";


	if ( $rfpSectionID > 0 )
	{
	    $db->array_query( $category_array, $categoryQueryWithSection );
	    if ( count( $category_array ) == 0 )
	    {
	        $db->array_query( $category_array, $categoryQueryWithoutSection );
	    }
	}
	else
	{
	    $db->array_query( $category_array, $categoryQueryWithoutSection );
	}

	return $category_array[0][$db->fieldName("CategoryID")];
    }


    /*!
      Creates a discussion forum for the rfp.
    */
    function createForum()
    {

    }

    /*!
      Returns the forum for the rfp.
    */
    function forum( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT ForumID FROM
                                            eZRfp_RfpForumLink
                                            WHERE RfpID='$this->ID'" );
        $forum = false;
        if ( count( $res ) == 1 )
        {
            if ( $as_object )
                $forum = new eZForum( $res[0][$db->fieldName( "ForumID" )] );
            else
                $forum = $res[0][$db->fieldName( "ForumID" )];
        }
        else
        {
            $forum = new eZForum();
            $forum->setName( $db->escapeString( $this->Name ) );
            $forum->store();

            $forumID = $forum->id();

            $db->begin( );

            $db->lock( "eZRfp_RfpForumLink" );

            $nextID = $db->nextID( "eZRfp_RfpForumLink", "ID" );

            $res = $db->query( "INSERT INTO eZRfp_RfpForumLink
                                ( ID, RfpID, ForumID )
                                VALUES
                                ( '$nextID', '$this->ID', '$forumID' )" );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();


            if ( $as_object )
                $forum = new eZForum( $forumID );
            else
                $forum = $forumID;
        }
        return $forum;
    }


    /*!
      \Static
      Returns true if the given user is the author of the given object.
      $user is either a userID or an eZUser.
      $rfp is the rfpID
     */
    function isAuthor( $user, $rfpID )
    {
        if( get_class( $user ) != "ezuser" )
            return false;

        $db =& eZDB::globalDatabase();

//        $db->query_single( $res, "SELECT HolderID from eZRfp_Rfp WHERE ID='$rfpID'");
//        $authorID = $res[$db->fieldName("HolderID")];


        $db->query_single( $res, "SELECT ContentsWriters from eZRfp_Rfp WHERE ID='$rfpID'");
        $authorID = $res[$db->fieldName("ContentsWriters")];
	$authorIDs = unserialize($authorID);
// insert foreach loop over authorIDs to find and match id then kick to return

        if(  $authorID == $user->id() )
            return true;

        return false;
    }

    /*!
      Returns the rfp which a forum is connected to.
     */
    function rfpIDFromForum( $ForumID )
    {
        $db =& eZDB::globalDatabase();

        $RfpID = 0;

        $db->array_query( $result, "SELECT RfpID FROM
                                    eZRfp_RfpForumLink
                                    WHERE ForumID='$ForumID' GROUP BY RfpID" );

        if( count( $result ) > 0 )
        {
            $RfpID = $result[0][$db->fieldName("RfpID")];
        }

        return $RfpID;
    }

    /*!
      Returns a list of authors and their rfp count.
    */
    function authorList( $offset = 0, $limit = -1, $sort = false )
    {
        if ( is_string( $sort ) )
        {
            switch( $sort )
            {
                case "count":
                {
		 $sort_text = "ORDER BY eZRfp_Rfp.ID DESC";
                 //   $sort_text = "ORDER BY Count";
                    break;
                }
                default:
                case "name":
                {
		  $sort_text = "ORDER BY eZRfp_Rfp.ID ASC";
                 //   $sort_text = "ORDER BY ContentsWriterID";
		 //   $sort_text = "ORDER BY eZRfp_Rfp.ID DESC";
                    break;
                }
            }
        }

        $db =& eZDB::globalDatabase();

	/*      
        $db->array_query( $qry_array, "SELECT eZRfp_Rfp.ID, eZRfp_Rfp.ContentsWriters
                                       FROM eZRfp_Rfp
                                       WHERE eZRfp_Rfp.IsPublished='1'
				       $sort_text ",	
				       array( "Limit" => $limit, "Offset" => $offset ) );
	*/
/*

GROUP BY eZUser_User.ID, eZUser_User.FirstName $sort_text ",
        array( "Limit" => $limit, "Offset" => $offset ) );
##########################

        $db->array_query( $qry_array, "SELECT eZRfp_Rfp.ID, eZRfp_Rfp.ContentsWriters
                                       FROM eZRfp_Rfp
                                       WHERE eZRfp_Rfp.IsPublished='1'
                                       GROUP BY eZRfp_Rfp.ID $sort_text ",
                                       array( "Limit" => $limit, "Offset" => $offset ) );

###############


        $db->array_query( $qry_array, "SELECT eZRfp_Rfp.ID, eZRfp_Rfp.ContentsWriters
                                       FROM eZRfp_Rfp
                                       WHERE eZRfp_Rfp.IsPublished='1' ",
                                       array( "Limit" => $limit, "Offset" => $offset ) );

*/

//v_array( $qry_array );

$rfp = new eZRfp(  );

// check if the rfp exists
$rfp->get( 1 );

$rfpName = $rfp->Name;
$rfpContentsWriters = $rfp->contentsWriters(false);

$rfpContentsWriters1 = $rfpContentsWriters[0];

$rfpContentsWritersz = $rfpContentsWriters1->ID;


/*
print('ID Static: '. $rfpContentsWritersz . '<br>' . $rfpName);

print ('<br>Count '. sizeof($qry_array) .'<br>');
print ('<br>Contents '. $rfpContentsWriters[0] .'<br>' );

*/

// v_array($rfpContentsWriters[0]);

// print ('<br>black '. count($rfpContentsWriters) .'<br>' );


// ##########################################################################

 $procurements = new eZRfp();
 // get a list of all procurements
 $procurements = $procurements->getAll();


 //super foreach . . .
 foreach ($procurements as $procurement) {
   // get a list (IDs) of planholders per each procurement
    $qry_array[] = $procurement->planholders(false);
 }

 /*
 v_array($qry_array);
 v_array($qry_array[1]);
 v_array($qry_array[2]);
 */

 $ary_array_len = sizeof($qry_array);
 $loop_array_len = $ary_array_len -1;

for ($i = 0; $i <= $loop_array_len; $i++) {
 //  echo '<br> count <b>'. $i .'</b>';

	$aArrayItem = $qry_array[$i];
	//	$aArrayItemItem = $aArrayItem[1];
	$aArrayItemItem = $aArrayItem;
	// v_array($aArrayItem);

        // #################################################################
	//$aArrayItemUS = unserialize($aArrayItemItem);
	$aArrayItemUS = $aArrayItemItem;
	// print($aArrayItemItem);


	//   echo '<br>result1 '. $aArrayItemItem;

	if ($aArrayItemUS != ''){ 
	  //v_array($aArrayItemUS);

	 foreach ( $aArrayItemUS as $aArrayItemHolder) {
	   // print($aArrayItemHolder ."<br />");

	   //	   v_array($aArrayItemHolder[0]);
	   $aArrayItemHolderNameObj = new eZUser($aArrayItemHolder);
	   $aArrayItemHolderNameObj = $aArrayItemHolderNameObj->personId();

	   //	   die($aArrayItemHolderNameObj);

	   $aArrayItemHolderNameObj = new eZPerson($aArrayItemHolderNameObj);

	   //	   $aArrayItemHolderNameObj = new eZPerson($aArrayItemHolder);
	   
	   $aArrayItemHolderName = $aArrayItemHolderNameObj->FirstName .' '. $aArrayItemHolderNameObj->LastName;
	   //	   print($aArrayItemHolderObj->name());

	//   echo '<br/><br/> ID: ' . $aArrayItemHolderName;
	//   $ekey = array_search($aArrayItemHolder, $aArrayItemHoldersID)

	//	if ( !array_search($aArrayItemHolder, $aArrayItemHoldersID) ) {

	   $aArrayItemHolders[] = $aArrayItemHolder;
	//   $aArrayItemHoldersObj[] = new eZPerson($aArrayItemHolder);
	   $aArrayItemHoldersID[] = $aArrayItemHolder;
	   $aArrayItemHoldersNames[] = $aArrayItemHolderName;
	  // }
	}
	
	}else {
	//  echo '<br>NULL';   
	}
//   echo '<br>result2 '. $aArrayItemUS;
  // echo '<br>';
}
//echo '<br>';

        //#############################################################

        $aArrayItemHoldersID = array_unique($aArrayItemHoldersID);

	// v_array( $aArrayItemHoldersID );

        foreach ( $aArrayItemHoldersID as $holderUserID ) {
	  $holderUser = new eZUser($holderUserID);
	  $holderPersonID = $holderUser->PersonID;
          $aArrayItemHoldersObj[] = new eZPerson($holderPersonID);
        }

        // v_array($aArrayItemHolders);
        // $aArrayItemHolders = array_unique($aArrayItemHolders);


	//#############################################################


	return $aArrayItemHoldersObj;


//#############################################################


// for($a = 0; $a < $qry_array_len; $a++) {
// print ($a);
/*
        $ContentsWriterID = $qry_array[$a];
        $contentsWriters = unserialize($ContentsWriterID);
        $contentsWritersID = unserialize($ContentsWriterID);
        $contentsWriterUser = new eZPerson($contentsWritersID);
        $contentsWriterUser = $contentsWriterUser->ID;
        $contentsWriterz[] = $contentsWriterUser;
        print ( 'bellow: '. $ContentsWriterID .'<br>');
*/
// }




// v_array(rfpContentsWriters[0]);

//print($rfpName);

// echo ($aRfp->Name);
// exit();
//v_array($aRfp);

$contentsWriters = $this->ContentsWriters;
//#############################################################
// $contentsWriters = $this->planholders():


/*
echo ( " $contentsWriters
#######################
" );
exit();
*/

// v_array( $contentsWriters );

// http://ladivaloca.org/index.php/rfp/author/view/15



// Create some objects and store them in an array
// $my_objects = array();
// print ('count'. count($qry_array) );

/*
for($a = 0; $a < $qry_array_len; $a++) {
	$ContentsWriterID = $qry_array[$a];
	$contentsWriters = unserialize($ContentsWriterID);
	$contentsWritersID = unserialize($ContentsWriterID);
	$contentsWriterUser = new eZUser($contentsWritersID);
	$contentsWriterUser = $contentsWriterUser->ID;
	$contentsWriterz[] = $contentsWriterUser;
	print ( $ContentsWriterID .'<br>');
}
*/
 
/*
	foreach ( $qry_array as $rfpWriter ) {
		$ContentsWriterID = ContentsWriters[1];
		$contentsWriters = unserialize($ContentsWriterID);
		$this->ContentsWriters (already unserialized in fill !!00XDS

		// by each user obj in array, get id
		// user->ID, user->firstname, user->lastname, user->email

		// build array of author users & info.
		// build array of per user , rfp's they are associated with . . . 
	}
	$qry_array = 0;
*/


// } //end super foreach


//        return $qry_array;
    }

    /*!
      Returns a all rfps an author has written that currentuser is allowed to see.
    */
    function authorRfpList( $authorid, $offset = 0, $limit = -1, $sort = false )
    {

        if ( is_string( $sort ) )
        {
            switch( $sort )
            {
               case "name":
                {
                    $sort_text = "ORDER BY A.Name";
                    break;
                }
                case "category":
                {
                    $sort_text = "ORDER BY C.Name";
                    break;
                }
                default:
                case "published":
                {
                    $sort_text = "ORDER BY A.Published DESC";
                    break;
                }
            }
        }

        $query = "SELECT A.ContentsWriters, A.ID, A.Name, User.FirstName, User.LastName, A.Published, C.ID as CategoryID, 	 C.Name as CategoryName
        FROM
	eZRfp_Rfp AS A,
	eZRfp_Category as C,
	eZRfp_RfpCategoryDefinition as ACL,
	eZRfp_RfpPermission AS P,
	eZRfp_CategoryPermission AS CP,
	eZUser_User as User 
	WHERE A.ID=ACL.RfpID AND C.ID=ACL.CategoryID
	AND
	IsPublished='1'
	GROUP BY A.ID $sort_text ";

        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, $query, array( "Limit" => $limit, "Offset" => $offset ) );

	$ary_array_len = sizeof($qry_array);
	$loop_array_len = $ary_array_len -1;
	

	// print("<br>". $ary_array_len );
	// v_array( $qry_array );

	for ($i = 0; $i <= $loop_array_len; $i++) {

	//  echo '<br> count <b>'. $i .'</b><br />';
          $aArrayItem = $qry_array[$i];
          $aArrayItemItem = $aArrayItem[1];
	  $aArrayFirstItem = $aArrayItem[0];
          $aArrayItemUS[1]['users'] = unserialize($aArrayFirstItem);
	  $aArrayItemUS[0]['rfp'] =  $aArrayItem[1];
	
        // v_array($aArrayItemUS[0]);
	  $bdq = $aArrayItemUS[1]['users'];
        //  print($bdq[0]);

	// if( is_array($bdq) ) {

	$bdq_array_len = sizeof($bdq);
	$cloop_array_len = $bdq_array_len -1;

	// print("77777777777777 :: ". $bdq_array_len);
	// if (bdq_array_len >= 1){

	for ($pi = 0; $pi <= $cloop_array_len; $pi++) {
		if ($bdq[$pi] == $authorid) {
  		//	print ( "<br />$pi - the new line item : ". $authorid ." - ". $bdq[$pi] ."<br>");
                        $new_array[] = $qry_array[$i];
		}
		//	print ( "<br>$pi - THE Author Was: ". $authorid ." - ". $bdq[$pi] ."<br>");
	
		// v_array($aArrayItemUS[1]['users']);
		// v_array($aArrayItemHolder);
	
		// 	if ($bdq[0] == $authorid) {
		// v_array($qry_array[$i]);

	//for
	}
                // if (!isset($totalCount)) $totalCount = 0;
}


	/*
	 $de_array[] = $qry_array[0];
	 $de_array[] = $qry_array[2];
	 return $de_array;
	*/

	  return $new_array;
//        return $qry_array;
    	
    }

    /*!
      Returns the number of rfps this author has written that the user is allowed to see.
    */
    function authorRfpCount( $authorid )
    {
	$in_contentswriter = '';

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        $usePermission = true;
/*
A        if ( $user )
        {

            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( P.GroupID='$group' AND CP.GroupID='$group' ) OR
                              ( P.GroupID='$group' AND CP.GroupID='-1' ) OR
                              ( P.GroupID='-1' AND CP.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $currentUserSQL = "A.HolderID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL P.GroupID='-1' AND CP.GroupID='-1' ) AND P.ReadPermission='1' AND CP.ReadPermission='1' ) ) ";
*/
        /*

        $query = "SELECT count( DISTINCT A.ID ) AS Count
                     FROM eZRfp_Rfp AS A,
                     eZRfp_RfpCategoryDefinition AS Def,
                     eZRfp_RfpPermission AS P,
                     eZRfp_CategoryPermission AS CP,
                     eZUser_Author as Author,
					 eZUser_User as User
                     WHERE
                     $loggedInSQL AND CP.ObjectID=Def.CategoryID AND A.ID=P.ObjectID AND  A.ID=Def.RfpID AND
                     A.ContentsWriterID=User.ID AND A.IsPublished='1' AND A.ContentsWriterID='$authorid'";
        */

/*
	$ezrfp = new eZRfp();
	$ezrfp = $ezrfp->getAll();
	$ezrfpCW = $ezrfp[0]->contentsWriters();
	$ezrfpCWW = unserialize($ezrfpCW);
	$ezrfpCWWIDs = $ezrfpCWW->ID;
*/
	// loop over us cws build id array, search array, count for each rfp the in_cw apears and build count total for user and return count.
	
	$ezRfpContentsWriterIDs[] = $ezrfpCWWIDs;
/*

        $query = "SELECT count( DISTINCT A.ID ) AS Count
                     FROM eZRfp_Rfp AS A,
                     eZRfp_RfpCategoryDefinition AS Def,
                     eZRfp_RfpPermission AS P,
                     eZRfp_CategoryPermission AS CP,
                     eZUser_User as User
                     WHERE
                     $loggedInSQL AND CP.ObjectID=Def.CategoryID AND A.ID=P.ObjectID AND  A.ID=Def.RfpID AND
                     A.ContentsWriters=User.ID AND A.IsPublished='1' AND A.ContentsWriters='$in_contentswriter'";
*/


 $db =& eZDB::globalDatabase();
 /*
 $db->array_query( $qry_array, "SELECT eZRfp_Rfp.ID, eZRfp_Rfp.ContentsWriters
                                       FROM eZRfp_Rfp
                                       WHERE eZRfp_Rfp.IsPublished='1' ",
                                       array( "Limit" => $limit, "Offset" => $offset ) );



 $the_qry = "SELECT eZRfp_Rfp.ID, eZProcurement_ProcurementHolderDefinition.*, count( DISTINCT eZRfp_Rfp.ID ) as Count
            FROM eZRfp_Rfp, eZProcurement_ProcurementHolderDefinition
            WHERE eZRfp_Rfp.IsPublished='1' AND eZProcurement_ProcurementHolderDefinition.ProcurementID = eZRfp_Rfp.ID";
 */

      $the_qry = "SELECT eZRfp_Rfp.ID, eZProcurement_ProcurementHolderDefinition.*
      FROM eZRfp_Rfp, eZProcurement_ProcurementHolderDefinition
      WHERE eZRfp_Rfp.IsPublished='1' AND eZProcurement_ProcurementHolderDefinition.ProcurementID = eZRfp_Rfp.ID AND eZProcurement_ProcurementHolderDefinition.PersonID = $authorid";

 $db->array_query( $qry_array, $the_qry, array( "Limit" => $limit, "Offset" => $offset ) );


 //  die($the_qry);
 // v_array( $qry_array[0] );

 $totalCount = count( $qry_array );
 //echo "<div style='font: 20px; color: red;'>Total Count: $totalCount</div>";
 return $totalCount;


$ary_array_len = sizeof($qry_array);
$loop_array_len = $ary_array_len -1;


for ($i = 0; $i <= $loop_array_len; $i++) {
//  echo '<br> count <b>'. $i .'</b>';
        $aArrayItem = $qry_array[$i];
        $aArrayItemItem = $aArrayItem[1];
        $aArrayItemUS = unserialize($aArrayItemItem);

//   echo '<br>result1 '. $aArrayItemItem;
        if ($aArrayItemUS != ''){
   //       v_array($aArrayItemUS);

         foreach ( $aArrayItemUS as $aArrayItemHolder) {
           $aArrayItemHolderNameObj = new eZPerson($aArrayItemHolder);
           $aArrayItemHolderName = $aArrayItemHolderNameObj->FirstName .' '. $aArrayItemHolderNameObj->LastName;
 	     $authornewid = $aArrayItemHolderNameObj-->ID;
	//	echo '<br/><br/> ID: ' . $aArrayItemHolderName .$aArrayItemHolder.'<br/><br/>';
		if (!isset($totalCount)) $totalCount = 0;
	//	echo "Author ID: $authorid <br>";
		if ($authorid == $aArrayItemHolder) { 
			$totalCount = $totalCount +1;
//			echo "Total Count: $totalCount";
		}
        // $ekey = array_search($aArrayItemHolder, $aArrayItemHoldersID)

        //      if ( !array_search($aArrayItemHolder, $aArrayItemHoldersID) ) {

           $aArrayItemHolders[] = $aArrayItemHolder;
        //   $aArrayItemHoldersObj[] = new eZUser($aArrayItemHolder);
           $aArrayItemHoldersID[] = $aArrayItemHolder;
           $aArrayItemHoldersNames[] = $aArrayItemHolderName;
          // }
        }

        }else {
        //  echo '<br>NULL';
        }
//  echo '<br>result2 '. $aArrayItemUS;
  // echo '<br>';
}
//echo '<br>';

// $aArrayItemHoldersID = array_unique($aArrayItemHoldersID);

$di = 0;
        foreach ( $aArrayItemHoldersID as $holderUser ) {
          $aArrayItemHoldersObj[] = new eZPerson($holderUser);
//	  $aArrayItemHoldersIDz[] = $holderUser;
	  
/*
	$array = array(0 => 'blue', 1 => 'red', 2 => 'green', 3 => 'red');

	$key = array_search('blue', $array); // $key = 2;
	//$key = array_search('red', $array);  // $key = 1;

	print $key."<br>";

	if($key===FALSE){
	  print "FALSE<br>";
	}else{
	  print "TRUE<br>";
	}
*/

//	$aArrayItemHoldersIDz = array();
	 $holderUserCount = 0;
         $aArrayItemHoldersIDz[][$holderUser] = $holderUserCount;
	 $aArrayItemHoldersIDzz = $aArrayItemHoldersIDz;

//v_array( $aArrayItemHoldersIDzz );

	 $dkey = in_array_multi( $holderUser, $aArrayItemHoldersIDzz );

 //	 $dkey = in_array( $holderUser, $aArrayItemHoldersIDzz );
//	 print "\ ". $dkey."<br>";

	
         if($dkey===FALSE){
  //         print "\ FALSE /<br>";
         }else{
    //       print "TRUE<br>";
  
	 }


//	    $holderUserCount = 0;
//	   $aArrayItemHoldersIDz[][$holderUser] = $holderUserCount;
	$di++;
        }

//        v_array($aArrayItemHolders);
	
//        v_array($aArrayItemHoldersIDz);

//$aArrayItemHolders = array_unique($aArrayItemHolders);
// return $aArrayItemHoldersObj;

/*
xxxxxx
*/
	//echo "<div style='font: 20px; color: red;'>Total Count: $totalCount</div>";
	return $totalCount;

        // $db =& eZDB::globalDatabase();
        // $db->array_query( $qry_array, $query );

	//        return (int)$qry_array[0][$db->fieldName("Count")];

    }

    /*!
      Returns a 2d array for each author id in $authArr that holds the count information as well
    */
	function getFullAuthorCount($authArr) {
		for($i=0;$i<sizeof($authArr);$i++) {
			$authArr[$i]['count'] = eZRfp::authorRfpCount( $authArr[$i]);
	}
	return $authArr;
	}
		/*!
      Adds a log message to the rfp.
    */
    function addLog( $message, $user = false )
    {
        $db =& eZDB::globalDatabase();

        if ( !$user )
            $user =& eZUser::currentUser();
        $userID = $user->id();

        $db->begin( );

        $db->lock( "eZRfp_Log" );

        $nextID = $db->nextID( "eZRfp_Log", "ID" );

        $timeStamp =& eZDateTime::timeStamp( true );

        $query = "INSERT INTO eZRfp_Log
                  ( ID,  RfpID, Created, Message, UserID )
                  VALUES
                  ( '$nextID',
                    '$this->ID',
                    '$timeStamp',
                    '$message',
                    '$userID' )";

        $res = $db->query( $query );

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Adds a form to the rfp.
    */
    function deleteForms()
    {
        $db =& eZDB::globalDatabase();

        $RfpID = $this->ID;

        $query = "DELETE FROM eZRfp_RfpFormDict
                  WHERE RfpID=$RfpID
                  ";
        $db->query( $query );
    }


    /*!
      Adds a form to the rfp.
    */
    function addForm( $form )
    {
        $db =& eZDB::globalDatabase();

        if( get_class( $form ) == "ezform" )
        {
            $RfpID = $this->ID;
            $FormID = $form->id();

            $db->begin( );

            $db->lock( "eZRfp_RfpFormDict" );

            $nextID = $db->nextID( "eZRfp_RfpFormDict", "ID" );

            $query = "INSERT INTO eZRfp_RfpFormDict
                      ( ID, RfpID, FormID )
                      VALUES ( '$nextID', '$RfpID', '$FormID' )
                      ";
            $res = $db->query( $query );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();

        }
    }

    /*!
      Returns an array of the forms for the current rfp.
    */
    function forms( $as_object = true)
    {
        $db =& eZDB::globalDatabase();

        include_once( "ezform/classes/ezform.php" );

        $RfpID = $this->ID;

        $return_array = array();

        $query = "SELECT FormID FROM eZRfp_RfpFormDict
                      WHERE RfpID=$RfpID
                      ";

        $db->array_query( $ret_array, $query );
        $count = count( $ret_array );
        for( $i = 0; $i < $count; $i++ )
        {
            $id = $ret_array[$i][$db->fieldName("FormID")];
            $return_array[] = $as_object ? new eZForm( $id ) : $id;
        }
        return $return_array;
    }

    /*!
      Returns an array of the logg messages for the current rfp.

      The messages are returned as : array( date, message ).
    */
    function logMessages( )
    {
        $db =& eZDB::globalDatabase();
        $ret = array();

        $query = "SELECT * FROM  eZRfp_Log
                  WHERE RfpID='$this->ID'
                  ORDER BY Created
                  ";


        $db->array_query( $ret_array, $query );
        foreach( $ret_array as $log )
        {
            $ret[] = array( "Created" => $log[$db->fieldName( "Created" )], "UserID" => $log[$db->fieldName( "UserID" )], "Message" => $log[$db->fieldName( "Message" )] );
        }
        return $ret;
    }

    /*!
      Returns all the rfps in the database.

      The rfps are returned as an array of eZRfp objects.
    */
    function &getAll( )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $rfpArray = array();

        $db->array_query( $rfpArray, "SELECT * FROM eZRfp_Rfp" );
        for ( $i=0; $i < count($rfpArray); $i++ )
        {
			$rfpArray[$i]['dueDateFormatted'] = date("m-d-Y H:i:s", $rfpArray[$i]['ResponseDueDate']);
			//print($rfpArray[$i]['dueDateFormatted'] ."=".date("m-d-Y H:i:s", $rfpArray[$i]['ResponseDueDate']).'<br>');
			//print("<b><br />".$returnArray[$i]['ResponseDueDate']."<br>".$returnArray[$i]['dueDateFormatted']."</b><br />");
			//v_array($rfpArray[$i]);
			//print("getAll: ".$returnArray[$i]." <br>"); //dylan take it out
			if ($i==2) {
				$rfpArray[$i]['ResponseDueDate'] = 1073755322;
			//    v_array($rfpArray[$i]);
			}
            $returnArray[$i] = new eZRfp( $rfpArray[$i] );
        }

        return $returnArray;
    }

    /*!
      Returns all the rfps that is valid now.

      The rfps are returned as an array of eZRfp objects.
    */
    function &getAllValid( $isPublished=false )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $rfpArray = array();

        if ( !$isPublished )
            $published = "  IsPublished='0' ";
        else
            $published = "  IsPublished='1' ";

        $now =& eZDateTime::timeStamp( true );

        $db->array_query( $rfpArray, "SELECT *
                                          FROM eZRfp_Rfp
                                          WHERE $published
                                          AND ( PublishDate !='0' AND PublishDate <= $now )
                                          AND ( ResponseDueDate ='0' OR ResponseDueDate >= $now )
                                          ORDER BY ID
                                          " );

        for ( $i=0; $i < count($rfpArray); $i++ )
        {
            $returnArray[$i] = new eZRfp( $rfpArray[$i] );
        }

        return $returnArray;
    }

    /*!
      \private
      Send mail to the editors that will tell them that a new rfp is ready to be published.
    */
    function sendPendingMail()
    {
        $ini =& INIFile::globalINI();
        $Language = $ini->read_var( "eZRfpMain", "Language" );
        $adminSiteURL = $ini->read_var( "site", "AdminSiteURL" );
        $index = $ini->Index;
        $wwwDir = $ini->WWWDir;
        global $HTTP_HOST;

        $definition = $this->categoryDefinition();

        $editorGroup = $definition->editorGroup();


        $mailTemplate = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                                        "ezrfp/admin/intl", $Language, "pendingmail.php" );

        $mailTemplate->set_file( "pending_mail_tpl", "pendingmail.tpl" );
        $mailTemplate->setAllStrings();


        $mail = new eZMail();

        $author = $this->contentsWriter();
        $authorEmail = $author->email();
        $name = $author->name();

        $mailTemplate->set_var( "author", $name . " " . $authorEmail );
        $mailTemplate->set_var( "url", "http://" . $HTTP_HOST . $wwwDir . $index .
                                "/rfp/rfppreview/" . $this->ID .  "/" );
        $mailBody = $mailTemplate->parse( "dummy", "pending_mail_tpl" );
        $mail->setBody( $mailBody );

        $mail->setFrom( $authorEmail );

        $users = $editorGroup->users();
        foreach ( $users as $userItem )
        {
            $mail->setTo( $userItem->email() );
            $mail->send();
        }
    }

    /*!
      Returns all the rfps that is not valid now.

      The rfps are returned as an array of eZRfp objects.
    */
    function &getAllUnValid( $isPublished=true )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $rfpArray = array();

        if ( !$isPublished )
            $published = "  IsPublished='0' ";
        else
            $published = "  IsPublished='1' ";

        $now =& eZDateTime::timeStamp( true );

        $db->array_query( $rfpArray, "SELECT *
                                          FROM eZRfp_Rfp
                                          WHERE $published
                                          AND ( ResponseDueDate !='0')
                                          AND ResponseDueDate <= $now
                                          ORDER BY ID
                                          " );

        for ( $i=0; $i < count($rfpArray); $i++ )
        {
            $returnArray[$i] = new eZRfp( $rfpArray[$i] );
        }

        return $returnArray;
    }


    var $ID;
    var $HolderID;
    var $ContentsWriterID;
    var $ContentsWriters;
    var $Planholders;
    var $Name;
    var $Contents;

    var $Modified;
    var $Created;
    var $Published;
    // override publising date
    var $PublishedOverride;
    var $Keywords;
    var $Discuss;

    //    var $TopicID;
    var $Project;
    var $ProjectEstimate;
    var $ProjectNumber;
    var $ProjectManager;

    var $PublishDate;
    var $ResponseDueDate;

    var $BidAwardDate;
    
    // tell eZ publish to show the rfp to the public
    var $IsPublished;

    // variable for storing the number of pages in the rfp.
    var $PageCount;
    var $ImportID;

    // undefined aditional results
    var $AdditionalBidResults;
 
}

?>
