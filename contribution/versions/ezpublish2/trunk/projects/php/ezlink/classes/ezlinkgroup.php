<?
class eZLinkGroup
{
    /*
      Counstructor
    */
    function eZLinkGroup( )
    {

    }

    /*
      Lager linkgruppe i databasen
    */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO LinkGroup SET
                ID='$this->ID',
                Title='$this->Title',
                Parent='$this->Parent'" );
    }

    /*
      Oppgraderer databasen
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE LinkGroup SET 
                Title='$this->Title',
                Parent='$this->Parent'
                WHERE ID='$this->ID'" );
    }

    /*
      Slette fra databasen
    */
    function delete( )
    {
        $this->dbInit();
        query( "DELETE FROM LinkGroup WHERE ID='$this->ID'" );
    }

    /*
      Henter ut alle gruppene fra databasen.
    */
    function get( $id )
    {
        $this->dbInit();
        array_query( $linkgroup_array,  "SELECT * FROM LinkGroup WHERE ID='$id'" );
        if ( count( $linkgroup_array ) > 1 )
        {
            die( "feil, flere grupper med samme id" );
        }
        else if ( count( $linkgroup_array ) == 1 )
        {
            $this->ID = $linkgroup_array[ 0 ][ "ID" ];
            $this->Title = $linkgroup_array[ 0 ][ "Title" ];
            $this->Parent = $linkgroup_array[ 0 ][ "Parent" ];
        }
    }

    /*
      Rekursiv funksjon, skriver ut hele pathen til gruppen.
    */
    function printPath( $id, $url )
    {
        $lg = new eZLinkGroup();
        $lg->get( $id );

        if ( $lg->parent() != 0 )
        {
            $this->printPath( $lg->parent(),  $url );
        }
        else
        {
            print( "/ <a href=\"index.php?page=$url&LGID=0\">" . "kategorier" . "</a>" );
        }
        print( " / <a href=\"index.php?page=$url&LGID=$id\">" . $lg->title() . "</a>" );
    }


    /*
      Henter ut parent
    */
    function getByParent( $id )
    {
        $this->dbInit();
        $parent_array = 0;

        array_query( $parent_array, "SELECT * FROM LinkGroup WHERE Parent='$id'" );

        return $parent_array;
    }

    /*
      Henter ut _alt_
    */
    function getAll()
    {
        $this->dbInit();
        $parnet_array = 0;

        array_query( $parent_array, "SELECT * FROM LinkGroup ORDER BY Title" );

        return $parent_array;
    }

    /*
      Setter navn.
    */
    function setTitle( $value )
    {
        $this->Title = ( $value );
    }

    /*
      Setter parent.
    */
    function setParent( $value )
    {
        $this->Parent = ( $value );
    }

    /*
      returnerer navn.
    */
    function Title()
    {
        return $this->Title;
    }

    /*
      returnerer parent.
    */
    function parent()
    {
        return $this->Parent;

    }
/*
          Initiering av database
        */
    function dbInit()
    {
        require "ezlink/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

var $ID;
var $Title;
var $Parent;


}

    




?>
