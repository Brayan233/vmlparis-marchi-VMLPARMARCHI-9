composer install --prefer-dist --no-progress --no-ansi --no-interaction -vvv
chown -R www-data:www-data public
chmod -R 755 public/wp-content/uploads/