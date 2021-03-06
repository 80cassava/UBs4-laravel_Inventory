<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\Fakultas;
use App\Exports\JurusanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //pagination
        // numbering

        $data = Jurusan::when($request->search, function($query) use($request){
            $query->where('fakultas.name', 'LIKE', '%'.$request->search.'%')
                ->orWhere('jurusan.name','LIKE', '%'.$request->search.'%');
        })
        ->join('fakultas', 'fakultas.id', '=', 'jurusan.fakultas_id')
        ->select('fakultas.name AS fakultas_name', 'jurusan.*')
        ->orderBy('fakultas.name','asc')
        ->orderBy('jurusan.name','asc')
        ->with('fakultas')->paginate(10);

        return view('jurusan.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Fakultas::all();
        return view('jurusan.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'fakultas_id' => 'required'
        ]);
  
        Jurusan::create($request->all());
        return redirect()->route('jurusan.index')
                        ->with('success','Jurusan created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function show(Jurusan $jurusan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Jurusan::find($id);
        $fakultas = Fakultas::all();
        return view('jurusan.edit',compact('data'))->with('fakultas', $fakultas);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'fakultas_id' => 'required'
        ]);

        $form_data = array(
            'name'      =>  $request->name,
            'fakultas_id'  =>  $request->fakultas_id
        );

        Jurusan::whereId($id)->update($form_data);
        return redirect()->route('jurusan.index')
            ->with('success','Jurusan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Jurusan::whereId($id)->delete();
        return redirect()->route('jurusan.index')
            ->with('success','Jurusan deleted successfully.');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $filename = rand().$file->getClientOriginalName();
        $file->move('excel/jurusan',$filename);
        Excel::import(new JurusanImport, public_path('/excel/jurusan/'.$filename));
        return redirect()->route('jurusan.index')
            ->with('success','Jurusan imported successfully.');
    }

    public function export()
    {
        return Excel::download(new JurusanExport, 'jurusan-'.date("Y-m-d").'.xlsx');
    }
}
