
#!/bin/bash

PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin

export PATH

step=5

for (( i = 0; i < 59; i=(i+step) )); do
curl -sS --connect-timeout 10 -m 59 ''http://127.0.0.1/tools/autos.php?admin=toor''
curl -sS --connect-timeout 10 -m 59 ''http://127.0.0.1/tools/action.php?admin=toor''
curl -sS --connect-timeout 10 -m 59 ''http://127.0.0.1/tools/autokjs.php?admin=toor''
curl -sS --connect-timeout 10 -m 59 ''http://127.0.0.1/tools/autoflys.php?admin=toor''

echo "--------------------------------------------------"

endDate=`date +"%Y-%m-%d %H:%M:%S"`

echo "[$endDate] 成功"

echo "--------------------------------------------------"

sleep $step

done
