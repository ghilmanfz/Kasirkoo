<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('member.index');
    }

    public function data()
    {
        $member = Member::orderBy('kode_member')->get();

        return datatables()
            ->of($member)
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_member[]" value="'. $produk->id_member .'">
                ';
            })
            ->addColumn('kode_member', function ($member) {
                return '<span class="label label-success">'. $member->kode_member .'<span>';
            })
            ->addColumn('diskon', function ($member) {
                return $member->diskon_type === 'percent'
                    ? (int) $member->diskon . ' %'                        
                    : 'Rp ' . number_format($member->diskon, 0, ',', '.'); 
            })
            ->addColumn('aksi', function ($member) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('member.update', $member->id_member) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('member.destroy', $member->id_member) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_member'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        'nama'        => 'required|string|max:255',
        'telepon'     => 'required|string|max:50',
        'diskon'      => 'required|numeric|min:0',
        'diskon_type' => 'required|in:percent,nominal',
    ]);

    $last     = Member::latest()->first() ?? new Member();
    $kodeBaru = tambah_nol_didepan(((int)$last->kode_member) + 1, 5);

    Member::create([
        'kode_member' => $kodeBaru,
        'nama'        => $request->nama,
        'telepon'     => $request->telepon,
        'alamat'      => $request->alamat,
        'diskon'      => $request->diskon,
        'diskon_type' => $request->diskon_type,
    ]);

    return response()->json('Data berhasil disimpan', 200);
}
public function update(Request $request, $id)
{
    $request->validate([
        'nama'        => 'required|string|max:255',
        'telepon'     => 'required|string|max:50',
        'diskon'      => 'required|numeric|min:0',
        'diskon_type' => 'required|in:percent,nominal',
    ]);

    Member::findOrFail($id)->update($request->all());
    return response()->json('Data berhasil disimpan', 200);
}

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('MemberController@show called', ['id' => $id, 'user_level' => auth()->user()->level]);
        
        $member = Member::find($id);
        
        if (!$member) {
            Log::warning('Member not found', ['id' => $id]);
            return response()->json(['error' => 'Member not found'], 404);
        }
        
        Log::info('Member found', ['member_id' => $member->id_member, 'member_name' => $member->nama]);

        return response()->json($member);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::find($id);
        $member->delete();

        return response(null, 204);
    }

    public function cetakMember(Request $request)
    {
        $datamember = collect(array());
        foreach ($request->id_member as $id) {
            $member = Member::find($id);
            $datamember[] = $member;
        }

        $datamember = $datamember->chunk(2);
        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('member.cetak', compact('datamember', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('member.pdf');
    }
}
