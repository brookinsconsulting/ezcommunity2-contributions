<?
// 
// $Id: articleedit.php,v 1.88 2001/06/08 09:00:37 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 15:04:39 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcachefile.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezuser/classes/ezauthor.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/eztopic.php" );
include_once( "ezarticle/classes/ezarticlegenerator.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );

include_once( "ezbulkmail/classes/ezbulkmail.php" );
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

include_once( "ezarticle/classes/ezarticletool.php" );

$ini =& INIFile::globalINI();

// insert a new article in the database

if ( $Action == "Insert" )
{
    $user = eZUser::currentUser();
        
    $article = new eZArticle( );
    $article->setName( $Name );
    
    $article->setAuthor( $user );

    $author = new eZAuthor( $ContentsWriterID );
    $article->setContentsWriter( $author );

    $topic = new eZTopic( $TopicID );
    $article->setTopic( $topic );
    

    $generator = new eZArticleGenerator();

    if ( trim( $LogMessage ) != "" )
        $article->addLog( $LogMessage );

    $contents = $generator->generateXML( $Contents );
    $article->setContents( $contents );
    $article->setPageCount( $generator->pageCount() );
    $article->setAuthorText( $AuthorText );
    $article->setAuthorEmail( $AuthorEmail );
    $article->setLinkText( $LinkText );

    if ( $Discuss == "on" )
        $article->setDiscuss( true );
    else
        $article->setDiscuss( false );

    
    $article->store(); // to get the ID

    if( isset( $WriteGroupArray ) )
    {
        if( $WriteGroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $article->id(), "article_article", 'w' );
        }
        else
        {
            eZObjectPermission::removePermissions( $article->id(), "article_article", 'w' );
            foreach ( $WriteGroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $article->id(), "article_article", 'w' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $article->id(), "article_article", 'w' );
    }
    
    /* read access thingy */
    if ( isset( $GroupArray ) )
    {
        if( $GroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $article->id(), "article_article", 'r' );
        }
        else // some groups are selected.
        {
            eZObjectPermission::removePermissions( $article->id(), "article_article", 'r' );
            foreach ( $GroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $article->id(), "article_article", 'r' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $article->id(), "article_article", 'r' );
    }
    
        
    // check if the contents is parseable
    if ( xmltree( $contents ) )
    // add document validation here
//    if ( true )
    {
        // generate keywords
        $contents = strip_tags( $contents );
        $contents = ereg_replace( "#\n#", "", $contents );
        $contents_array =& split( " ", $contents );
        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            $keywords .= strtolower( trim( $word ) ) . " ";
        }

        $article->setKeywords( $keywords );
        
        $article->store();

        $article->setManualKeywords( $Keywords );

        // add to categories
        $category = new eZArticleCategory( $CategoryID );
        $category->addArticle( $article );

        $article->setCategoryDefinition( $category );

        if ( count( $CategoryArray ) > 0 )
        {
            foreach ( $CategoryArray as $categoryItem )
            {
                if ( $categoryItem != $CategoryID )
                {
                    $category = new eZArticleCategory( $categoryItem );
                    $category->addArticle( $article );
                }
            }
        }


        $articleID = $article->id();

        $categoryArray = $article->categories();
        $categoryIDArray = array();
        foreach ( $categoryArray as $cat )
        {
            $categoryIDArray[] = $cat->id();
        }

        // add check for publishing rights here
        if ( $IsPublished == "on" )
        {
            eZArticleTool::notificationMessage( $article );
        
            $article->setIsPublished( true );
        }
        else
        {
            $article->setIsPublished( false );
        }

        $article->store();


        // clear the cache files.
        eZArticleTool::deleteCache( $ArticleID, $CategoryID, $CategoryArray );
        
        // add images
        if ( isset( $Image ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/imagelist/$articleID/" );
            exit();
        }

        // add files
        if ( isset( $File ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/filelist/$articleID/" );
            exit();
        }
        
        // add attributes
        if ( isset( $Attribute ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/attributelist/$articleID/" );
            exit();
        }
        
        
        // preview
        if ( isset( $Preview ) )
        {
            eZHTTPTool::header( "Location: /article/articlepreview/$articleID/" );
            exit();
        }

        // log history
        if ( isset( $Log ) )
        {
            eZHTTPTool::header( "Location: /article/articlelog/$articleID/" );
            exit();
        }
        

        // get the category to redirect to
        $category = $article->categoryDefinition( );
        $categoryID = $category->id();

        if ( $article->isPublished() )
            eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
        else
            eZHTTPTool::header( "Location: /article/unpublished/$categoryID/" );
        exit();
    }
    else
    {
        $Action = "New";
        $ErrorParsing = true;
    }
}


if ( $Action == "Cancel" )
{
    $article = new eZArticle( $ArticleID );

    $category = $article->categoryDefinition( );
    
    if ( $category )
    {
        $categoryID = $category->id();
    }

    eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
    exit();
}

// update an existing article in the database
if ( $Action == "Update" )
{
    $article = new eZArticle( $ArticleID );
    $article->setName( $Name );

    $oldCategory = $article->categoryDefinition();
    $oldCategoryID = $oldCategory->id();

    $author = new eZAuthor( $ContentsWriterID );
    $article->setContentsWriter( $author );

    $topic = new eZTopic( $TopicID );
    $article->setTopic( $topic );
    
    $generator = new eZArticleGenerator();

    $contents = $generator->generateXML( $Contents );
    
    $article->setContents( $contents  );
    $article->setPageCount( $generator->pageCount() );
    $article->setAuthorText( $AuthorText );
    $article->setAuthorEmail( $AuthorEmail );
    $article->setLinkText( $LinkText );

    if ( trim( $LogMessage ) != "" )
        $article->addLog( $LogMessage );

    if ( $Discuss == "on" )
        $article->setDiscuss( true );
    else
        $article->setDiscuss( false );


    eZObjectPermission::removePermissions( $article->id(), "article_article", 'w' );
    if( isset( $WriteGroupArray ) )
    {
        if( $WriteGroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $article->id(), "article_article", 'w' );
        }
        else
        {
            foreach ( $WriteGroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $article->id(), "article_article", 'w' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $article->id(), "article_article", 'w' );
    }
    
    /* read access thingy */
    eZObjectPermission::removePermissions( $article->id(), "article_article", 'r' );
    if ( isset( $GroupArray ) )
    {
        if( $GroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $article->id(), "article_article", 'r' );
        }
        else // some groups are selected.
        {
            foreach ( $GroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $article->id(), "article_article", 'r' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $article->id(), "article_article", 'r' );
    }

    // add check for publishing rights here
    if ( $IsPublished == "on" )
    {
        // check if the article is published now
        if ( $article->isPublished() == false )
        {
            eZArticleTool::notificationMessage( $article );
        }
        
        $article->setIsPublished( true );
    }
    else
    {
        $article->setIsPublished( false );
    }
        
    // check if the contents is parseable
    if ( xmltree( $contents ) )
    // TODO add document validation here:
//    if ( true )
    {
        // generate keywords
        $contents = strip_tags( $contents );
        $contents = ereg_replace( "#\n#", "", $contents );
        $contents_array =& split( " ", $contents );
        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            $keywords .= $word . " ";
        }

        $article->setKeywords( $keywords );

        $article->store();

        $article->setManualKeywords( $Keywords );

        $categoryArray = $article->categories();
        // Calculate new and unused categories
        $old_maincategory = $article->categoryDefinition();
        $old_categories =& array_unique( array_merge( $old_maincategory->id(),
                                                      $article->categories( false ) ) );

        $new_categories = array_unique( array_merge( $CategoryID, $CategoryArray ) );

        $remove_categories = array_diff( $old_categories, $new_categories );
        $add_categories = array_diff( $new_categories, $old_categories );

        $categoryIDArray = array();

        foreach ( $categoryArray as $cat )
        {
            $categoryIDArray[] = $cat->id();
        }

        // clear the cache files.
        eZArticleTool::deleteCache( $ArticleID, $CategoryID, $old_categories );

        foreach ( $remove_categories as $categoryItem )
        {
            eZArticleCategory::removeArticle( $article, $categoryItem );
        }

        // add to categories
        $category = new eZArticleCategory( $CategoryID );
        $article->setCategoryDefinition( $category );

        foreach ( $add_categories as $categoryItem )
        {
            eZArticleCategory::addArticle( $article, $categoryItem );
        }

        // add images
        if ( isset( $Image ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/imagelist/$ArticleID/" );
            exit();
        }

        // add files
        if ( isset( $File ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/filelist/$ArticleID/" );
            exit();
        }

        // add attributes
        if ( isset( $Attribute ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/attributelist/$ArticleID/" );
            exit();
        }

        
        // preview
        if ( isset( $Preview ) )
        {
            eZHTTPTool::header( "Location: /article/articlepreview/$ArticleID/" );
            exit();
        }

        // log history
        if ( isset( $Log ) )
        {
            eZHTTPTool::header( "Location: /article/articlelog/$ArticleID/" );
            exit();
        }
        
        
        // get the category to redirect to
        $category = $article->categoryDefinition( );
        $categoryID = $category->id();

        if ( $article->isPublished() )
            eZHTTPTool::header( "Location: /article/archive/$oldCategoryID/" );
        else
            eZHTTPTool::header( "Location: /article/unpublished/$oldCategoryID/" );
        exit();
    }
    else
    {
        print( htmlspecialchars( $contents ) );

        $Action = "Edit";
        $ErrorParsing = true;        
    }
}

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "articleedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_edit_page_tpl" => "articleedit.tpl"
    ) );

$t->set_block( "article_edit_page_tpl", "author_item_tpl", "author_item" );
$t->set_block( "article_edit_page_tpl", "topic_item_tpl", "topic_item" );

$t->set_block( "article_edit_page_tpl", "value_tpl", "value" );
$t->set_block( "article_edit_page_tpl", "multiple_value_tpl", "multiple_value" );
$t->set_block( "article_edit_page_tpl", "category_owner_tpl", "category_owner" );
$t->set_block( "article_edit_page_tpl", "group_item_tpl", "group_item" );

$t->set_block( "article_edit_page_tpl", "error_message_tpl", "error_message" );

if ( $ErrorParsing == true )
{
    $t->parse( "error_message", "error_message_tpl" );
}
else
{
    $t->set_var( "error_message", "" );
}

$t->set_var( "article_is_published", "" );

$t->set_var( "article_id", "" );
$t->set_var( "article_name", stripslashes( $Name ) );
$t->set_var( "article_keywords", stripslashes( $Keywords ) );
$t->set_var( "article_contents_0", stripslashes( $Contents[0] ) );
$t->set_var( "article_contents_1", stripslashes($Contents[1] ) );
$t->set_var( "article_contents_2", stripslashes($Contents[2] ) );
$t->set_var( "article_contents_3", stripslashes($Contents[3] ) );
$t->set_var( "author_text", stripslashes($AuthorText ) );
$t->set_var( "author_email", stripslashes($AuthorEmail ) );
$t->set_var( "link_text", stripslashes($LinkText  ));

$t->set_var( "action_value", "insert" );
$t->set_var( "all_selected", "selected" );
$t->set_var( "all_write_selected", "selected" );
$writeGroupsID = array(); 
$readGroupsID = array(); 


if ( $Action == "New" )
{
    $user = eZUser::currentUser();
    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName());
    $article = new eZArticle( );
}


if ( $Action == "Edit" )
{
    $article = new eZArticle( );

    if ( !$article->get( $ArticleID ) )
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }
   

    $t->set_var( "article_id", $ArticleID );

    if (  $article->isPublished() )
    {
        $t->set_var( "article_is_published", "checked" );
    }
    else
    {
        $t->set_var( "article_is_published", "" );
    }

    if (  $article->discuss() )
    {
        $t->set_var( "discuss_article", "checked" );
    }
    else
    {
        $t->set_var( "discuss_article", "" );
    }
    
    if ( !isset( $Name ) )        
         $t->set_var( "article_name", $article->name() );

    $generator = new eZArticleGenerator();
    
    $contentsArray = $generator->decodeXML( $article->contents() );

    $i=0;
    foreach ( $contentsArray as $content )
    {
        if ( !isset( $Contents[$i] ) )
        {
            $t->set_var( "article_contents_$i", htmlspecialchars( $content ) );
        }
        $i++;
    }
    $t->set_var( "article_keywords", $article->manualKeywords() );

    $t->set_var( "author_text", $article->authorText() );
    $t->set_var( "author_email", $article->authorEmail() );
    $t->set_var( "link_text", $article->linkText() );

    $t->set_var( "action_value", "update" );

    $author = $article->contentsWriter();
    $ContentsWriterID = $author->id();

    $topic = $article->topic();
    $TopicID = $topic->id();

    $writeGroupsID = eZObjectPermission::getGroups( $ArticleID, "article_article", 'w' , false );
    $readGroupsID = eZObjectPermission::getGroups( $ArticleID, "article_article", 'r', false );

    if( $writeGroupsID[0] != -1 )
        $t->set_var( "all_write_selected", "" );
    if( $readGroupsID[0] != -1 )
        $t->set_var( "all_selected", "" );

}


// author select

$author = new eZAuthor();
$authorArray = $author->getAll();
foreach ( $authorArray as $author )
{
    if ( $ContentsWriterID == $author->id() )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }
    $t->set_var( "author_id", $author->id() );
    $t->set_var( "author_name", $author->name() );
    $t->parse( "author_item", "author_item_tpl", true );
}

