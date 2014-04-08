<?php
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('MediaHelper', 'Media.View/Helper');

class MediaHelperTest extends CakeTestCase {

    public function setUp() {
        parent::setUp();
        $Controller = new Controller();
        $View = new View($Controller);
        $this->Media = new MediaHelper($View);
        $this->image = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Media' . DS . 'Test' . DS . 'testHelper.png';
    }

    public function startTest($method) {
        copy($this->image, WWW_ROOT . 'testHelper.png');
    }

    public function endTest($method) {
        unlink(WWW_ROOT . 'testHelper.png');
    }

    public function testResizedUrl() {
        $url = $this->Media->resizedUrl('/testHelper.png', 150, 150);
        $this->assertEquals('/testHelper_150x150.jpg', $url);
        $size = getimagesize(WWW_ROOT . 'testHelper_150x150.jpg');
        $this->assertEquals(150, $size[0]);
        $this->assertEquals(150, $size[1]);
        unlink(WWW_ROOT . 'testHelper_150x150.jpg');
    }

    public function testResizedUrlWithWrongExtension() {
        $url = $this->Media->resizedUrl('/testHelper.jpg', 150, 150);
        $this->assertEquals('/testHelper_150x150.jpg', $url);
        $size = getimagesize(WWW_ROOT . 'testHelper_150x150.jpg');
        $this->assertEquals(150, $size[0]);
        $this->assertEquals(150, $size[1]);
        unlink(WWW_ROOT . 'testHelper_150x150.jpg');
    }

    public function testResizedUrlWithMissingImage() {
        $url = $this->Media->resizedUrl('/testlolwithlolXD.jpg', 150, 150);
        $this->assertEquals('/img/error.jpg', $url);
    }
}
