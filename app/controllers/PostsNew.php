<?php

namespace app\controllers;

class PostsNew extends \vendor\core\base\Controller
{   
    public function indexAction() 
    {
        echo 'Posts::index';
    }

    
    public function testAction () 
    {
        echo 'Posts::test';
    }

    
    public function testPageAction () 
    {
        echo 'Posts::testPage';
    }
    
    
    public function before () 
    {
        echo 'Posts::before';
    }
}
