<?php
    require 'view.php';
    $page = new Page();
    $page->start("Stock");

        $nav = new Navbar();
        $code->class="navbar navbar-expand-md navbar-primary bg-light";
        $nav->getLinks();
        $nav->show();

    $page->end();
?>