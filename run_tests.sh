#!/bin/bash

# Check if the user provided at least a filename
if [ "$#" -lt 1 ]; then
    echo "Usage: $0 <path_to_php_file> [args...]"
    exit 1
fi

PHP_FILE=$1
shift  # Shift arguments to pass the rest to the PHP script

# Check if the provided file exists
if [ ! -f "$PHP_FILE" ]; then
    echo "Error: File '$PHP_FILE' not found!"
    exit 2
fi

echo "Testing without JIT..."
php -c php-jit-disabled.ini $PHP_FILE "$@"

echo "Testing with JIT..."
php -c php-jit-enabled.ini $PHP_FILE "$@"
