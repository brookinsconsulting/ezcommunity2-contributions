<?php

//!! eZCommon
//! The eZImage class provides image functions.
/*!
  
*/

class eZImage
{
  var $Antall = 0;
  var $Id;
  var $Path;
  var $Prefix = "img";
  var $X = 400;
  var $Y = 300;
  var $ThumbX = 100;
  var $ThumbY = 100;

  function eZImage( $id2 = "" )
    {
      if( $id2 == "" )
        {
          $this->Id = MD5( microtime() . $REMOTE_ADDR );
        }
      else
        {
          $this->Id = $id2;
        }
    }

  function setPath( $path2 = "images/" )
    {
      $this->Path = $this->addSlash( $path2 );
      $this->setAntall();
    }

  function setAntall()
    {
      $fortsett = true;
      $i = 1;
      if( is_int( $this->Id ) )
        {
          while( $fortsett )
            {
              if( file_exists( $this->Path . "img" . $this->Id . "-" . $i . ".jpg" ) )
                {
                  $i++;
                }
              else
                {
                  $fortsett = false;
                }
            }
        }
      else
        {
          while( $fortsett )
            {
              if( file_exists( $this->Path . "tmp" . $this->Id . "-" . $i . ".jpg" ) )
                {
                  $i++;
                }
              else
                {
                  $fortsett = false;
                }
            }
        }
      $this->Antall = $i - 1;
    }
  
  function get( $id )
    {
      $fortsett = true;
      $i = 1;
      $liste = array();
      while( $fortsett )
        {
          if( file_exists( $this->Path . "img" . $id . "-" . $i . ".jpg" ) )
            {
              array_push( $liste, "img" . $id . "-" . $i . ".jpg" );
            }
          else
            {
              $fortsett = false;
            }
          $i++;
        }
      return $liste;
    }

  function getTemp()
    {
      $fortsett = true;
      $i = 1;
      $liste = array();
      while( $fortsett )
        {
          if( file_exists( $this->Path . "tmp" . $this->Id . "-" . $i . ".jpg" ) )
            {
              array_push( $liste, "tmp" . $this->Id . "-" . $i . ".jpg" );
            }
          else
            {
              $fortsett = false;
            }
          $i++;
        }
      return $liste;
    }

  function getThumb( $id )
    {
      if( file_exists( $this->Path . "thumb" . $id . ".jpg" ) )
      {
        $fil = "thumb" . $id . ".jpg";
      }
      else
      {
        $fil = "";
      }
      return $fil;
    }

  // Sletter bilde med nummer som parameter (hvis det ikke er noen parameter
  // slettes alle til denne id'en
  function delete( $id, $nr = 0 )
  {
      if( $nr == 0 )
      {
          $fortsett = true;
          $i = 1;
          while( $fortsett )
          {
              if( file_exists( $this->Path . "img" . $id . "-" . $i . ".jpg" ) )
              {
                  unlink( $this->Path . "img" . $id . "-" . $i . ".jpg" ) or die( "Kan ikke slette filer! Sjekk retigheter" );
              }
              else
              {
                  $fortsett = false;
              }
              $i++;
          }
      }
      else
      {
          if( file_exists( $this->Path . "img" . $id . "-" . $nr . ".jpg" ) )
          {
              unlink( $this->Path . "img" . $id . "-" . $nr . ".jpg" ) or die( "Kan ikke slette filer! Sjekk retigheter" );
          }
      }
  }

  function deleteThumb( $id )
  {
     if( file_exists( $this->Path . "thumb" . $id . ".jpg" ) )
       unlink( $this->Path . "thumb" . $id . ".jpg" );
  }     

  // Sletter en temperær fil til denne id'en og flytter oppover
  function deleteTempNo( $nr )
    {
      if ( file_exists( $this->Path . "tmp" . $this->Id . "-" . $nr . ".jpg" ) )
        {
           unlink( $this->Path . "tmp" . $this->Id . "-". $nr . ".jpg" ) or die( "Kan ikke slette fil! Sjekk retigheter" );
           for( $i = $nr; $i < $this->Antall; $i++ )
             {
	       $next = $i + 1;
               rename( $this->Path . "tmp" . $this->Id . "-" . $next . ".jpg", $this->Path . "tmp" . $this->Id . "-" . $i . ".jpg" );
             }
           $this->Antall--;
        }
    }

  function store( $file, $id )
    {
      if( file_exists( $file ) )
        {
          $this->Antall++;
          $execstr = "convert -geometry \"$this->X" . "x" . "$this->Y" . ">\" " . $file . " " . $this->Path . "img" . $id . "-" . $this->Antall . ".jpg";
          $err = system( $execstr );
        }
      else
        {
          die( "Filen: $file eksisterer ikke!" );
        }
    }
  
  function storeTempAsNormal( $nr )
  {
      for( $i = 1; $i <= $this->Antall; $i++ )
      {
          if( file_exists( $this->Path . "tmp" . $this->Id . "-" . $i . ".jpg" ) )
	  {
	      rename( $this->Path . "tmp" . $this->Id . "-" . $i . ".jpg",
                      $this->Path . "img" . $nr . "-" . $i . ".jpg" );
	  }
      }
  }

  function storeTemp( $file )
    {
      if( file_exists( $file ) )
        {
          $this->Antall++;
          $execstr = "convert -geometry \"$this->X" . "x" . "$this->Y" . ">\" " . $file . " " . $this->Path . "tmp" . $this->Id . "-" . $this->Antall . ".jpg";
          $err = system( $execstr );
        }
      else
        {
          die( "Filen: $file eksisterer ikke!" );
        }
    }

  function storeTempAs( $file, $nr )
    {
      if( file_exists( $file ) )
        {
          $execstr = "convert -geometry \"$this->X" . "x" . "$this->Y" . ">\" " . $file . " " . $this->Path . "tmp" . $this->Id . "-" . $nr . ".jpg";
          $err = system( $execstr );
        }
      else
        {
          die( "Filen: $file eksisterer ikke!" );
        }
    }

  function storeThumb( $file, $id )
    {
      if( file_exists( $file ) )
        {
          $execstr = "convert -geometry \"$this->ThumbX" . "x" . "$this->ThumbY" . ">\" " . $file . " " . $this->Path . "thumb" . $id . ".jpg";
          $err = system( $execstr );
        }
      else
        {
          die( "Filen: $file eksisterer ikke!" );
        }
    }

  function getId()
    {
      return $this->Id;
    }

  function setSize( $xx, $yy )
    {
      $this->X = $xx;
      $this->Y = $yy;
    }

  function setThumbSize( $xx, $yy )
    {
      $this->ThumbX = $xx;
      $this->ThumbY = $yy;
    }

  function addSlash( $path )
    {
      if( substr( $path, strlen( $path ) - 1 ) != "/" )
        $path = $path . "/";
      return $path;
    }
}
      
?>

