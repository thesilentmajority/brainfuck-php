# brainfuck-php

---

# Brainfuck PHP Interpreter

This repository contains a PHP-based interpreter for the Brainfuck programming language to test the Just-In-Time (JIT) compilation feature introduced in PHP 8.

## Files in the Repository

1. `brainfuck.php`: The main PHP script that interprets Brainfuck code.
2. `run_tests.sh`: A shell script to run tests.

## Usage

```bash
php brainfuck.php [path_to_brainfuck_file]
```

```bash
./run_tests.sh brainfuck.php [path_to_brainfuck_file]
```

For example:

```bash
./run_tests.sh brainfuck.php pi-digits.bf
```
