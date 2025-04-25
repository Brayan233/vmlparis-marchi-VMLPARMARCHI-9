# Use the official WordPress image as a base
FROM wordpress:6.7.2-php8.1-apache

# Install Composer
RUN apt-get update && apt-get install -y \ 
    git \ 
    unzip \ 
    && rm -rf /var/lib/apt/lists/*

# Download Composer installer and install Composer globally
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Download WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar

# Make WP-CLI executable and move to PATH
RUN chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

# Set the working directory
WORKDIR /var/www/html

# Copy existing application files (optional if mounting volume)
# COPY ./public /var/www/html

# Expose port 80
EXPOSE 80 