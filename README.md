EvaEngine - a php development engine
=========

EvaEngine is a PHP5 & Zend Framework 2.0 based development engine which is still **under developing**.

Our goal is make EvaEngine to  the best way to create your own custom website or webservice.


Features
---------

###Real Module Based

Every feature is an independent module in EvaEngine. What you need is just pick out features when you need them.

You could combine different modules into various websites : blog, social networking SNS, E-commerce or anything you want.

###Everything is RESTFul

In EvaEngine, everything is designed by RESTFul style, you could build up a  RESTFul webserice easily.

###Plugins and Customize

EvaEngine is complete follow Zend Framework 2.0 code standards, it make developers easy to add features and install 3rd-part modules.

Installation
---------

###Get source code from github

    mkdir evaengine
    cd evaengine
    git clone git://github.com/AlloVince/eva-engine.git
    git submodules update --init

###Create mysql db tables

Create a database "eva" (or any name you want), run sql query file in this database

    evaengine/data/database/eva.sql

###Connect database

Create EvaEngine local config file by:

    evaengine/config/autoload/local.config.php

Write config file as below and change the username/password to yours:

    <?php
    return array(
	    'db' => array(
	        'dsn'            => 'mysql:dbname=eva;hostname=localhost',
	        'username'       => 'dbusername',
	        'password'       => 'dbpassword',
	    ),
		'superadmin' => array(
            'username' => 'root',
        	'password' => '123456',
	    ),
	);

###Bind local domain

Bind a local domain local.evaengine (or anything you want) to path

    evaengine/public

Then visit the local domain http://local.evaengine .

Example
---------

Check a blog made by EvaEngine: http://avnpc.com/

Blog source code is here:

https://github.com/AlloVince/eva-engine/tree/avnpc

Resources
---------

Contact author AlloVince by his [blog](http://avnpc.com/) or email i#av2.me(replace # to @) .


