<?php
$controller = config('media.controller', '\LoiPham\Media\Controllers\MediaController');
$adminDir = config('media.admin_dir', 'admin');
$prefix = config('media.url_prefix', 'media');
$middleware = array_merge(config('media.middlewares'));
Route::group(['prefix' => $adminDir.'/'.$prefix, 'middleware' => $middleware, 'as' => 'media.',], function () use ($controller) {
    Route::get('/', ['uses' => "$controller@index", 'as' => 'index']);
    Route::post('upload', ['uses' => "$controller@upload", 'as' => 'upload']);
    Route::post('upload-cropped', ['uses' => "$controller@uploadEditedImage", 'as' => 'uploadCropped']);
    Route::post('upload-link', ['uses' => "$controller@uploadLink", 'as' => 'uploadLink']);
    Route::post('files', ['uses' => "$controller@getFiles", 'as' => 'files']);
    Route::post('directories', ['uses' => "$controller@getFolders", 'as' => 'directories']);
    Route::post('new-folder', ['uses' => "$controller@createNewFolder", 'as' => 'new_folder']);
    Route::post('delete-file', ['uses' => "$controller@deleteItem", 'as' => 'delete_file']);
    Route::post('move-file', ['uses' => "$controller@moveItem", 'as' => 'move_file']);
    Route::post('rename-file', ['uses' => "$controller@renameItem", 'as' => 'rename_file']);
    Route::post('change-vis', ['uses' => "$controller@changeItemVisibility", 'as' => 'change_vis']);
    Route::post('lock-file', ['uses' => "$controller@lockItem", 'as' => 'lock_file']);
    Route::get('global-search', ['uses' => "$controller@globalSearch", 'as' => 'global_search']);
    Route::post('folder-download', ['uses' => "$controller@downloadFolder", 'as' => 'folder_download']);
    Route::post('files-download', ['uses' => "$controller@downloadFiles", 'as' => 'files_download']);
});

