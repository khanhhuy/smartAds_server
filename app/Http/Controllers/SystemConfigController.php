<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Config;
use File;
use Illuminate\Http\Request;
use Lang;
use Laracasts\Flash\Flash;
use Setting;
use Validator;

class SystemConfigController extends Controller
{

    public function getSettings()
    {
        //Todo: get config and show

        return view('system.settings');
    }

    public function getTools()
    {
        $names = ['taxonomy', 'stores_areas'];
        foreach ($names as $name) {
            $lastUpdated[$name] = Setting::get("$name.updated_at");
            $updating[$name] = ($lastUpdated[$name] === 'Updating');
        }
        return view('system.tools')->with(compact('lastUpdated', 'updating', 'names'));
    }

    public function updateThreshold(Request $request)
    {
        $inputs = $request->only(['entrance_value', 'entrance_rate', 'aisle_value', 'aisle_rate']);
        $v = Validator::make($inputs, [
            'entrance_value' => 'required|numeric|min:0',
            'entrance_rate' => 'required|numeric|between:0,100',
            'aisle_value' => 'required|numeric|min:0|max:' . $inputs['entrance_value'],
            'aisle_rate' => 'required|numeric|between:0,100|max:' . $inputs['entrance_rate'],
        ]);

        if ($v->fails()) {
            return view('errors.list')->withErrors($v->errors());
        } else {
            $thresholds = Config::get('promotion-threshold');
            foreach ($inputs as $key => $val) {
                $thresholds[$key] = $val;
            }
            $data = var_export($thresholds, 1);
            File::put(config_path('promotion-threshold.php'), "<?php\n return $data ;");
            Flash::success(Lang::get('flash.save_success'));
            return view('partials.fixed-pos-message');
        }
    }

}
