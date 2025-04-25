<?php

if (!file_exists(__DIR__.'/public.php')) {
    copy(__DIR__.'/public-default.php', __DIR__.'/public.php');
}

return array_merge(
    require __DIR__.'/public.php',
    require __DIR__.'/private.php'
);
