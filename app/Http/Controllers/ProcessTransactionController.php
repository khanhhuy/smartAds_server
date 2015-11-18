<?php namespace App\Http\Controllers;

use App\ActiveCustomer;
use App\Facades\ProcessTransaction;
use App\Http\Requests;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;

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
        $r['result']=true;
        return response()->json($r);
    }
}
