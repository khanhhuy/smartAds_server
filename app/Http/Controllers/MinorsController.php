<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\CategoryRepositoryInterface;

class MinorsController extends Controller
{	
	private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo) {
        $this->categoryRepo = $categoryRepo;
    }

    public function manage()
    {
    	$tree = $this->categoryRepo->getCategoryTree();
    	
        return view('minors.manage', ['tree' => $this->categoryRepo->getCategoryTree()]);
    }

}
