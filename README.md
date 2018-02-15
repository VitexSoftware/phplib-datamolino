![Logo](https://raw.githubusercontent.com/VitexSoftware/phplib-datamolino/master/project-logo.png "Project Logo")

Datamolino PHP Library
======================

Datamolino motto: Let humans focus on creating value, leave robotic work to robots

[Datamolino](https://www.datamolino.com/) service process invoice bitmap image 
and gives you OCRed result data in web interface or via API https://datamolino.docs.apiary.io/



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
    aptitude update
    aptitude install php-datamolino

In this case please add this to your app composer.json:

    "require": {
        "ease-framework": "*"
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
  * DATAMOLINO_ID         - API ID  - request access keys by sending an email to [info@datamolino.com](mailto:info@datamolino.com?subject=Datamolino API: Request for application registration&body=Company name: ,Project name: )
  * DATAMOLINO_SECRET     - API Secret - this code you obtain with API ID
  * DATAMOLINO_USERNAME   - email address used to sign in to datamolino web interface
  * DATAMOLINO_PASSWORD   - password for datamolino web interface


