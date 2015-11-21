<?php namespace App\Http\Controllers;

use App\ActiveCustomer;
use App\Commands\ReprocessTrans;
use App\Facades\ProcessTransaction;
use App\Http\Requests;
use App\Repositories\CategoryRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Setting;

class ProcessTransactionController extends Controller {

    private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo) {
        $this->categoryRepo = $categoryRepo;
    }

	public function index(ActiveCustomer $customer)
	{  
        //return Setting::get('trans_reprocess.updated_at');
        //Queue::push(new ReprocessTrans(null, null));
        Setting::set('trans_reprocess.updated_at', 'Now');
        
        // Setting::set('taxonomy.updated_at', Carbon::now()->format('m-d-Y'));
         Setting::save();
        // return Setting::all();
        $fromDate = Carbon::now()->subMonths(6)->toDateString();
		return ProcessTransaction::processAllCustomer($fromDate);

        //return ProcessTransaction::processCustomer($customer);
	}

    public function getListCategories() {
        return view('system.category', ['tree' => $this->categoryRepo->getCategoryTree()]);
    }

    public function selectCategories(Request $request){
        $inputs = $request->all();
        $this->categoryRepo->selectCategory($inputs);
        $r['result']=true;
        return response()->json($r);
    }
    public function getProcessConfig() {
        //Todo: get config and show
        return view('system.process-config');
    }

    public function getAreaConfig() {
        return view('system.area-config');
    }

    public function processAllTrans()
    {   
        if (Setting::get('trans_reprocess.updated_at') !== 'Updating') {
            $timeRange = Setting::get('process-config.process_range_months');
            if ($timeRange === null)
                $timeRange = 6;
            $fromDate = Carbon::now()->subMonths($timeRange)->toDateString();
            Queue::push(new ReprocessTrans($fromDate, null));
            Setting::set('trans_reprocess.updated_at', 'Updating');
            Setting::save();
            return "OK";
        } else {
            return "Updating";
        }
    }

    public function updateProcessStatus()
    {
        return Setting::get('trans_reprocess.updated_at');
    }
}
