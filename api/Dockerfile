FROM webdevops/php-apache
WORKDIR /app
COPY . .
RUN composer install
RUN chmod -R 777 /app/storage
EXPOSE 80
