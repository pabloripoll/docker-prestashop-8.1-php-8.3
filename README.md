<div style="width:100%;float:left;clear:both;margin-bottom:50px;">
    <a href="https://github.com/pabloripoll?tab=repositories">
        <img style="width:150px;float:left;" src="https://pabloripoll.com/files/logo-light-100x300.png"/>
    </a>
</div>

# Docker Prestashop 8.1 with PHP FPM 8+

The objective of this repository is having a CaaS [Containers as a Service](https://www.ibm.com/topics/containers-as-a-service) to provide a "ready to use" container with the basic enviroment features to deploy a [Prestashop](https://prestashop.com) application service under a lightweight Linux Alpine image with Nginx server platform and [PHP-FPM](https://www.php.net/manual/en/install.fpm.php) for development stage requirements.

The container configuration is as [Host Network](https://docs.docker.com/network/drivers/host/) on `eth0` as [Bridge network](https://docs.docker.com/network/drivers/bridge/), thus it can be accessed through `localhost:${PORT}` by browsers but to connect with it or this with other services `${HOSTNAME}:${PORT}` will be required.

### Prestashop Docker Container Service

- [Prestashop 8.1.4](https://build.prestashop-project.org/tag/8/)

- [PHP-FPM 8.1](https://www.php.net/releases/8.1/en.php)

- [Nginx 1.24](https://nginx.org/)

- [Alpine Linux 3.19](https://www.alpinelinux.org/)

### Database Container Service

This project does not include a database service for it is intended to connect to a database instance like in a cloud database environment or similar.

To emulate a SQL database service it can be used the following [MariaDB 10.11](https://mariadb.com/kb/en/changes-improvements-in-mariadb-1011/) repository:
- [https://github.com/pabloripoll/docker-mariadb-10.11](https://github.com/pabloripoll/docker-mariadb-10.11)

### Project objetives with Docker

* Built on the lightweight and secure Alpine 3.19 [2024 release](https://www.alpinelinux.org/posts/Alpine-3.19.1-released.html) Linux distribution
* Multi-platform, supporting AMD4, ARMv6, ARMv7, ARM64
* Very small Docker image size (+/-40MB)
* Uses PHP 8.1 as default for the best performance, low CPU usage & memory footprint, but also can be downgraded till PHP 8.0
* Optimized for 100 concurrent users
* Optimized to only use resources when there's traffic (by using PHP-FPM's `on-demand` process manager)
* The services Nginx, PHP-FPM and supervisord run under a project-privileged user to make it more secure
* The logs of all the services are redirected to the output of the Docker container (visible with `docker logs -f <container name>`)
* Follows the KISS principle (Keep It Simple, Stupid) to make it easy to understand and adjust the image to your needs
* Services independency to connect the application to other database allocation

#### Containers on Windows systems

This project has not been tested on Windows OS neither I can use it to test it. So, I cannot bring much support on it.

Anyway, using this repository you will needed to find out your PC IP by login as an `administrator user` to set connection between containers.

```bash
C:\WINDOWS\system32>ipconfig /all

Windows IP Configuration

 Host Name . . . . . . . . . . . . : 191.128.1.41
 Primary Dns Suffix. . . . . . . . : paul.ad.cmu.edu
 Node Type . . . . . . . . . . . . : Peer-Peer
 IP Routing Enabled. . . . . . . . : No
 WINS Proxy Enabled. . . . . . . . : No
 DNS Suffix Search List. . . . . . : scs.ad.cs.cmu.edu
```

Take the first ip listed. Prestashop container will connect with database container using that IP.

#### Containers on Unix based systems

Find out your IP on UNIX systems and take the first IP listed
```bash
$ hostname -I

191.128.1.41 172.17.0.1 172.20.0.1 172.21.0.1
```

## Structure

Directories and main files on a tree architecture description
```
.
│
├── docker
│   └── nginx-php
│       └── docker
│       │   ├── config
│       │   ├── .env
│       │   ├── docker-compose.yml
│       │   └── Dockerfile
│       │
│       └── Makefile
│
├── resources
│   ├── database
│   │   ├── prestashop-init.sql
│   │   └── prestashop-backup.sql
│   │
│   ├── plugin
│   │   ├── dev
│   │   ├── (plugin-version)
│   │   └── (plugin-version).zip
│   │
│   ├── theme
│   │   ├── dev
│   │   ├── (theme-version)
│   │   └── (theme-version).zip
│   │
│   └── prestashop
│       └── (any file or directory required for re-building the app...)
│
├── prestashop
│   └── (application...)
│
├── .env
├── .env.example
└── Makefile
```

## Automation with Makefile

Makefiles are often used to automate the process of building and compiling software on Unix-based systems as Linux and macOS.

*On Windows - I recommend to use Makefile: \
https://stackoverflow.com/questions/2532234/how-to-run-a-makefile-in-windows*

Makefile recipies
```bash
$ make help
usage: make [target]

targets:
Makefile  help                    shows this Makefile help message
Makefile  hostname                shows local machine ip
Makefile  fix-permission          sets project directory permission
Makefile  host-check              shows this project ports availability on local machine
Makefile  prestashop-ssh           enters the application container shell
Makefile  prestashop-set           sets the application PHP enviroment file to build the container
Makefile  prestashop-create        creates the application PHP container from Docker image
Makefile  prestashop-start         starts the application PHP container running
Makefile  prestashop-stop          stops the application PHP container but data will not be destroyed
Makefile  prestashop-destroy       removes the application PHP from Docker network destroying its data and Docker image
Makefile  prestashop-install       installs the application pre-defined version with its dependency packages into container
Makefile  prestashop-update        updates the application dependency packages into container
Makefile  database-install        installs into container database the init sql file from resources/database
Makefile  database-replace        replaces container database with the latest sql backup file from resources/database
Makefile  database-backup         creates / replace a sql backup file from container database in resources/database
Makefile  repo-flush              clears local git repository cache specially to update .gitignore
```

## Service Configuration

Create a [DOTENV](.env) file from [.env.example](.env.example) and setup according to your project requirement the following variables
```
# REMOVE COMMENTS WHEN COPY THIS FILE

# Leave it empty if no need for sudo user to execute docker commands
DOCKER_USER=sudo

# Container data for docker-compose.yml
PROJECT_TITLE="PRESTASHOP"   # <- this name will be prompt for Makefile recipes
PROJECT_ABBR="prestashop"    # <- part of the service image tag - useful if similar services are running

# Prestashop container
PROJECT_HOST="127.0.0.1"                    # <- for this project is not necessary
PROJECT_PORT="8888"                         # <- port access container service on local machine
PROJECT_CAAS="ps-app"                       # <- container as a service name to build service
PROJECT_PATH="../../../prestashop"           # <- path where application is binded from container to local

# Database service container
DB_CAAS="mariadb"                           # <- name of the database docker container service to access by ssh
DB_NAME="mariadb"                           # <- name of the database to copy or replace
DB_ROOT="7c4a8d09ca3762af61e59520943d"      # <- database root password
DB_BACKUP_NAME="prestashop"                    # <- the name of the database backup or copy file
DB_BACKUP_PATH="resources/database"         # <- path where database backup or copy resides
```

Exacute the following command to create the [docker/.env](docker/.env) file, required for building the container
```bash
$ make prestashop-set
WORPRESS docker-compose.yml .env file has been set.
```

Checkout port availability from the set enviroment
```bash
$ make host-check

Checking configuration for WORPRESS container:
WORPRESS > port:8888 is free to use.
```

Checkout local machine IP to set connection between container services using the following makefile recipe if required
```bash
$ make hostname

192.168.1.41
```

## Create the application container service
```bash
$ make worpress-create

PRESTASHOP docker-compose.yml .env file has been set.

[+] Building 54.3s (26/26) FINISHED                                                 docker:default
=> [nginx-php internal] load build definition from Dockerfile                       0.0s
 => => transferring dockerfile: 2.78kB                                              0.0s
 => [nginx-php internal] load metadata for docker.io/library/composer:latest        1.5s
 => [nginx-php internal] load metadata for docker.io/library/php:8.1-fpm-alpine     1.5s
 => [nginx-php internal] load .dockerignore                                         0.0s
 => => transferring context: 108B                                                   0.0s
 => [nginx-php internal] load build context                                         0.0s
 => => transferring context: 8.30kB                                                 0.0s
 => [nginx-php] FROM docker.io/library/composer:latest@sha256:63c0f08ca41370...
...
 => [nginx-php] exporting to image                                                  1.0s
 => => exporting layers                                                             1.0s
 => => writing image sha256:3c99f91a63edd857a0eaa13503c00d500fad57cf5e29ce1d...     0.0s
 => => naming to docker.io/library/ps-app:ps-nginx-php                              0.0s
[+] Running 1/2
 ⠴ Network ps-app_default  Created                                                  0.4s
 ✔ Container ps-app        Started                                                  0.3s
[+] Running 1/0
 ✔ Container ps-app        Running
```

If container service has been built with the application content completed, accessing by browsing [http://localhost:8888/](http://localhost:8888/) will display the successful installation wizard page.

If container has been built without application, the following Makefile recipe will install the application that is configure in [docker/nginx-php/Makefile](docker/nginx-php/Makefile) service
```bash
$ make prestashop-install
```

## Container Information

Running container on Docker
```bash
$ sudo docker ps -a
CONTAINER ID   IMAGE      COMMAND    CREATED      STATUS      PORTS                                             NAMES
ecd27aeae010   pres...    "docker-php-entrypoi…"  1 min...    9000/tcp, 0.0.0.0:8888->80/tcp, :::8888->80/tcp   ps-app
```

Docker image size
```bash
$ sudo docker images
REPOSITORY   TAG           IMAGE ID       CREATED         SIZE
ps-app       pres...       373f6967199b   5 minutes ago   251MB
```

Stats regarding the amount of disk space used by the container
```bash
$ sudo docker system df
TYPE            TOTAL     ACTIVE    SIZE      RECLAIMABLE
Images          1         1         251.4MB   0B (0%)
Containers      1         1         4B        0B (0%)
Local Volumes   1         0         117.9MB   117.9MB (100%)
Build Cache     39        0         10.56kB   10.56kB
```

## Stopping the Container Service

Using the following Makefile recipe stops application from running, keeping database persistance and application files binded without any loss
```bash
$ make prestashop-stop
[+] Stopping 1/1
 ✔ Container ps-app  Stopped                                                    0.5s
```

## Removing the Container Image

To remove application container from Docker network use the following Makefile recipe *(Docker prune commands still needed to be applied manually)*
```bash
$ make prestashop-destroy

[+] Removing 1/0
 ✔ Container ps-app  Removed                                                     0.0s
[+] Running 1/1
 ✔ Network ps-app_default  Removed                                               0.4s
Untagged: ps-app:ps-nginx-php
Deleted: sha256:3c99f91a63edd857a0eaa13503c00d500fad57cf5e29ce1da3210765259c35b1
```

Information on pruning Docker system cache
```bash
$ sudo docker system prune

...
Total reclaimed space: 168.4MB
```

Information on pruning Docker volume cache
```bash
$ sudo docker system prune

...
Total reclaimed space: 0MB
```

## Custom database service usage

In case of using the repository [https://github.com/pabloripoll/docker-mariadb-10.11](https://github.com/pabloripoll/docker-mariadb-10.11) as database service, complete the application mysql database connection params in [prestashop/config/parameters.php](prestashop/config/parameters.php) file.

Use local hostname IP `$ make hostname` to set database host ip.

```php
return array (
  'parameters' =>
    array (
        'database_host' => '192.168.1.41',
        'database_port' => '8880',
        'database_name' => 'mariadb',
        'database_user' => 'mariadb',
        'database_password' => '123456',
        'database_prefix' => 'ps_',
        'database_engine' => 'InnoDB',
        ...
    ),
);
```

*(the above process also can be done automatically by using Composer package DOTENV - not included in this repository)*

### Dumping Database

Every time the containers are built up and running it will be like start from a fresh installation.

You can continue using this repository with the pre-set database executing the command `$ make database-install`

Follow the next recommendations to keep development stages clear and safe.

*On first installation* once the app service is running with basic tables set, I suggest to make a initialization database backup manually, saving as [resources/database/prestashop-backup.sql](resources/database/prestashop-backup.sql) but renaming as [resources/database/prestashop-init.sql](resources/database/prestashop-init.sql) to have that init database for any Docker compose rebuild / restart on next time.

**The following three commands are very useful for *Continue Development*.**

### DB Backup

When the project is already in an advanced development stage, making a backup is recommended to keep lastest database registers.
```bash
$ make database-backup

DATABASE backup has been created.
```

### DB Install

If it is needed to restart the project from base installation step, you can use the init database .sql file to restart at that point in time. Although is not common to use, helps to check and test installation health.
```bash
$ make database-install

DATABASE has been installed.
```

This repository comes with an initialized .sql with a main database user. See [.env.example](.env.example)

### DB Replace

Replace the database set on container with the latest .sql backup into current development stage.
```bash
$ make database-replace

DATABASE has been replaced.
```

#### Notes

- Notice that both files in [resources/database/](resources/database/) have the name that has been set on the main `.env` file to automate processes.

- Remember that on any change in the main `.env` file will be required to execute the following Makefile recipe
```bash
$ make prestashop-set

PRESTASHOP docker-compose.yml .env file has been set.
```