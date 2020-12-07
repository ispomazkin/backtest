##Docker
1. Install docker https://docs.docker.com/install/
2. Install docker-composer https://docs.docker.com/compose/install/
3. cp .env.example .env
4. edit .env
5. $ docker-compose -f docker-compose.nginx-proxy.yml -f docker-compose.yml up
6. sudo chown $USER:docker /var/run/docker.sock
7. docker plugin install vieux/sshfs
8. Если нужна папка vendor, то копируем ее себе на машину из контейнера sudo docker cp PHP_CONTAINER_ID:/app/vendor ./
