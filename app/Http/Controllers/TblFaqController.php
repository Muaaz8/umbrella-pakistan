<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateTblFaqRequest;
use App\Http\Requests\UpdateTblFaqRequest;
use App\Repositories\TblFaqRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;

class TblFaqController extends AppBaseController
{
    /** @var  TblFaqRepository */
    private $tblFaqRepository;

    public function __construct(TblFaqRepository $tblFaqRepo)
    {
        $this->tblFaqRepository = $tblFaqRepo;
    }

    /**
     * Display a listing of the TblFaq.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type != 'admin') {
                $tblFaqs = $this->tblFaqRepository->all();
            } else {
                $tblFaqs = $this->tblFaqRepository->allGeneralCategory();
            }

            return view('tbl_faqs.index')
                ->with('tblFaqs', $tblFaqs);
        } else {
            return redirect()->route('login');
        }
    }

    public function website_faqs(){
        $tblFaqs = DB::table('tbl_faq')->orderBy('id', 'desc')->get();
        return view('website_pages.faq', compact('tblFaqs'));
    }

//new_index_for_faqs
    public function faqs(Request $request)
    {

        $tblFaqs = DB::table('tbl_faq')->orderBy('id', 'desc')->get();
        $this->data['tblFaq'] = $tblFaqs;
        return view('dashboard_admin.Add_Items.FAQs.index', $this->data);

        //     $tblFaqs = $this->tblFaqRepository->all();

        // else
        //     $tblFaqs =$this->tblFaqRepository->allGeneralCategory();

    }

    //new_add_new_faqs
    public function create_faqs()
    {
        return view('dashboard_admin.Add_Items.FAQs.Add');
    }
//new_insert_faqs

    public function insert_faqs(Request $request)
    {
        $validated = $request->validate([
            'questions' => 'required|max:255',
            'answers' => 'required',
        ]);
        DB::table('tbl_faq')->insert([
            'question' => $request->questions,
            'answer' => $request->answers,

        ]);

        return redirect()->route('FAQs')->with('success', 'faqs created successfully.');
    }

    public function edit_faqs($id)
    {

        $fetch = DB::table('tbl_faq')->where('id', $id)->first();
        $this->data['tblFaq'] = $fetch;
        return view('dashboard_admin.Add_Items.FAQs.edit', $this->data);
    }
    public function update_faqs($id, Request $request)
    {
        DB::table('tbl_faq')->where('id', $id)->update([
            'question' => $request->input('questions'),
            'answer' => $request->input('answers'),

        ]);
        return redirect()->route('FAQs')->with('success', 'faqs updated successfully.');
    }

    public function view($id)
    {

        $fetch = DB::table('tbl_faq')->where('id', $id)->get();
        $this->data['tblFaq'] = $fetch;
        return view('dashboard_admin.Add_Items.FAQs.view', $this->data);
    }

    /**
     * Show the form for creating a new TblFaq.
     *
     * @return Response
     */
    public function create()
    {
        return view('tbl_faqs.create');
    }

    public function delete($id)
    {
        DB::table('tbl_faq')->where('id', $id)->delete();
        return redirect()->route('FAQs')->with('success', ' Deleted successfully.');
    }

    /**
     * Store a newly created TblFaq in storage.
     *
     * @param CreateTblFaqRequest $request
     *
     * @return Response
     */
    public function store(CreateTblFaqRequest $request)
    {
        $input = $request->all();
        if (Auth::user()->user_type != 'admin') {

            // for test ids
            $input['labtest_ids'] = implode(",", $input['faq_for_test']);
            $input['type'] = 'labtest';
            // dd($input);
        } else {
            $input['type'] = 'general';
        }
        //dd($input);

        $tblFaq = $this->tblFaqRepository->create($input);

        Flash::success('FAQ saved successfully.');

        return redirect(route('faqs.index'));
    }

    /**
     * Display the specified TblFaq.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tblFaq = $this->tblFaqRepository->find($id);

        if (empty($tblFaq)) {
            Flash::error('Tbl Faq not found');

            return redirect(route('faqs.index'));
        }
        if (Auth::user()->user_type != 'admin') {

            $arr = $this->tblFaqRepository->getMultipleTestNamesViaIds($tblFaq->labtest_ids);

            return view('tbl_faqs.show')->with(['tblFaq' => $tblFaq
                , 'test_names' => $arr,
            ]);
        } else {
            return view('tbl_faqs.show')->with(['tblFaq' => $tblFaq]);

        }
    }

    /**
     * Show the form for editing the specified TblFaq.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tblFaq = $this->tblFaqRepository->find($id);

        if (empty($tblFaq)) {
            Flash::error('Tbl Faq not found');

            return redirect(route('faqs.index'));
        }
        if (Auth::user()->user_type != 'admin') {

            if (!empty($tblFaq->labtest_ids)) {
                $arr = $this->tblFaqRepository->getMultipleTestNamesViaIds($tblFaq->labtest_ids);
            } else {
                $arr = json_encode([]);
            }
            return view('tbl_faqs.edit')->with(['tblFaq' => $tblFaq, 'test_names' => $arr]);
        }
        //echo $arr;
        return view('tbl_faqs.edit')->with(['tblFaq' => $tblFaq]);
    }

    /**
     * Update the specified TblFaq in storage.
     *
     * @param int $id
     * @param UpdateTblFaqRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTblFaqRequest $request)
    {
        $tblFaq = $this->tblFaqRepository->find($id);

        if (empty($tblFaq)) {
            Flash::error('Tbl Faq not found');

            return redirect(route('faqs.index'));
        }

        $input = $request->all();
        if (Auth::user()->user_type != 'admin') {

            $input['labtest_ids'] = implode(",", $input['faq_for_test']);
        }
        // dd($request->all());

        $tblFaq = $this->tblFaqRepository->update($input, $id);

        Flash::success('Tbl Faq updated successfully.');

        return redirect(route('faqs.index'));
    }

    /**
     * Remove the specified TblFaq from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tblFaq = $this->tblFaqRepository->find($id);

        if (empty($tblFaq)) {
            Flash::error('Tbl Faq not found');

            return redirect(route('faqs.index'));
        }

        $this->tblFaqRepository->delete($id);

        Flash::success('Tbl Faq deleted successfully.');

        return redirect(route('faqs.index'));
    }
    public function show_all(Request $request)
    {
        $faqs = $this->tblFaqRepository->allGeneralCategory();
        return view('faqs', compact('faqs'));
    }
    public function show_one(Request $request)
    {
        $faq = $this->tblFaqRepository->find($request['id']);
        if (empty($faq)) {
            Flash::error('FAQ not found');

            return redirect(route('faqs.index'));
        }

        return view('tbl_faqs.show_single')->with('faq', $faq);

    }
}
