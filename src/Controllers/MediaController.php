<?php

namespace LoiPham\Media\Controllers;

use App\Http\Controllers\Controller;
use League\Flysystem\Plugin\ListWith;
use LoiPham\Media\Controllers\Moduels\Lock;
use LoiPham\Media\Controllers\Moduels\Move;
use LoiPham\Media\Controllers\Moduels\Utils;
use LoiPham\Media\Controllers\Moduels\Delete;
use LoiPham\Media\Controllers\Moduels\Rename;
use LoiPham\Media\Controllers\Moduels\Upload;
use LoiPham\Media\Controllers\Moduels\Download;
use LoiPham\Media\Controllers\Moduels\NewFolder;
use LoiPham\Media\Controllers\Moduels\GetContent;
use LoiPham\Media\Controllers\Moduels\Visibility;

class MediaController extends Controller
{
    use Utils,
        GetContent,
        Delete,
        Download,
        Lock,
        Move,
        Rename,
        Upload,
        NewFolder,
        Visibility;

    protected $baseUrl;
    protected $db;
    protected $fileChars;
    protected $fileSystem;
    protected $folderChars;
    protected $ignoreFiles;
    protected $LMF;
    protected $GFI;
    protected $sanitizedText;
    protected $storageDisk;
    protected $storageDiskInfo;
    protected $unallowedMimes;

    public function __construct()
    {
        $this->fileSystem     = config('media.storage_disk');
        $this->ignoreFiles    = config('media.ignore_files');
        $this->fileChars      = config('media.allowed_fileNames_chars');
        $this->folderChars    = config('media.allowed_folderNames_chars');
        $this->sanitizedText  = config('media.sanitized_text');
        $this->unallowedMimes = config('media.unallowed_mimes');
        $this->LMF            = config('media.last_modified_format');
        $this->GFI            = config('media.get_folder_info') ?? true;

        $this->storageDisk     = app('filesystem')->disk($this->fileSystem);
        $this->storageDiskInfo = app('config')->get("filesystems.disks.{$this->fileSystem}");
        $this->baseUrl         = $this->storageDisk->url('/');
        $this->db              = app('db')->connection('mysql')->table('file_locked');

        $this->storageDisk->addPlugin(new ListWith());
    }

    /**
     * main view.
     *
     * @return [type] [description]
     */
    public function index()
    {
        return view('media::media');
    }

    public function globalSearch()
    {
        return collect($this->getFolderContent('/', true))->reject(function ($item) { // remove unwanted
            return preg_grep($this->ignoreFiles, [$item['path']]) || $item['type'] == 'dir';
        })->map(function ($file) {
            return $file = [
                'name'                   => $file['basename'],
                'type'                   => $file['mimetype'],
                'path'                   => $this->resolveUrl($file['path']),
                'dir'                    => $file['dirname'] != '' ? $file['dirname'] : '/',
                'last_modified_formated' => $this->getItemTime($file['timestamp']),
            ];
        })->values()->all();
    }
}
