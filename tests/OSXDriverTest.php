<?php

namespace Tsc\CatStorageSystem;

use PHPUnit\Framework\TestCase;
use Tsc\CatStorageSystem\Factories\DirectoryFactory;
use Tsc\CatStorageSystem\Factories\FileFactory;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDriverInterface;
use Tsc\CatStorageSystem\FSUtils\OSXDriver;


class OSXDriverTest extends TestCase
{

    /**
     * @var FsDriverInterface
     */
    private $driver;

    /**
     * @var string
     */
    private $testFolder = './test_folder';

    protected function setUp()
    {
        parent::setUp();
        $this->driver = new OSXDriver();
        $this->createTmpFolder();
    }


    /** @test */
    public function create_file_fn_can_create_a_file()
    {
        $file = FileFactory::create()
            ->setName('new-cat.gif');

        $dir = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('tmp');

        $this->assertFileNotExists($dir->getPath() . '/' . $dir->getName() . '/' . $file->getName());

        $this->driver->createFile($file, $dir);

        $this->assertFileExists($dir->getPath() . '/' . $dir->getName() . '/' . $file->getName());
    }

    /** @test */
    public function create_file_fn_throws_an_excetion_when_invalid_data()
    {
        $file = FileFactory::create()
            ->setName('new-cat.gif');

        $dir = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('doesntExist');

        $this->expectException(\Exception::class);

        $this->driver->createFile($file, $dir);

    }

    /** @test */
    public function update_file_fn_can_update_a_file()
    {
       touch($this->testFolder . '/tmp/mycat.gif');

        $file = FileFactory::create()
            ->setName('mycat.gif')
            ->setParentDirectory(DirectoryFactory::create()
                ->setPath($this->testFolder)
                ->setName('tmp'));

        $newDate = (new \DateTime())->add(new \DateInterval('P10D'));
        $file->setModifiedTime($newDate);

        $this->driver->updateFile($file);

        $fileInfo = new \SplFileInfo($file->getPath() . '/' . $file->getName());
        $this->assertEquals($newDate->getTimestamp(), $fileInfo->getMTime());
    }


    /** @test */
    public function rename_file_fn_can_rename_a_file()
    {
        touch($this->testFolder . '/tmp/mycat.gif');

        $file = FileFactory::create()
            ->setName('mycat.gif')
            ->setParentDirectory(DirectoryFactory::create()
                ->setPath($this->testFolder)
                ->setName('tmp'));

        $this->assertFileNotExists($this->testFolder . '/tmp/renamed-cat.gif');

        $this->driver->renameFile($file, 'renamed-cat.gif');

        $this->assertFileNotExists($this->testFolder . '/tmp/mycat.gif');
        $this->assertFileExists($this->testFolder . '/tmp/renamed-cat.gif');
    }


    /** @test */
    public function delete_file_fn_can_delete_a_file()
    {
        touch($this->testFolder . '/tmp/mycat.gif');

        $file = FileFactory::create()
        ->setName('mycat.gif')
        ->setParentDirectory(DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('tmp'));

        $this->driver->deleteFile($file);

        $this->assertFileNotExists($this->testFolder . '/tmp/mycat.gif');
    }


    /** @test */
    public function create_root_directory_fn_can_create_a_root_directory()
    {
        $root = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('root');

        $this->assertDirectoryNotExists($this->testFolder . '/root');

        $this->driver->createRootDirectory($root);

        $this->assertDirectoryExists($this->testFolder . '/root');
        $this->assertDirectoryIsWritable($this->testFolder . '/root');
    }


    /** @test */
    public function create_directory_fn_can_create_a_directory()
    {
        mkdir($this->testFolder . '/root');

        $root = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('root');

        $child = DirectoryFactory::create()
            ->setName('child');

        $this->assertDirectoryNotExists($this->testFolder . '/root/child');
        $this->driver->createDirectory($child, $root);
        $this->assertDirectoryExists($this->testFolder . '/root/child');
        $this->assertDirectoryIsWritable($this->testFolder . '/root/child');

    }


    /** @test */
    public function delete_directory_fn_can_delete_a_directory()
    {
        mkdir($this->testFolder . '/removable');
        $root = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('removable');

        $this->assertDirectoryExists($this->testFolder . '/removable');

        $this->driver->deleteDirectory($root);

        $this->assertDirectoryNotExists($this->testFolder . '/removable');
    }


