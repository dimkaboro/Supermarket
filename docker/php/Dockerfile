FROM php:8.0-apache

# Установка необходимых расширений PHP
RUN docker-php-ext-install pdo pdo_mysql

# Настройка прав доступа к директории проекта
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Добавление конфигурации Apache для разрешения доступа
RUN echo '<Directory /var/www/html>' > /etc/apache2/conf-available/allow-access.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/conf-available/allow-access.conf && \
    echo '    AllowOverride All' >> /etc/apache2/conf-available/allow-access.conf && \
    echo '    Require all granted' >> /etc/apache2/conf-available/allow-access.conf && \
    echo '</Directory>' >> /etc/apache2/conf-available/allow-access.conf

# Включение конфигурации Apache
RUN a2enconf allow-access

# Перезапуск Apache
CMD ["apache2-foreground"]
