<?php

namespace App;

class RoadRunnerFileHelper extends \Illuminate\Http\UploadedFile
{
    /**
     * @var \RoadRunnerFileHelper
     */
    private static $instance = null;

    /**
     * Retrieve a file from the request.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return \RoadRunnerFileHelper|null
     */
    private function __construct($file)
    {
        self::$instance = $file;
    }

    /**
     * Retrieve a file from the request.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return \RoadRunnerFileHelper|null
     */
    public static function instance(\Illuminate\Http\UploadedFile $file)
    {
        if (self::$instance == null) {
            self::$instance = new RoadRunnerFileHelper($file);
        }
        return self::$instance;
    }

    protected static $file;

    /**
     * Retrieve a file from the request.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return \RoadRunnerFileHelper|null
     */
    public static function parse(\Illuminate\Http\UploadedFile $file)
    {
        // parent::file($name);
        self::$file = $file;
        return self::instance($file);
    }

    /**
     * Moving a file from the request.
     *
     * @param  string  $path
     * @param  string  $file
     * @return boolean
     */
    public function move(string $directory, ?string $name = NULL)
    {
        if ($name == NULL) {
            $name = self::$file->getClientOriginalName();
        }
        
        $target = $directory . '/'. $name;

        $isCopied = copy(self::$file->getPathName(), $target);
        $isDeleted = unlink(self::$file->getPathName());
        return $isCopied && $isDeleted;
    }
}