    /** @test */
    public function rename_directory_fn_can_rename_a_directory()
    {
        mkdir($this->testFolder . '/my_dir', 0777, true);

        $dir = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('my_dir');

        $this->assertDirectoryExists($this->testFolder . '/my_dir');
        $this->assertDirectoryNotExists($this->testFolder . '/new_name');

        $this->driver->renameDirectory($dir, 'new_name');

        $this->assertDirectoryNotExists($this->testFolder . '/my_dir');
        $this->assertDirectoryExists($this->testFolder . '/new_name');

    }

    /** @test */
    public function count_directories_fn_returns_count_of_directories_inside_a_given_directory()
    {
        mkdir($this->testFolder . '/root');
        mkdir($this->testFolder . '/root/dir_a');
        mkdir($this->testFolder . '/root/dir_b');
        mkdir($this->testFolder . '/root/dir_c');
        touch($this->testFolder . '/root/fileA');

        $root = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('root');

        $this->assertEquals(3, $this->driver->countDirectories($root));
    }

    /** @test */
    public function count_files_fn_returns_count_of_files_inside_a_given_directory()
    {
        mkdir($this->testFolder . '/root');
        mkdir($this->testFolder . '/root/dir_a');
        mkdir($this->testFolder . '/root/dir_b');
        mkdir($this->testFolder . '/root/dir_c');
        touch($this->testFolder . '/root/fileA');
        touch($this->testFolder . '/root/fileB');

        $root = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('root');

        $this->assertEquals(2, $this->driver->countFiles($root));
    }

    /** @test */
    public function directory_size_fn_returns_the_size_of_a_complete_directory()
    {
        mkdir($this->testFolder . '/root');
        $size = (new \SplFileObject($this->testFolder . '/root/fileA.txt', 'w'))->fwrite('aaa');
        mkdir($this->testFolder . '/root/dir_a');
        $size += (new \SplFileObject($this->testFolder . '/root/dir_a/fileB.txt', 'w'))->fwrite('aaa');
        mkdir($this->testFolder . '/root/dir_a/dir_b');
        $size += (new \SplFileObject($this->testFolder . '/root/dir_a/dir_b/fileC.txt', 'w'))->fwrite('aaa');

        $root = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('root');

        $this->assertEquals($size, $this->driver->directorySize($root));
    }

    /** @test */
    public function list_directories_fn_returns_directories_inside_a_directory()
    {
        mkdir($this->testFolder . '/root');
        mkdir($this->testFolder . '/root/dir_a');
        mkdir($this->testFolder . '/root/dir_b');
        mkdir($this->testFolder . '/root/dir_c');
        mkdir($this->testFolder . '/root/dir_c/other_dir');
        touch($this->testFolder . '/root/fileA');

        $root = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('root');

        $directories = $this->driver->listDirectories($root);
        $this->assertEquals(3, count($directories));
    }

    /** @test */
    public function list_files_fn_returns_files_inside_a_directory()
    {
        mkdir($this->testFolder . '/root');
        mkdir($this->testFolder . '/root/dir_a');
        mkdir($this->testFolder . '/root/dir_b');
        mkdir($this->testFolder . '/root/dir_c');
        touch($this->testFolder . '/root/fileA');
        touch($this->testFolder . '/root/fileB');


        $root = DirectoryFactory::create()
            ->setPath($this->testFolder)
            ->setName('root');

        $files = $this->driver->listFiles($root);
        $this->assertEquals(2, count($files));
    }


    protected function tearDown()
    {
        $this->deleteDirectory($this->testFolder . '/root');

        $this->deleteTmpFolder();
    }


    private function createTmpFolder()
    {
        if (is_dir($this->testFolder)) {
            $this->deleteDirectory($this->testFolder);
        }
        mkdir($this->testFolder);
        mkdir($this->testFolder . '/tmp');
    }

    private function deleteTmpFolder()
    {
        if (is_dir($this->testFolder)) {
            $this->deleteDirectory($this->testFolder);
        }
    }

    private function deleteDirectory($dir)
    {
        system('rm -rf ' . escapeshellarg($dir), $retval);
        return $retval == 0;
    }
}
