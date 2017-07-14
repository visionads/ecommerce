<?php
namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Validator;
use Session;
use Input;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Printing;

class AdminPrintingController extends Controller{

	public function index()
	{
        $pageTitle = "Printing";

        $data = Printing::orderBy('id', 'DESC')->paginate(30);

        return view('printing.index', ['data' => $data,'pageTitle'=> $pageTitle]);
	}

	public function store(Requests\PrintingRequest $request)
    {
    	$input = $request->all();

    	$input['slug'] = str_slug($input['title']);

    	$printing = Printing::where('slug',$input['slug'])->exists();


        if($printing){
            Session::flash('flash_message_error',' Already Exists.');
        }else{

        	/* Transaction Start Here */
            DB::beginTransaction();
            try {
                Printing::create($input);
                DB::commit();
                Session::flash('flash_message', 'Successfully added!');
            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                Session::flash('flash_message_error', $e->getMessage());
            }

        }

        return redirect()->back();
    }


    public function show($id)
    {
        $pageTitle = 'Details';
        $data = Printing::where('id',$id)->first();
        return view('printing.view', ['data' => $data, 'pageTitle'=> $pageTitle]);
    }

    public function edit($id)
    {
        $data = Printing::where('id',$id)->first();

        return view('printing.update', ['data' => $data]);
    }

    public function update(Requests\PrintingRequest $request, $id)
    {
        $model = Printing::where('id',$id)->first();
        $input = $request->all();
       
       
            DB::beginTransaction();
            try {
                $model->update($input);
                DB::commit();
                Session::flash('flash_message', "Successfully Updated");
            }
            catch ( Exception $e ){
                //If there are any exceptions, rollback the transaction
                DB::rollback();
                Session::flash('flash_message_error', $e->getMessage());
            }
        return redirect()->back();
    }


    public function delete($id)
    {
        try {
            $model = Printing::where('id',$id)->first();
            if ($model->delete()) {
                Session::flash('flash_message', " Successfully Deleted.");
                return redirect()->back();
            }
        } catch(\Exception $e) {
            Session::flash('flash_message_error',$e->getMessage() );
            return redirect()->back();
        }
    }

}