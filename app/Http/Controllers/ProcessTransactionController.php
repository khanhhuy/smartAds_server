<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Category;

use App\ActiveCustomer;
use App\Facades\ProcessTransaction;
use Carbon\Carbon;
use App\Facades\Connector;
use Redirect;
use App\Repositories\CategoryRepositoryInterface;

class ProcessTransactionController extends Controller {

    private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo) {
        $this->categoryRepo = $categoryRepo;
    }

	public function index(ActiveCustomer $customer)
	{
        //$from = Carbon::now()->subMonths(config('process-trans.process_range_months'))->toDateString();
		//return ProcessTransaction::processAllCustomer($from);
        return ProcessTransaction::processCustomer($customer);
	}

    public function getListCategories() {
        return view('system.category', ['tree' => $this->categoryRepo->getCategoryTree()]);
    }

    public function selectCategories(Request $request){
        $inputs = $request->all();
        $this->categoryRepo->selectCategory($inputs);
        return view('system.category', ['tree' => $this->categoryRepo->getCategoryTree()]);
    }

    public function updateTaxonomy() {
        //Todo: Update taxonomy
        return redirect()->action('ProcessTransactionController@getListCategories');
    }

    public function getProcessConfig() {
        //Todo: get config and show
        return view('system.process-config');
    }

    public function getAreaConfig() {
        return view('system.area-config');
    }
}
