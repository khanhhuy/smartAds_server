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
        $relatedItem = Setting::get('process-config.related-item');
        $timeRange = Setting::get('process-config.process_range_months');
        if ($relatedItem === null)
            $relatedItem = false;
        if ($timeRange === null)
            $timeRange = 6;
        return view('system.settings')->with(compact('relatedItem', 'timeRange'));
    }

    public function getTools()
    {
        $names = ['taxonomy', 'stores_areas', 'trans_reprocess'];
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

    public function updateTransactionConfig(Request $request) {

        $inputs = $request->only('time-range');
        $v = Validator::make($inputs, [
            'time-range' => 'required|numeric|min:1',
        ]);

        if ($v->fails())
            return view('errors.list')->withErrors($v->errors());

        if ($request->input('time-range') != '')
            Setting::set('process-config.process_range_months', $request->input('time-range'));
        if ($request->input('related-item') != '')
            Setting::set('process-config.related-item', true);
        else
            Setting::set('process-config.related-item', false);

        Setting::save();

        Flash::success(Lang::get('flash.save_success'));
        return view('partials.fixed-pos-message');
    }
}
