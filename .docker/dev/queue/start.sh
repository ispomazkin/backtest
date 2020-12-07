#!/usr/bin/env bash
i=0
while [ $i -lt $1 ]; do
  php /app/yii queue/listen --verbose &
  i=$(( i + 1 ))
done
php /app/yii queue/listen --verbose

