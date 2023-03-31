FROM php:7.4-apache

# Copy application files to container
COPY . /var/www/html/

# Set working directory to Apache document root
WORKDIR /var/www/html/

RUN echo "DirectoryIndex index.html" >> /etc/apache2/apache2.conf

RUN sed -i '/<Directory \/var\/www\/html\/>/,/<\/Directory>/ s/Options Indexes/Options FollowSymLinks/' /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
