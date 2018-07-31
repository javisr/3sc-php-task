<?php

namespace Tsc\CatStorageSystem;

use Mockery;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Tsc\CatStorageSystem\Factories\DirectoryFactory;
use Tsc\CatStorageSystem\Factories\FileFactory;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDriverInterface;
use Tsc\CatStorageSystem\FSUtils\OSXDriver;


class FileSystemTest extends TestCase
{

//    /**
//     * @var FsDriverInterface
//     */
    private $mock;

    /**
     * @var string
     */
    private $testFolder = './test_folder';


    protected function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock(FsDriverInterface::class);
    }


    /** @test */
    public function create_file_fn_returns_same_file_instance_filled()
    {

        $file = FileFactory::create()
            ->setName('new-cat.gif');

        $dir = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('tmp');

        $time = time();
        $splFileInfoMock = Mockery::mock(SplFileInfo::class);
        $splFileInfoMock->shouldReceive('getSize')->once()->andReturn(33);
        $splFileInfoMock->shouldReceive('getMTime')->twice()->andReturn($time);

        $this->mock->shouldReceive('createFile')->with($file, $dir)
            ->andReturn($splFileInfoMock);

        $fs = new FileSystem($this->mock);

        $returned = $fs->createFile($file, $dir);
        $this->assertSame($file, $returned);
        $this->assertEquals((new \DateTime())->setTimestamp($time), $returned->getCreatedTime());
        $this->assertEquals(33, $returned->getSize());
        $this->assertEquals((new \DateTime())->setTimestamp($time), $returned->getModifiedTime());
    }


    /** @test */
    public function update_file_fn_returns_same_file_instance()
    {
        $file = FileFactory::create()
            ->setName('new-cat.gif');
        $this->mock->shouldReceive('updateFile')->with($file);

        $fs = new FileSystem($this->mock);

        $returned = $fs->updateFile($file);

        $this->assertSame($file, $returned);
    }
}
