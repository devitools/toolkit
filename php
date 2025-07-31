#!/bin/bash

ACTION="$1"

case ${ACTION} in
  "stop")
    docker stop php-cli
  ;;
  "start")
    docker run --rm --name php-cli -i -t -d \
         --workdir "$(pwd)" \
         --volume "$(pwd):$(pwd)" \
         --entrypoint /bin/sh \
         --platform linux/amd64 \
         devitools/hyperf:8.3-dev &>/dev/null
  ;;
 *)
   if [ -z "$(docker ps -a | grep php-cli)" ]; then
      echo "PHP is not running"
      echo "  use: php up"
      exit 1
   fi
   echo "$@" >> /Users/william.correa/.local/bin/php.log
   docker exec php-cli php $@
  ;;
esac
