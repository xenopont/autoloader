<?php
namespace project {
    require_once(__DIR__ . '/../autoloader/autoload.php');

    use App\TestObject;
    use Core\Exception;

    try {
        $o = new TestObject();
    } catch (Exception\UndefinedClass $e) {
        $o = null;
        echo 'Exception' . "\n";
    }

    var_dump($o);
    echo 'Done' . "\n";

}