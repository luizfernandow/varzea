#!/usr/bin/env bash

echo ""
echo "Setting up folder permissions..."
echo "chown -R www-data:www-data storage"
chown -R www-data:www-data storage
echo "chown -R www-data:www-data bootstrap/cache"
chown -R www-data:www-data bootstrap/cache


echo ""
echo "Setup completed!"
echo ""