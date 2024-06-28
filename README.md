# Les Editions Lunaires

## Stack

* PHP 8.2
* Symfony 6.4

## How to install ?

### Web reverse proxy

If you don't already have a docker web reverse proxy service (ex: traefik), you must start it
```sh
$ docker network create --scope swarm --driver overlay traefik_reverse_proxy
$ docker stack deploy -c .docker/traefik/docker-compose.yml traefik
```

To remove the traefik stack:
```sh
$ docker stack rm traefik
```

Once traefik run, you can check your browser at 127.0.0.1:8080

### Clone repository

Clone this repository using Git :
```sh
$ git clone https://github.com/ScytheOwens/leseditionslunaires.git`
```

Then, move to project directory :
```sh
$ cd EditionsLunaires
```

### Build image

Build the docker image :
```sh
$ make build-image
```

### Start stack

Once docker image is built, you must start the docker stack :
```sh
$ make stack-deploy
```

You can stop it whenever, using :
```sh
$ make stack-undeploy
```

### Build assets

Now, build the assets to get medias, styles, and scripts :
```sh
$ make build-style
```

### Add networks

Finally, add the following to your dns entries :
```sh
$ sudo gedit /etc/hosts
```

```
# Les Editions Lunaires
127.0.0.1       leseditionslunaires.docker
```
