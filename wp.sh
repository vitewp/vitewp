#!/bin/bash

if [[ ! -f "./.env" ]]; then
  echo "Missing .env file."
else
  export $(grep -v '^#' .env | xargs)
fi

function db:anonimize() {
  wp option set blog_public 0

  LIST=$(wp user list --field=ID --exclude="1")
  if [ ! -z "$LIST" ]
  then
    wp user delete $LIST --reassign=1
  fi
 
  wp user update $(wp user list --field=ID) --user_pass=test1234 --skip-email
}

function db:export() {
  wp db export db.sql
  gzip db.sql
}

function db:export:prod() {
  wp search-replace $DOMAIN_LOCAL $DOMAIN_PROD --all-tables
  db:export
  wp search-replace $DOMAIN_PROD $DOMAIN_LOCAL --all-tables
}

function db:export:staging() {
  wp search-replace $DOMAIN_LOCAL $DOMAIN_STAGING --all-tables
  db:export
  wp search-replace $DOMAIN_STAGING $DOMAIN_LOCAL --all-tables
}

function db:import() {
  wp db reset --yes
  gzip -d db.sql.gz
  wp db import db.sql
  rm db.sql
}

function db:import:prod() {
  db:import
  wp search-replace $DOMAIN_PROD $DOMAIN_LOCAL --all-tables
  db:anonimize
}

function db:import:staging() {
  db:import
  wp search-replace $DOMAIN_STAGING $DOMAIN_LOCAL --all-tables
}

function wp:config() {
  curl https://raw.githubusercontent.com/przemekhernik/templates/main/wordpress/wp-config-db.php -o wp-config-db.php
}

function wp:env() {
  curl https://raw.githubusercontent.com/przemekhernik/templates/main/bash/.env -o .env
}

function wp:phpcs() {
  curl https://raw.githubusercontent.com/przemekhernik/templates/refs/heads/main/wordpress/phpcs.xml.dist -o phpcs.xml.dist
}

function wp:init() {
  read -p "Are you sure? (y/n) " choice
  if [ "$choice" == "y" ]
  then
    wp core download
    curl https://raw.githubusercontent.com/przemekhernik/templates/main/gitignore/.gitignore.wp -o .gitignore
    curl https://raw.githubusercontent.com/przemekhernik/templates/main/htaccess/.htaccess.wp -o .htaccess
    curl https://raw.githubusercontent.com/przemekhernik/templates/main/htaccess/.htpasswd.wp -o .htpasswd
    curl https://raw.githubusercontent.com/przemekhernik/templates/main/wordpress/phpcs.xml.dist -o phpcs.xml.dist

    wp config create --dbname=$DB_NAME --dbuser=$DB_USER --dbpass=$DB_PASS --dbhost=$DB_HOST:$DB_PORT --dbcharset=utf8mb4 --dbcollate=utf8mb4_general_ci
    wp config set WP_DEBUG true --raw
    wp config set WP_DEBUG_DISPLAY true --raw
    wp config set WP_DEBUG_LOG true --raw
    wp config set WP_ENVIRONMENT_TYPE development
    wp config set DISALLOW_FILE_EDIT true --raw
    wp config set WP_AUTO_UPDATE_CORE false --raw

    wp db reset --yes
    wp core install --url=https://$DOMAIN_LOCAL --title=$TITLE --admin_user=$ADMIN_LOGIN --admin_password=$ADMIN_PASS --admin_email="$ADMIN_EMAIL" --skip-email

    wp user update 1 --first_name=Dev --last_name=Team
    wp post delete 3 --force
    wp post update 2 --post_title=Homepage --post_name=homepage
    wp term update category 1 --name=News --slug=news
    wp plugin delete hello akismet
    wp theme delete twentytwentythree
    wp theme delete twentytwentyfour
    wp comment delete 1 --force
    wp rewrite structure '/%postname%/'
    wp option set blog_public 0
    wp option update show_on_front page
    wp option update page_on_front 2
    wp option update page_for_posts $(wp post create --post_title=Blog --post_name=blog --post_type=page --post_status=publish --post_author=1 --porcelain)
    wp option update default_comment_status closed
    wp option update default_ping_status closed
    wp rewrite flush

    mv wp-content/themes/footmate wp-content/themes/$THEME_SLUG
    cd wp-content/themes/$THEME_SLUG
    composer install && yarn && yarn build
    cd ../../..
    wp theme activate $THEME_SLUG/resources
    wp fm rename --company="$THEME_COMPANY" --name="$THEME_NAME" --slug="$THEME_SLUG" --namespace="$THEME_NAMESPACE" --initials="$THEME_INITIALS" --domain="$DOMAIN_LOCAL"
    cd wp-content/themes/$THEME_SLUG
    composer install && yarn && yarn build
    cd ../../..
    rm -rf .git

    wp post create --post_type=page --post_title="Playground" --page_template="playground" --post_status=publish
    wp post create --post_type=page --post_title="Demo" --page_template="playground" --post_status=publish

    open "https://$DOMAIN_LOCAL"
  fi
}

case $1 in
  "db:anonimize")
    db:anonimize
    ;;

  "db:export")
    db:export
    ;;

  "db:export:prod")
    db:export:prod
    ;;

  "db:export:staging")
    db:export:staging
    ;;

  "db:import")
    db:import
    ;;

  "db:import:prod")
    db:import:prod
    ;;

  "db:import:staging")
    db:import:staging
    ;;

  "wp:config")
    wp:config
    ;;

  "wp:env")
    wp:env
    ;;

  "wp:phpcs")
    wp:phpcs
    ;;
  
  "wp:init")
    wp:init
    ;;
esac
