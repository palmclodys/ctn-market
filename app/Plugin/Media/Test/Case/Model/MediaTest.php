<?php

class MediaTest extends CakeTestCase {

    public $fixtures = array('plugin.media.post', 'plugin.media.media');

    public function setUp() {
        parent::setUp();
        $this->image = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Media' . DS . 'Test' . DS . 'testHelper.png';
        $this->Media = $this->getMockForModel('Media.Media', array('move_uploaded_file'));
        $this->Media->expects($this->any())->method('move_uploaded_file')->will($this->returnCallback('test_move_uploaded_file'));
    }

    public function testAfterFindType() {
        $media = $this->Media->find('first');
        $this->assertEqual('pic', $media['Media']['type']);

        $this->Media->create(array('file' => 'lol.pdf'));
        $this->Media->save();
        $media = $this->Media->read();
        $this->assertEqual('pdf', $media['Media']['type']);
    }

    public function testBeforeSaveUnknowModel() {
        $this->expectException('CakeException');
        $this->Media->save(array(
            'ref' => 'Page',
            'ref_id' => 3,
        ));
    }

    public function testDelete() {
        copy($this->image, WWW_ROOT . 'testHelper.png');
        $this->assertEqual(true, file_exists(WWW_ROOT . 'testHelper.png'));
        $this->Media->delete(1);
        $this->assertEqual(false, file_exists(WWW_ROOT . 'testHelper.png'));
    }

    public function testBeforeSave() {
        $file = array('name' => 'testHelper.png','type' => 'image/png','tmp_name' => $this->image,'error' => (int) 0,'size' => (int) 52085);
        $this->Media->save(array(
            'ref'    => 'Post',
            'ref_id' => 1,
            'file'   => $file
        ));
        $media = $this->Media->read();
        $this->assertEquals('Post', $media['Media']['ref']);
        $this->assertEquals(1, $media['Media']['ref_id']);
        $this->assertEquals(true, file_exists(WWW_ROOT . trim($media['Media']['file'], '/')));
        $this->Media->delete($this->Media->id);
    }

    public function testBeforeSaveWithForbiddenExtension() {
        $file = array('name' => 'testHelper.csv','type' => 'image/png','tmp_name' => $this->image,'error' => (int) 0,'size' => (int) 52085);
        $save =$this->Media->save(array(
            'ref'    => 'Post',
            'ref_id' => 1,
            'file'   => $file
        ));
        $media = $this->Media->read();
        $this->assertEquals(false, $save);
        $this->Media->delete($this->Media->id);
    }


    public function testDuplicate() {
        $file = array('name' => 'testHelper.png','type' => 'image/png','tmp_name' => $this->image,'error' => (int) 0,'size' => (int) 52085);
        $this->Media->save(array(
            'ref'    => 'Post',
            'ref_id' => 1,
            'file'   => $file
        ));
        $id = $this->Media->id;
        $media = $this->Media->read();

        $this->assertEquals('testHelper.png', basename($media['Media']['file']));
        $this->Media->create();
        $media = $this->Media->save(array(
            'ref'    => 'Post',
            'ref_id' => 1,
            'file'   => $file
        ));
        $media = $this->Media->read();
        $this->assertEquals('testHelper-1.png', basename($media['Media']['file']));

        $this->Media->create();
        $media = $this->Media->save(array(
            'ref'    => 'Post',
            'ref_id' => 1,
            'file'   => $file
        ));
        $media = $this->Media->read();
        $this->assertEquals('testHelper-2.png', basename($media['Media']['file']));

        $this->Media->delete($id);
        $this->Media->delete($id+1);
        $this->Media->delete($id+2);
    }

}