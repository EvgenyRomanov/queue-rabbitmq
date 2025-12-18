#!/usr/bin/env php
<?php

try {
    exit(
        (require __DIR__ . '/../bootstrap/bootstrap_console_app.php')->run()
    );
} catch (Throwable $th) {
    dump($th);
    exit(1);
}
