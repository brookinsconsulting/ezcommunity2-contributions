#!/bin/bash
echo "Clearing the cache."

dirs="
ezlink
ezforum
ezpoll
eztrade
ezarticle
"

for dir in $dirs
do
    if [ -d $dir ]; then
	    echo "Clearing $dir"
        rm -f $dir/cache/*.cache
    else
        echo "Creating $dir"
	    mkdir -p $dir
    fi
    chmod 777 $dir   
done
