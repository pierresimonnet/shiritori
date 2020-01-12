<?php

/**
 * @param string $path
 * @param array $variables
 */
function render(string $path, array $variables = [])
{
    extract($variables);

    ob_start();
    require("templates/pages/".$path.".html.php");
    $pageContent = ob_get_clean();

    require_once 'templates/layout.html.php';
}

function redirect(string $url): void
{
    header("Location: $url");
    exit();
}
