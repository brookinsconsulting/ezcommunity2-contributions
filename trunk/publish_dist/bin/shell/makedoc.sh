#!/bin/sh
echo "Generatin eZ publish documentation"

ezphpdoc.pl --disable_todo classes/ ezarticle/classes/ ezad/classes/ ezbug/classes/ ezcalendar/classes/ ezcontact/classes/ ezfilemanager/classes/ ezforum/classes/ ezimagecatalogue/classes/ ezlink/classes/ eznewsfeed/classes/ ezpoll/classes/ ezsession/classes/ ezstats/classes/ eztodo/classes/ eztrade/classes/ ezuser/classes/ ezaddress/classes/