// topic select

$topic = new eZTopic();
$topicArray = $topic->getAll();
foreach ( $topicArray as $topic )
{
    if ( $TopicID == $topic->id() )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }
    $t->set_var( "topic_id", $topic->id() );
    $t->set_var( "topic_name", $topic->name() );
    $t->parse( "topic_item", "topic_item_tpl", true );
}

// category select
$category = new eZArticleCategory();
$categoryArray = $category->getAll( );


$tree = new eZArticleCategory();
$treeArray =& $tree->getTree();
$user =& eZUser::currentUser();

$catCount = count( $treeArray );
$t->set_var( "num_select_categories", min( $catCount, 10 ) );

foreach ( $treeArray as $catItem )
{
    if ( eZObjectPermission::hasPermission( $catItem[0]->id(), "article_category", 'w', $user ) == true  ||
         eZArticleCategory::isOwner( eZUser::currentUser(), $catItem[0]->id() ) )
    {    
        if ( $Action == "Edit" )
        {
            $defCat = $article->categoryDefinition( );
        
            if ( get_class( $defCat ) == "ezarticlecategory" )
            {
                if ( $article->existsInCategory( $catItem[0] ) &&
                ( $defCat->id() != $catItem[0]->id() ) )
                {
                    $t->set_var( "multiple_selected", "selected" );
                }
                else
                {
                    $t->set_var( "multiple_selected", "" );
                }
            }
            else
            {
                $t->set_var( "selected", "" );
            }
            
            if ( get_class( $defCat ) == "ezarticlecategory" )
            {
                if ( $defCat->id() == $catItem[0]->id() )
                {
                    $t->set_var( "selected", "selected" );
                }
                else
                {
                    $t->set_var( "selected", "" );
                }
            }
            else
            {
                $t->set_var( "selected", "" );
            }
        }
        else
        {
            $t->set_var( "selected", "" );
            $t->set_var( "multiple_selected", "" );
        }    
        
    
        $t->set_var( "option_value", $catItem[0]->id() );
        $t->set_var( "option_name", $catItem[0]->name() );

        if ( $catItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
        else
            $t->set_var( "option_level", "" );

    
        $t->parse( "value", "value_tpl", true );    
        $t->parse( "multiple_value", "multiple_value_tpl", true );
    }
}

// group selector
$group = new eZUserGroup();
$groupList = $group->getAll();

$t->set_var( "selected", "" );
foreach( $groupList as $groupItem )
{
    /* for the group owner selector */
        $t->set_var( "module_owner_id", $groupItem->id() );
        $t->set_var( "module_owner_name", $groupItem->name() );

        if( in_array( $groupItem->id(), $writeGroupsID ) )
            $t->set_var( "is_selected", "selected" );
        else
            $t->set_var( "is_selected", "" );
    
        $t->parse( "category_owner", "category_owner_tpl", true );

        /* for the read access groups selector */
        $t->set_var( "group_name", $groupItem->name() );
        $t->set_var( "group_id", $groupItem->id() );
        if( in_array( $groupItem->id(), $readGroupsID ) )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
        $t->parse( "group_item", "group_item_tpl", true );
}


$t->pparse( "output", "article_edit_page_tpl" );


?>
