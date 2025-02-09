FROM php:7.4-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the entire project into the container
COPY . .

# Set the DocumentRoot to /var/www/html/public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Enable Apache modules (if needed)
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80