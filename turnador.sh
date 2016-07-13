!#/bin/bash

d=2016-04-27

while [ "$d" != 2016-05-01 ]; do 
	  php artisan generarTurnos  $d
echo $d	
    	d=$(date -I -d "$d + 1 day")
    done
