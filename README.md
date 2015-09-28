# svlt/back:f3

*This is the backend API for Social Vault, rewritten in F3*

## Installation
Install [Composer](https://getcomposer.org/), then run:

    composer install

Copy the `config-sample.php` file to `config.php` and fill in your database connection information.

## Deployment

### Ubuntu 14.04 LTS

Start by cloning the repo into `/var/www/sv/back` and following the [Installation](#installation) section.

#### nginx/HHVM

    sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0x5a16e7281be7a449
    sudo add-apt-repository "deb http://dl.hhvm.com/ubuntu $(lsb_release -sc) main"
    sudo apt-get update
    sudo apt-get install nginx hhvm

    sudo /usr/share/hhvm/install_fastcgi.sh
    sudo /etc/init.d/hhvm restart
    sudo /etc/init.d/apache restart
    sudo update-rc.d hhvm defaults

Next, create `/etc/nginx/sites-available/sv-back` with these contents:

    server {
        listen 80;
        server_name DOMAIN_HERE;

        root /var/www/sv/back;
        index index.html index.php;

        location / {
            add_header 'Access-Control-Allow-Origin' '*';
            try_files $uri $uri/ /index.php?$args;
        }

        include hhvm.conf;
    }

Finally, restart nginx:

    sudo service nginx restart
