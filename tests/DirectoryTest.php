<?php

namespace Tsc\CatStorageSystem;

use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase {
    /**
    * @var Directory
    */
    private $dir;

    protected function setUp()
    {
        parent::setUp();
        $this->dir = new Directory();
    }

    /** @test */
    public function it_returns_self_object_when_name_is_setted()
    {
        $obj = $this->dir->setName('cat.gif');
        $this->assertSame($this->dir, $obj);
    }

    /** @test */
    public function it_returns_self_object_when_created_time_is_setted()
    {
        $obj = $this->dir->setCreatedTime(new \DateTime());
        $this->assertSame($this->dir, $obj);
    }

    /** @test */
    public function it_returns_self_object_when_path_is_setted()
    {
        $obj = $this->dir->setPath('/my/testing/path');;
        $this->assertSame($this->dir, $obj);
    }

    /** @test */
    public function it_returns_expected_name()
    {
        $this->dir->setName('cat_dir');
        $this->assertEquals('cat_dir', $this->dir->getName());
    }

    /** @test */
    public function it_returns_expected_created_time()
    {
        $date = new \DateTime();
        $this->dir->setCreatedTime($date);
        $this->assertEquals($date, $this->dir->getCreatedTime());
    }
    
    /** @test */
    public function it_returns_expected_path()
    {
        $this->dir->setPath('/my/testing/path');
        $this->assertEquals('/my/testing/path', $this->dir->getPath());
    }
}