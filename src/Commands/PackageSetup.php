<?php

namespace LoiPham\Media\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class PackageSetup extends Command
{
    protected $file;
    protected $signature   = 'media:setup';
    protected $description = 'setup package';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->file = app('files');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Đang tiến hành cài đặt media...');
        $this->file->copyDirectory('vendor/loipham/media/public', 'public/app-assets/media');
        $this->call('storage:link');
        $this->info('Quá trình cài đặt media đã hoàn tất');
    }
}
