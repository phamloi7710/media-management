<?php

namespace LoiPham\Media\Controllers\Moduels;

use Illuminate\Http\Request;
use LoiPham\Media\Events\MediaFileOpsNotifications;

trait Visibility
{
    /**
     * change file visibility.
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function changeItemVisibility(Request $request)
    {
        $path        = $request->path;
        $result      = [];
        $toBroadCast = [];

        foreach ($request->list as $file) {
            $name      = $file['name'];
            $type      = $file['visibility'] == 'public' ? 'private' : 'public';
            $file_path = !$path ? $name : $this->clearDblSlash("$path/$name");

            if ($this->storageDisk->setVisibility($file_path, $type)) {
                $result[] = [
                    'success'    => true,
                    'name'       => $name,
                    'visibility' => $type,
                    'message'    => trans('media::messages.visibility.success', ['attr' => $name]),
                ];

                $toBroadCast[] = [
                    'name'       => $name,
                    'visibility' => $type,
                ];
            } else {
                $result[] = [
                    'success' => false,
                    'message' => trans('media::messages.visibility.error', ['attr' => $name]),
                ];
            }
        }

        // broadcast
        broadcast(new MediaFileOpsNotifications([
            'op'    => 'visibility',
            'path'  => $path,
            'items' => $toBroadCast,
        ]))->toOthers();

        return response()->json($result);
    }
}
