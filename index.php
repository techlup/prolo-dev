<?php
require 'view.php';
require 'db.php';
$page=new Page();
$page->start("home");
    $code = new Navbar();
    $code->class="navbar navbar-expand-md navbar-primary bg-light";
    $code->getLinks();
    $code->show();

    $code = new Section();
    $data=[
        array('caption'=> 'Elegant 4 Bedroom Home', 'price'=> '6.5M', 'location'=>'Eldoret'),
        array('caption'=> 'Spacious 6 Bedroom Home', 'price'=> '1.5M', 'location'=>'Nakuru'),
        array('caption'=> 'Classy 4 Mansion Home', 'price'=> '8.5M', 'location'=>'Nairobi')
    ];
    $title = '<h5 class="mb-4 text-center my-3 color-primary col-12"> <span class="code-title">HOUSES FOR SALE</span></h5>';
    $code->class=" row container-fluid bg-white";
    $code->addItem($title);
    for($i=0; $i<3; $i++)
    {
        $code->addItem($page->snip("trending_lands"));
        $code->addData($data[$i]);
    }
    $code->getImages();
    $code->show();
$page->end();
?>