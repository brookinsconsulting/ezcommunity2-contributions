#!/usr/bin/perl -w
#
# Test-Skript für Impetex
#

use IO::Socket;
$remote_host = "localhost";
$remote_port = 40400;
$shop_ta_id = "2";

$connection = IO::Socket::INET->new(PeerAddr=>$remote_host,
					     PeerPort=>$remote_port,
					     Proto   =>"tcp",
					     Type    =>SOCK_STREAM)
or die "Konnte die Verbindung zu $remote_host:$remote_port nicht herstellen: $@\n";

$xml = $ARGV[0];

print $connection $xml;


while (<$connection>)
{
    print
}
# print $results;
    
# print( "fetching result" );
# #$results = <$connection>;
# print $results; 

close($connection);

