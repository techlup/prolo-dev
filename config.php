<?php
    $app=array(
        "name" => "KUL",
        "brand" => "KUL-Properties LTD",
        "icon" => "",
        "host"=>"",
        "image_dir"=>"res/img/",
        "color"=>array(
            "primaryDark"=>"#228b22"
        ),
        "css" => array(
            "css/all.css",
            "bootstrap.min.css",
            "theme.css",
            "aos.css",
            "style.css"
        ),
        "js" => array(
            "jquery-3.3.1.slim.min.js",
            "bootstrap.min.js",
            "aos.js",
            "app.js"
        ),
        "links" => array(
            "Default"=>array(
                array("Home", "index.php"),
                array("Lands", "#"),
                array("Housing", "#"),
                array("Events", "#"),
                array("Contacts", "#contact"),
                array("About_Us", "about.php")
            ),

            "Home"=>array(
                array("Home", "index.php"),
                array("About_us", "#")
            )
        )
    );

    $database=array(
        "name" => "kul",
        "username" => "root",
        "password" => "password",
        "server"=>"localhost",
        'type' => 'mysql',

        "tables" => array(
            
            "lands"=>[
                "id" => [
                    "INT",
                    "NOT NULL",
                    "AUTO_INCREMENT",
                    "PRIMARY KEY"
                ],
                "town" => [
                    "VARCHAR(30)",
                    "NOT NULL",
                    "prolo_config"=>[
                        "caption" => "Select town",
                        "type" => "select",
                        "source" => "table",
                        "table"=>"properties",
                        "data" => ["item", "name"]
                    ]
                ],
                "area" => [
                    "VARCHAR(50)",
                    "NOT NULL"
                ],
                "size" => [
                    "VARCHAR(30)",
                    "NOT NULL"
                ],
                "price" => [
                    "VARCHAR(30)",
                    "NOT NULL"
                ],
                "type" => [
                    "VARCHAR(30)",
                    "NOT NULL"
                ],
                "coordinates" => [
                    "VARCHAR(50)",
                    "NOT NULL"
                ],
                "image" => [
                    "VARCHAR(100)",
                    "NOT NULL",
                    "prolo_config"=>[
                        "type"=>"image",
                        "caption"=>"Display image",
                    ]
                ]
                ],

                "properties"=>[
                    "id" => [
                        "INT",
                        "NOT NULL",
                        "AUTO_INCREMENT",
                        "PRIMARY KEY"
                    ],
                    "item" => [
                        "INT",
                        "NOT NULL"
                    ],
                    "name" => [
                        "VARCHAR(30)",
                        "NOT NULL"
                    ]
                ],

                "users"=>[
                    "id" => [
                        "INT",
                        "NOT NULL",
                        "AUTO_INCREMENT",
                        "PRIMARY KEY"
                    ],
                    "age" => [
                        "INT",
                        "NOT NULL"
                    ],
                    "name" => [
                        "VARCHAR(100)",
                        "NOT NULL",                        
                    ],
                    "gender" => [
                        "VARCHAR(100)",
                        "NOT NULL",
                        "prolo_config"=>[
                            "caption" => "Select your gender",
                            "type" => "select",
                            "source" => "raw",
                            "data" => ["Male", "Female"]
                        ]                       
                    ]
                ]

        )
    );

    $admin=array(

        "tables"=>[
            
            "lands" => [
                "table_view"=>['id', 'town', 'area', 'type'],
            ]
        ]
    );
?>