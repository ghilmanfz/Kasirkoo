<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }


public function data()
{
    $user = User::orderByDesc('level')->orderBy('name')->get();

    return DataTables::of($user)
        ->addIndexColumn()
        ->addColumn('level', function ($u) {
            return $u->level == 1 ? 'Admin' : 'Kasir';
        })
        ->addColumn('aksi', function ($u) {
            // tombol cetak / lihat boleh untuk semua
            $tombol = '<button onclick="showDetail('.$u->id.')" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></button>';

            if (auth()->user()->level == 1) {               // hanya admin
                $tombol .=
                    ' <button onclick="editForm(\'/user/'.$u->id.'\')" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></button>'.
                    ' <button onclick="deleteData(\'/user/'.$u->id.'\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
            }

            return $tombol;
        })
        ->rawColumns(['aksi'])  
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
    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
        'level'    => 'required|in:1,2',      
    ]);

    User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => bcrypt($validated['password']),
        'level'    => $validated['level'],   
        'foto'     => '/img/user.jpg',
    ]);

    return response()->json('Data berhasil disimpan', 200);
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password') && $request->password != "") 
            $user->password = bcrypt($request->password);
        $user->update($request->only('name','email','level'));

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();

        return response(null, 204);
    }

    public function profil()
    {
        $profil = auth()->user();
        return view('user.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        
        $user->name = $request->name;
        if ($request->has('password') && $request->password != "") {
            if (Hash::check($request->old_password, $user->password)) {
                if ($request->password == $request->password_confirmation) {
                    $user->password = bcrypt($request->password);
                } else {
                    return response()->json('Konfirmasi password tidak sesuai', 422);
                }
            } else {
                return response()->json('Password lama tidak sesuai', 422);
            }
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            
            $user->foto = "/img/$nama";
            
        }
$user->save();

        return response()->json($user, 200);
    }
}
