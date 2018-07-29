<?php

namespace Tsc\CatStorageSystem;

use PHPUnit\Framework\TestCase;

class OSXFileSystemTest extends TestCase
{

    /**
     * @var FileSystemInterface
     */
    private $fs;

    /**
     * @var string
     */
    private $testFolder = './test_folder';

    protected function setUp()
    {
        parent::setUp();
        $this->fs = new OSXFileSystem();

        $this->createTmpFolder();
    }


    /** @test */
    public function it_can_create_a_file()
    {
        $file = (new File())
            ->setName('new-cat.gif');
        $dir = (new Directory())
            ->setPath($this->testFolder)
            ->setName('tmp');

        $this->assertFileNotExists($dir->getPath() . '/' . $dir->getName() . '/' . $file->getName());
        $this->assertSame($file, $this->fs->createFile($file, $dir));
        $this->assertFileExists($dir->getPath() . '/' . $dir->getName() . '/' . $file->getName());

    }

    /** @test */
    public function it_can_update_a_file()
    {
        $file = $this->fs->createFile(
            (new File())
                ->setName('new-cat.gif'),
            (new Directory())
                ->setPath($this->testFolder)
                ->setName('tmp'));

        $newDate = (new \DateTime())->add(new \DateInterval('P10D'));
        $file->setModifiedTime($newDate);
        $this->fs->updateFile($file);

        $fileInfo = new \SplFileInfo($file->getPath() . '/' . $file->getName());

        $this->assertEquals($newDate->getTimestamp(), $fileInfo->getMTime());
    }

    /** @test */
    public function it_can_rename_a_file()
    {
        $file = $this->fs->createFile(
            (new File())
                ->setName('new-cat.gif'),
            (new Directory())
                ->setPath($this->testFolder)
                ->setName('tmp'));

        $this->fs->renameFile($file, 'renamed-cat.gif');

        $this->assertFileNotExists($file->getPath() . '/' . 'new-cat.gif');
        $this->assertFileExists($file->getPath() . '/' . 'renamed-cat.gif');
    }


    /** @test */
    public function it_can_delete_a_file()
    {
        $file = $this->fs->createFile(
            (new File())
                ->setName('new-cat.gif'),
            (new Directory())
                ->setPath($this->testFolder)
                ->setName('tmp'));

        $this->fs->deleteFile($file);

        $this->assertFileNotExists($file->getPath() . '/' . 'new-cat.gif');
    }


    /** @test */
    public function it_can_create_a_root_directory()
    {
        $root = (new Directory())
            ->setPath($this->testFolder)
            ->setName('root');

        $this->assertDirectoryNotExists($this->testFolder . '/root');
        $this->fs->createRootDirectory($root);
        $this->assertDirectoryExists($this->testFolder . '/root');
        $this->assertDirectoryIsWritable($this->testFolder . '/root');
    }


    /** @test */
    public function it_can_create_a_directory()
    {
        mkdir($this->testFolder . '/root');
        $root = (new Directory())
            ->setPath($this->testFolder)
            ->setName('root');

        $child = (new Directory())
            ->setName('child');

        $this->assertDirectoryNotExists($this->testFolder . '/root/child');
        $this->fs->createDirectory($child, $root);
        $this->assertDirectoryExists($this->testFolder . '/root/child');
        $this->assertDirectoryIsWritable($this->testFolder . '/root/child');

    }


    /** @test */
    public function it_can_delete_a_directory()
    {
        $root = (new Directory())
            ->setPath($this->testFolder)
            ->setName('removable');
        $this->fs->createRootDirectory($root);
        $this->assertDirectoryExists($this->testFolder . '/removable');

        $this->fs->deleteDirectory($root);
        $this->assertDirectoryNotExists($this->testFolder . '/removable');
    }


    /** @test */
    public function it_can_rename_a_directory()
    {
        $dir = (new Directory())
            ->setPath($this->testFolder)
            ->setName('my_dir');
        $this->fs->createRootDirectory($dir);

        $this->assertDirectoryExists($this->testFolder . '/my_dir');
        $this->assertDirectoryNotExists($this->testFolder . '/new_name');

        $this->fs->renameDirectory($dir, 'new_name');

        $this->assertDirectoryNotExists($this->testFolder . '/my_dir');
        $this->assertDirectoryExists($this->testFolder . '/new_name');

    }

    /** @test */
    public function it_returns_count_of_directories_inside_a_directory()
    {
        mkdir($this->testFolder . '/root');
        mkdir($this->testFolder . '/root/dir_a');
        mkdir($this->testFolder . '/root/dir_b');
        mkdir($this->testFolder . '/root/dir_c');
        touch($this->testFolder . '/root/fileA');

        $root = (new Directory())
            ->setPath($this->testFolder)
            ->setName('root');

        $this->assertEquals(3, $this->fs->getDirectoryCount($root));
    }

    /** @test */
    public function it_returns_files_count_inside_a_directory()
    {
        mkdir($this->testFolder . '/root');
        mkdir($this->testFolder . '/root/dir_a');
        mkdir($this->testFolder . '/root/dir_b');
        mkdir($this->testFolder . '/root/dir_c');
        touch($this->testFolder . '/root/fileA');
        touch($this->testFolder . '/root/fileB');

        $root = (new Directory())
            ->setPath($this->testFolder)
            ->setName('root');

        $this->assertEquals(2, $this->fs->getFileCount($root));
    }

    /** @test */
    public function it_returns_the_size_of_a_complete_directory()
    {
        mkdir($this->testFolder . '/root');
        $size = (new \SplFileObject($this->testFolder . '/root/fileA.txt', 'w'))->fwrite('aaa');
        mkdir($this->testFolder . '/root/dir_a');
        $size += (new \SplFileObject($this->testFolder . '/root/dir_a/fileB.txt', 'w'))->fwrite('aaa');
        mkdir($this->testFolder . '/root/dir_a/dir_b');
        $size += (new \SplFileObject($this->testFolder . '/root/dir_a/dir_b/fileC.txt', 'w'))->fwrite('aaa');

        $root = (new Directory())
            ->setPath($this->testFolder)
            ->setName('root');

        $this->assertEquals($size, $this->fs->getDirectorySize($root));
    }

    /** @test */
    public function it_returns_directories_inside_a_directory()
    {
        mkdir($this->testFolder . '/root');
        mkdir($this->testFolder . '/root/dir_a');
        mkdir($this->testFolder . '/root/dir_b');
        mkdir($this->testFolder . '/root/dir_c');
        mkdir($this->testFolder . '/root/dir_c/other_dir');
        touch($this->testFolder . '/root/fileA');

        $root = (new Directory())
            ->setPath($this->testFolder)
            ->setName('root');

        $directories = $this->fs->getDirectories($root);
        $this->assertEquals(3, count($directories));
    }

    /** @test */
    public function it_returns_files_inside_a_directory()
    {
        mkdir($this->testFolder . '/root');
        mkdir($this->testFolder . '/root/dir_a');
        mkdir($this->testFolder . '/root/dir_b');
        touch($this->testFolder . '/root/fileA');
        touch($this->testFolder . '/root/fileB');


        $root = (new Directory())
            ->setPath($this->testFolder)
            ->setName('root');

        $files = $this->fs->getFiles($root);
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
