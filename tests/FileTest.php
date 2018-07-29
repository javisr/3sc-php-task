<?php

namespace Tsc\CatStorageSystem;

use PHPUnit\Framework\TestCase;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;

class FileTest extends TestCase {
    /**
    * @var File
    */
    private $file;

    protected function setUp()
    {
        parent::setUp();
        $this->file = new File();
    }

    /** @test */
    public function it_returns_self_object_when_name_is_setted()
    {
        $obj = $this->file->setName('cat.gif');
        $this->assertSame($this->file, $obj);
    }

    /** @test */
    public function it_returns_self_object_when_size_is_setted()
    {
        $obj = $this->file->setSize(33);
        $this->assertSame($this->file, $obj);
    }

    /** @test */
    public function it_returns_self_object_when_created_time_is_setted()
    {
        $obj = $this->file->setCreatedTime(new \DateTime());
        $this->assertSame($this->file, $obj);
    }

    /** @test */
    public function it_returns_self_object_when_modified_time_is_setted()
    {
        $obj = $this->file->setModifiedTime(new \DateTime());
        $this->assertSame($this->file, $obj);
    }

    /** @test */
    public function it_returns_self_object_when_parent_directory_is_setted()
    {
        $dir = $this->createMock(DirectoryInterface::class);
        $obj = $this->file->setParentDirectory($dir);
        $this->assertSame($this->file, $obj);
    }

    /** @test */
    public function it_returns_expected_name()
    {
        $this->file->setName('cat.gif');
        $this->assertEquals('cat.gif', $this->file->getName());
    }

    /** @test */
    public function it_returns_expected_size()
    {
        $this->file->setSize(33);
        $this->assertEquals(33, $this->file->getSize());
    }

    /** @test */
    public function it_returns_expected_created_time()
    {
        $date = new \DateTime();
        $this->file->setCreatedTime($date);
        $this->assertEquals($date, $this->file->getCreatedTime());
    }

    /** @test */
    public function it_returns_expected_modified_time()
    {
        $date = new \DateTime();
        $this->file->setModifiedTime($date);
        $this->assertEquals($date, $this->file->getModifiedTime());
    }

    /** @test */
    public function it_returns_expected_parent_directory()
    {
        $dir = $this->createMock(DirectoryInterface::class);
        $this->file->setParentDirectory($dir);
        $this->assertEquals($dir, $this->file->getParentDirectory());
    }


    /** @test */
    public function it_returns_expected_path()
    {
        $parentDir = (new Directory())
            ->setPath('/my/fake/path')
            ->setName('folderName');

        $this->file->setParentDirectory($parentDir);
        $this->assertEquals('/my/fake/path/folderName', $this->file->getPath());
    }
}