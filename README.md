![Logo](https://raw.githubusercontent.com/VitexSoftware/phplib-datamolino/master/project-logo.png "Project Logo")

Datamolino PHP Library
======================

Datamolino motto: Let humans focus on creating value, leave robotic work to robots

[Datamolino](https://www.datamolino.com/) service process invoice bitmap image 
and gives you OCRed result data in web interface or via API https://datamolino.docs.apiary.io/

[![Source Code](http://img.shields.io/badge/source-VitexSoftware/phplib-datamolino-blue.svg?style=flat-square)](https://github.com/VitexSoftware/phplib-datamolino)
[![Latest Version](https://img.shields.io/github/release/VitexSoftware/phplib-datamolino.svg?style=flat-square)](https://github.com/VitexSoftware/phplib-datamolino/releases)
[![Software License](https://img.shields.io/badge/license-GPL-brightgreen.svg?style=flat-square)](https://github.com/VitexSoftware/phplib-datamolino/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/VitexSoftware/phplib-datamolino/master.svg?style=flat-square)](https://travis-ci.org/VitexSoftware/phplib-datamolino)
[![Total Downloads](https://img.shields.io/packagist/dt/vitexsoftware/datamolino.svg?style=flat-square)](https://packagist.org/packages/vitexsoftware/datamolino)
[![Docker pulls](https://img.shields.io/docker/pulls/vitexsoftware/phplib-datamolino.svg)](https://hub.docker.com/r/vitexsoftware/phplib-datamolino/)
[![Latest stable](https://img.shields.io/packagist/v/vitexsoftware/phplib-datamolino.svg?style=flat-square)](https://packagist.org/packages/vitexsoftware/phplib-datamolino)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/246e6c0a-a6e5-41ad-9fe0-0b9d95703056/big.png)](https://insight.sensiolabs.com/projects/4900ce8c-8619-4007-b2d6-0ac830064963)


Installation
============

Download https://github.com/VitexSoftware/phplib-datamolino/archive/master.zip or:

Composer:
---------
    composer require vitexsoftware/datamolino

Linux
-----

For Debian, Ubuntu & friends please use repo:

    wget -O - http://v.s.cz/info@vitexsoftware.cz.gpg.key|sudo apt-key add -
    echo deb http://v.s.cz/ stable main > /etc/apt/sources.list.d/ease.list
    apt update
    apt install php-datamolino

In this case please add this to your app composer.json:

    "require": {
        "phplib-datamolino": "*",
        "datamolino": "*"
    },
    "repositories": [
        {
            "type": "path",
            "url": "/usr/share/php/Ease",
            "options": {
                "symlink": true
            }
        },
        {
            "type": "path",
            "url": "/usr/share/php/Datamolino",
            "options": {
                "symlink": true
            }
        }
    ]



Docker:
-------

To get Docker image:

    docker pull vitexsoftware/phplib-datamolino


Configuration Constants
-----------------------

  * DATAMOLINO_URL        - could be https://beta.datamolino.com/ for testing or https://app.datamolino.com/ for production usage
  * DATAMOLINO_ID         - API ID  - request access keys by sending an email to [info@datamolino.com]
  * DATAMOLINO_SECRET     - API Secret - this code you obtain with API ID
  * DATAMOLINO_USERNAME   - email address used to sign in to datamolino web interface
  * DATAMOLINO_PASSWORD   - password for datamolino web interface


