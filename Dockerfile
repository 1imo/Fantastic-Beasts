# Use official PHP Apache image
FROM php:8.2-apache

# Enable Apache modules commonly needed for PHP apps and allow .htaccess overrides
RUN a2enmod rewrite headers expires \
    && echo '<Directory /var/www/html>\n    AllowOverride All\n</Directory>' > /etc/apache2/conf-available/htaccess.conf \
    && a2enconf htaccess

# Set working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY . /var/www/html/

# Set proper permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# Expose Apache port
EXPOSE 80

# Base image starts Apache by default
