# Building vendor-dir from Composer with a multi-stage build
FROM composer AS vendor
COPY composer.json /app
#COPY composer.lock /app
RUN ["composer", "global", "require", "hirak/prestissimo"]
#RUN ["composer", "install","--ignore-platform-reqs"]
RUN ["composer", "update","--ignore-platform-reqs"]
RUN ls -l /app

# we need apache, php and ubuntu
FROM 1and1internet/ubuntu-16-apache-php-7.0

# Converter basics...
RUN apt-get update && apt-get install -y \
        timidity \
        ffmpeg \
        jq \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Apache configuration for PHP-Slim-Usage
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN chmod 777 /etc/apache2/sites-available/000-default.conf

# copy vendor dir 
COPY --from=vendor /app/vendor /var/www/vendor

# Adds PHP source files for API
COPY lib /var/www/lib
COPY public /var/www/public
RUN chown -R www-data /var/www
RUN chmod -R 777 /var/www

# Env variables for the ubuntu-16-apache-php-7.0 image
ENV DOCUMENT_ROOT public
ENV UID 33

# Do we need a test file?
#RUN wget -O /tmp/elton.mid http://en.midimelody.ru/midi.php?str=%2Fe%2FElton%20John%2FElton%20John%20-%20Song%20for%20a%20Guy.mid