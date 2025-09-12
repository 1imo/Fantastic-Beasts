# Use official PHP Apache image
FROM php:8.2-apache

# Enable Apache modules commonly needed for PHP apps
RUN a2enmod rewrite headers expires

# Set working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY . /var/www/html/

# Set proper permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# Expose Apache port
EXPOSE 80

# Base image starts Apache by default
