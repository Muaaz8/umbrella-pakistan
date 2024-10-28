<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMentalConditionsRequest;
use App\Http\Requests\UpdateMentalConditionsRequest;
use App\Repositories\MentalConditionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class MentalConditionsController extends AppBaseController
{
    /** @var  MentalConditionsRepository */
    private $mentalConditionsRepository;

    public function __construct(MentalConditionsRepository $mentalConditionsRepo)
    {
        $this->mentalConditionsRepository = $mentalConditionsRepo;
    }

    /**
     * Display a listing of the MentalConditions.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $mentalConditions = $this->mentalConditionsRepository->all();

        return view('mental_conditions.index')
            ->with('mentalConditions', $mentalConditions);
    }

    /**
     * Show the form for creating a new MentalConditions.
     *
     * @return Response
     */
    public function create()
    {
        return view('mental_conditions.create');
    }



//add_and_insert mental_condtions

    public function create_condition()
    {
        return view('dashboard_admin.Add_Items.mental_conditions.add');
    }

    public function insert_condition(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'content' => 'required',
        ]);

        DB::table('mental_conditions')->insert([
            'name' => $request->input('name'),
            'content' => $request->input('content'),
        ]);
        return redirect()->route('mental_condition')->with('success',' condition created successfully.');

    }

    public function view_condition(Request $request)
    {
        $condition = DB::table('mental_conditions')->orderBy('id', 'desc')->paginate(10);
        $this->data['mentalConditions'] = $condition;
        // dd($mentalConditions);
        return view('dashboard_admin.Add_Items.mental_conditions.index',$this->data);
    }




    public function edit_condition($id){

        $fetch = DB::table('mental_conditions')->where('id',$id)->first();
        $this->data['mental_conditions'] = $fetch;
        return view('dashboard_admin.Add_Items.mental_conditions.edit',$this->data);
    }

    public function update_condition($id, Request $request){
          $validated = $request->validate([
                'name' => 'required|max:255',
                'content' => 'required',
            ]);

        DB::table('mental_conditions')->where('id', $id)->update([
            'name' => $request->input('name'),
            'content' => $request->input('content'),
        ]);

    return redirect()->route('mental_condition')->with('success','condition updated successfully.');
    }



    public function delete($id){

        $res = DB::table('mental_conditions')->where('id',$id)->delete();
        return redirect()->route('mental_condition')->with('success',' Deleted successfully.');
    }

    public function view($id)
    {

        $fetch = DB::table('mental_conditions')->where('id',$id)->get();
        $this->data['mental_conditions'] = $fetch;
        return view('dashboard_admin.Add_Items.mental_conditions.view',$this->data);
    }



    /**
     * Store a newly created MentalConditions in storage.
     *
     * @param CreateMentalConditionsRequest $request
     *
     * @return Response
     */
    public function store(CreateMentalConditionsRequest $request)
    {
        $input = $request->all();

        $mentalConditions = $this->mentalConditionsRepository->create($input);

        Flash::success('Mental Conditions saved successfully.');

        return redirect(route('mentalConditions.index'));
    }

    /**
     * Display the specified MentalConditions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mentalConditions = $this->mentalConditionsRepository->find($id);

        if (empty($mentalConditions)) {
            Flash::error('Mental Conditions not found');

            return redirect(route('mentalConditions.index'));
        }

        return view('mental_conditions.show')->with('mentalConditions', $mentalConditions);
    }

    /**
     * Show the form for editing the specified MentalConditions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mentalConditions = $this->mentalConditionsRepository->find($id);

        if (empty($mentalConditions)) {
            Flash::error('Mental Conditions not found');

            return redirect(route('mentalConditions.index'));
        }

        return view('mental_conditions.edit')->with('mentalConditions', $mentalConditions);
    }

    /**
     * Update the specified MentalConditions in storage.
     *
     * @param int $id
     * @param UpdateMentalConditionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMentalConditionsRequest $request)
    {
        $mentalConditions = $this->mentalConditionsRepository->find($id);

        if (empty($mentalConditions)) {
            Flash::error('Mental Conditions not found');

            return redirect(route('mentalConditions.index'));
        }

        $mentalConditions = $this->mentalConditionsRepository->update($request->all(), $id);

        Flash::success('Mental Conditions updated successfully.');

        return redirect(route('mentalConditions.index'));
    }

    /**
     * Remove the specified MentalConditions from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mentalConditions = $this->mentalConditionsRepository->find($id);

        if (empty($mentalConditions)) {
            Flash::error('Mental Conditions not found');

            return redirect(route('mentalConditions.index'));
        }

        $this->mentalConditionsRepository->delete($id);

        Flash::success('Mental Conditions deleted successfully.');

        return redirect(route('mentalConditions.index'));
    }
    public function show_all(Request $request)
    {
        $mentalConditions = $this->mentalConditionsRepository->all();
        return view('mental_conditions',compact('mentalConditions'));
    }
    public function condition(Request $request)
    {
        $mentalCondition = $this->mentalConditionsRepository->find($request['id']);
        if (empty($mentalCondition)) {
            Flash::error('Mental Conditions not found');

            return redirect(route('mentalConditions.index'));
        }

        return view('mental_conditions.show_single')->with('mentalCondition', $mentalCondition);

    }
}

