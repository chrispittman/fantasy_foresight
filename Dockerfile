FROM php:8.1-apache

RUN a2enmod rewrite
# change apache webroot from / to /public/
RUN sed -i s/"DocumentRoot \/var\/www\/html"/"DocumentRoot \/var\/www\/html\/public"/ /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install pdo_mysql

ADD web .
RUN chmod a+w -R /var/www/html/bootstrap/cache
RUN chmod a+w -R /var/www/html/storage
