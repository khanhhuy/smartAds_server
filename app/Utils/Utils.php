<?php
namespace App\Utils;

use App\Area;
use App\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use App\Facades\Connector;

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
        $a = $store->area;
        $r = $a->name;
        $a = $a->parentArea;
        while (!empty($a)) {
            $r .= ' - ' . $a->name;
            $a = $a->parentArea;
        }
        return $r;
    }

    public static function formatDisplayDate($date)
    {
        if (empty($date)) {
            return null;
        } else {
            return Carbon::parse($date)->format('m-d-Y');
        }
    }

    public static function sortByAreasThenSlice($adsQuery, $dir, $start, $length)
    {
        $allAds = $adsQuery->get();
        foreach ($allAds as $p) {
            $p->cacheTargets = implode(' ', self::formatTargets($p->targets));
        }
        if ($dir == 'asc') {
            $allAds->sort(function ($p1, $p2) {
                return strcmp($p1->cacheTargets, $p2->cacheTargets);
            });
        } else {
            $allAds->sort(function ($p1, $p2) {
                return -strcmp($p1->cacheTargets, $p2->cacheTargets);
            });
        }
        return $allAds->slice($start, $length);
    }
    
    public static function formatRules($rule) {
        if ($rule->isEmpty())
            return "All";
        $rule = $rule[0];
        $displayedRule = '';
        //Age
        if ($rule['to_age'] != 0) 
            $displayedRule .= 'Age:' . $rule['from_age'] . '-' . $rule['to_age'];
        else if ($rule['from_age'] != 0)
            $displayedRule .= $displayedRule . 'Age>' . $rule['from_age'];
        //Family member
        if ($rule['to_family_members'] != 0) 
            $displayedRule .= ', Family:' . $rule['from_family_members'] . '-' . $rule['to_family_members'];
        elseif ($rule['from_family_members'] != 0) 
            $displayedRule .= ', Family>' . $rule['from_family_members'];
        //Gender
        switch ($rule['gender']) {
            case 0:
                $displayedRule .= ', Male';
                break;
            case 1:
                $displayedRule .= ', Female';
                break;
            default:
                break;
        }
        //Jobs
        if ($rule['jobs_desc'] != null) {
            $displayedRule .= ', Jobs:';
            $jobDesc = Connector::getJobDesc();
            $jobs = explode(',', $rule['jobs_desc']);
            foreach ($jobDesc as $job) {
                if (in_array($job['id'], $jobs))
                    $displayedRule .= $job['name'].', ';
            }
        }

        $displayedRule = trim($displayedRule, ', ');
        return empty($displayedRule) ? 'All' : $displayedRule;
    }

    public static function genSearchCell($name)
    {
        $fullName = "search_$name";
        return '<td>
                <input class="form-control table-search" type="text" name="' . $fullName . '" id="' . $fullName . '"/>
        </td>';
    }

    public static function filterByAreas($currentQuery, $text)
    {
        $text = trim($text);
        $includeWholeSystem = false;
        $filteredAreas = [];
        $filteredStores = [];
        $words = preg_split("/ ( |,) /", $text);
        $noResult = false;
        if (empty($words)) {
            return false;
        }

        for ($i = 0; $i < count($words); $i++) {
            $w = $words[$i];
            if (in_array(strtolower($w), ['a', 'l', 'al', 'll', 'all'])) {
                $includeWholeSystem = true;
            }
            $filteredAreas[$i] = Area::whereRaw('name LIKE ?', ["%$w%"])->lists('id');
            $filteredStores[$i] = Store::whereRaw('name LIKE ?', ["%$w%"])->lists('id');
            if (empty($filteredAreas[$i]) && empty($filteredStores[$i])) {
                $noResult = true;
                break;
            }
        }
        if ($noResult) {
            if (count($words) == 1 && $includeWholeSystem) {
                $currentQuery->where('is_whole_system', true);
                return false;
            }
            return true;
        }
        $currentQuery->leftJoin('ads_store', 'ads.id', '=', 'ads_store.ads_id')
            ->leftJoin('ads_area', 'ads.id', '=', 'ads_area.ads_id');
        $currentQuery->where(function ($filtered) use ($words, $filteredStores, $filteredAreas, $includeWholeSystem) {
            for ($i = 0; $i < count($words); $i++) {
                $filtered->where(function ($query) use ($i, $filteredStores, $filteredAreas) {
                    if (!empty($filteredAreas[$i])) {
                        $query->whereIn('area_id', $filteredAreas[$i]);
                        if (!empty($filteredStores[$i])) {
                            $query->orwhereIn('store_id', $filteredStores[$i]);
                        }
                    } else {
                        $query->whereIn('store_id', $filteredStores[$i]);
                    }
                });
            }

            if ($includeWholeSystem) {
                $filtered->orWhere('is_whole_system', true);
            }
        });
    }

    public static function filterByFromToBased($currentQuery, $text, $colName)
    {
        $values = explode(',', $text);
        if (!empty($values[0]) && $values[0] != 'null') {
            $currentQuery->where($colName, '>=', $values[0]);
        }
        if (!empty($values[1]) && $values[1] != 'null') {
            $currentQuery->where($colName, '<=', $values[1]);
        }
    }
}