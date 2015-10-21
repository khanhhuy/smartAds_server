<?php
namespace App\Utils;

use Illuminate\Support\Facades\Request;

/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 10/2/2015
 * Time: 9:41 PM
 */
class Utils
{
    public static function emptyObject($obj)
    {
        return empty((array)$obj);
    }

    public static function setActiveClassManager($condition)
    {
        $condition = 'manager/' . $condition;
        return Request::is($condition) ? 'class="active"' : '';
    }

    public static function formatItem($name, $id)
    {
        return $name . " [" . $id . "]";
    }

    public static function formatTargets($targets)
    {
        if (empty($targets)) {
            return ['All'];
        } else {
            return $targets;
        }

    }

    public static function createThumbnail($id, $ext, $path)
    {
        $thumbnail_width = 110;
        $thumbnail_height = 129;
        $arr_image_details = getimagesize($path);
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];
        if ($original_width > $original_height) {
            $new_width = $thumbnail_width;
            $new_height = intval($original_height * $new_width / $original_width);
            $thumbnail_height = $new_height;
        } else {
            $new_height = $thumbnail_height;
            $new_width = intval($original_width * $new_height / $original_height);
            $thumbnail_width = $new_width;
        }
        $dest_x = intval(($thumbnail_width - $new_width) / 2);
        $dest_y = intval(($thumbnail_height - $new_height) / 2);
        if ($arr_image_details[2] == 1) {
            $imgt = "ImageGIF";
            if (empty($ext)) {
                $ext = 'gif';
            }
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        if ($arr_image_details[2] == 2) {
            $imgt = "ImageJPEG";
            if (empty($ext)) {
                $ext = 'jpg';
            }
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        if ($arr_image_details[2] == 3) {
            $imgt = "ImagePNG";
            if (empty($ext)) {
                $ext = 'png';
            }
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        if ($imgt) {
            $old_image = $imgcreatefrom($path);
            $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
            if ($ext == 'png' || $ext == 'gif') {
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
            }
            imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
            $imgt($new_image, public_path('/img/thumbnails') . "/$id." . $ext);

            imagedestroy($old_image);
            imagedestroy($new_image);
        }
        return $ext;
    }

    public static function createThumbnailFromURL($url, $adsID)
    {
        $temp = Utils::getAdsImagePath($adsID, 'tmp');
        file_put_contents($temp, file_get_contents($url));
        $ext = Utils::createThumbnail($adsID, null, $temp);
        unlink($temp);
        return $ext;
    }

    public static function getAdsImagePath($id, $ext)
    {
        return public_path('img/ads') . "/$id." . $ext;
    }

    public static function getThumbnailPath($id, $ext)
    {
        return public_path('img/thumbnails') . "/$id." . $ext;
    }

    public static function formatStoreAreas($store)
    {
        $a=$store->area;
        $r=$a->name;
        $a=$a->parentArea;
        while (!empty($a)){
            $r.=' - '.$a->name;
            $a=$a->parentArea;
        }
        return $r;
    }
}