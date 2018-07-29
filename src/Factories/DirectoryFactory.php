<?php
/**
 * Created by PhpStorm.
 * User: javier
 * Date: 29/07/2018
 * Time: 11:41
 */

namespace Tsc\CatStorageSystem\Factories;


use Tsc\CatStorageSystem\Directory;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;

class DirectoryFactory
{
    /**
     * @return DirectoryInterface
     */
    static function create(){
        return new Directory();
    }
}