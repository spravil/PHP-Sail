<?php
use Sail\Tree;
use Sail\NoSuchRouteException;
use Sail\Sail;

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

$sail = new Sail();

$sail->get('/',
        function  ($request, $response)
        {
            echo 'Welcome to Sail!';
});


$sail->tree('/test', new TestTree());

try {
    $sail->run();
} catch (NoSuchRouteException $e) {
    echo $e->getMessage();
}
