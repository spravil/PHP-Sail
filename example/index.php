<?php
use Sail\Tree;
use Sail\NoSuchRouteException;

require '../vendor/autoload.php';

class TestTree extends Tree
{

    public function build ()
    {
        $this->get('/', 
                function  ($request, $response)
                {
                    echo 'You can see me if you request /test';
                });
        
        $this->get('/{id}', 
                function  ($request, $response, $id)
                {
                    echo 'I can also handle variables! request /test/42';
                });
    }
}

class MainTree extends Tree
{

    public function build ()
    {
        $this->get('/', 
                function  ($request, $response)
                {
                    echo 'Welcome to Sail!';
                });
        
        $this->tree('/test', new TestTree());
    }
}

$mainTree = new MainTree();

try {
    $mainTree->run();
} catch (NoSuchRouteException $e) {
    echo $e->getMessage();
}
