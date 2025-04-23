<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokModel;
use App\Models\BarangModel;
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];
    
        $page = (object)[
            'title' => 'Daftar stok yang terdaftar dalam sistem'
        ];
    
        $activeMenu = 'stok';
        $user = UserModel::all(); 
    
        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $stok = StokModel::select('stok_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->with(['barang', 'user']);
    
        if ($request->user_id) {
            $stok->where('user_id', $request->user_id);
        }
    
        // return DataTables::of($stok)
        //     ->addIndexColumn()
        //     ->addColumn('aksi', function ($stok) {
        //         $btn = '<a href="'.url('/stok/' . $stok->stok_id).'" class="btn btn-info btn-sm">Detail</a> ';
        //         $btn .= '<a href="'.url('/stok/' . $stok->stok_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
        //         $btn .= '<form class="d-inline-block" method="POST" action="'. url('/stok/'.$stok->stok_id).'">'
        //             . csrf_field() . method_field('DELETE') .
        //             '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
        //         return $btn;
        //     })

        return DataTables::of($stok)
        ->addIndexColumn() 
        ->addColumn('aksi', function ($stok) {
            $btn = '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })

        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah stok baru'
        ];

        $barang = BarangModel::all();
        $user = UserModel::all(); 
        $activeMenu = 'stok';

        return view('stok.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1',
        ]);

        StokModel::create([
            'barang_id' => $request->barang_id,
            'user_id' => $request->user_id,
            'stok_tanggal' => $request->stok_tanggal,
            'stok_jumlah' => $request->stok_jumlah,
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }

    public function show(string $id)
    {
        $stok = StokModel::with(['barang', 'user'])->find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Stok',
            'list' => ['Home', 'Stok', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail stok'
        ];

        $activeMenu = 'stok';

        return view('stok.show', ['breadcrumb' => $breadcrumb, 'page' => $page,'stok' => $stok, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $stok = StokModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Stok',
            'list' => ['Home', 'Stok', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit stok'
        ];

        $barang = BarangModel::all();
        $user = UserModel::all(); 
        $activeMenu = 'stok';

        return view('stok.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'barang' => $barang, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1',
        ]);

        StokModel::find($id)->update([
            'barang_id' => $request->barang_id,
            'user_id' => $request->user_id,
            'stok_tanggal' => $request->stok_tanggal,
            'stok_jumlah' => $request->stok_jumlah,
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = StokModel::find($id);
        if (!$check) { 
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            StokModel::destroy($id); 

            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('/stok')->with('error', 'Data stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();
        return view('stok.create_ajax')
                    ->with('barang', $barang)
                    ->with('user', $user);
    }

    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id'     => 'required|integer|exists:m_barang,barang_id',
                'user_id'       => 'required|integer|exists:m_user,user_id',
                'stok_tanggal'  => 'required|date',
                'stok_jumlah'   => 'required|integer|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false, 
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), 
                ]);
            }

            StokModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data stok berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.edit_ajax', ['stok'   => $stok, 'barang' => $barang, 'user'   => $user]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id'     => 'required|integer|exists:m_barang,barang_id',
                'user_id'       => 'required|integer|exists:m_user,user_id',
                'stok_tanggal'  => 'required|date',
                'stok_jumlah'   => 'required|integer|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { 
                return response()->json([
                    'status'   => false, // respon json, true: berhasil, false: gagal 
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            $check = StokModel::find($id); 
            if ($check) {
                $check->update($request->all()); 
                return response()->json([
                    'status'  => true,
                    'message' => 'Data stok berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $stok = StokModel::with(['barang', 'user'])->find($id);

        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                $stok->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data stok berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
}