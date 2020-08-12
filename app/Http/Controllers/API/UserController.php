<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserCollection;
use DB;
use File;

class UserController extends Controller
{
    public function index()
    {
        // me-load data outlet yang terkait menggunakan eager loading with() dan mengurutkannnya berdasarkan created_at. operator() adalah sebuah local scope untuk men-filter data user yang role-nya adalah 3. atau bisa diganti dengan where('role', 3). whereIn('role', [1,2,3]) untuk banyak role sekaligus
        $operators = User::with(['outlet'])->orderBy('created_at', 'DESC')->where('role', 3); //operator();
        if(request()->q != ''){
            $operators = $operators->where('name', 'LIKE', '%'.request()->q.'%');
        }
        $operators = $operators->paginate(5);

        return new UserCollection($operators);
    }

    public function store(Request $request) //store untuk user dan operator
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
            'photo' => 'nullable|image',
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);

        DB::beginTransaction();
        try {
            $photo_name = null;
            //APABILA ADA FILE YANG DIKIRIMKAN
            if($request->hasFile('photo')){
                //MAKA FILE TERSEBUT AKAN DISIMPAN KE STORAGE/APP/PUBLIC/COURIERS
                $file = $request->file('photo');
                $photo_name = $request->email . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/users', $photo_name);
            }

            $role = request()->role != null ? $request->role : 3;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'photo' => $photo_name,
                'outlet_id' => $request->outlet_id,
                'role' => $role,
            ]);

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Berhasil menambahkan user baru'], 201); //data $user tidak perlu dikirmkan karena mengandung password. biar privasi terjaga
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $err->getMessage()], 400);
        }
    }

    public function edit($id)
    {
        $user = User::find($id);

        return response()->json(['status' => 'success', 'data' => $user], 200);
    }

    public function update(Request $request, $id) //update khusus operator
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:150',
            'email' => 'required|email',
            'password' => 'nullable|string|min:5',
            'photo' => 'nullable',
            'outlet_id' => 'required|exists:outlets,id',
        ]);

        try {
            $operator = User::find($id); //ambil data operator yg diupdate

            $photo_name = $operator->photo;
            //jika password tidak kosong, maka request password baru. jika kosong, pakai password lama
            $password = $request->password != null ? bcrypt($request->password) : $operator->password;

            if($request->hasFile('photo')){
                $file = $request->file('photo'); //mengambil foto
                $photo_name ? File::delete(storage_path('app/public/users/' . $photo_name)) : ''; //hapus foto lama jika punya
                $photo_name = $request->email . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/users', $photo_name); //memindahkan foto ke folder public/users
            }
            
            //update data di database
            $operator->name = $request->name;
            $operator->email = $request->email;
            $operator->password = $password;
            $operator->photo = $photo_name;
            $operator->outlet_id = $request->outlet_id;
            $operator->save();

            return response()->json(['status' => 'Success', 'message' => 'Berhasil megupdate operator'], 200);
        } catch (\Exception $err) {
            //jika errornya disini, FE nya masih belum bisa menangkap error terjadi di bagian apa. di FE baru bisa menangani error validasi
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        $photo_name = $user->photo;
        if($photo_name){ //jika punya foto, hapus fotonya
            File::delete(storage_path('app/public/users/' . $photo_name));
        }
        $user->delete();

        return response()->json(['status' => 'Success', 'message' => 'Berhasil menghapus user']);
    }

    //menampilkan user yg sedang login
    public function getUserLogin()
    {
        $user = request()->user();
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    //get all user except operator
    public function userLists()
    {
        $users = User::with(['outlet'])->orderBy('created_at', 'DESC')->where('role', '!=', 3);
        if(request()->q != ''){
            $users = $users->where('name', 'LIKE', '%'.request()->q.'%');
        }

        $users = $users->paginate(5);

        return new UserCollection($users);
    }

    public function updateUser(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:150',
            'email' => 'required|email',
            'password' => 'nullable|string|min:5',
            'photo' => 'nullable',
            'outlet_id' => 'nullable|exists:outlets,id',
            'role' => 'required'
        ]);

        try {
            $user = User::find($id); //ambil data user yg diupdate

            $photo_name = $user->photo;
            //jika password tidak kosong, maka request password baru. jika kosong, pakai password lama
            $password = $request->password != null ? bcrypt($request->password) : $user->password;

            if($request->hasFile('photo')){
                $file = $request->file('photo'); //mengambil foto
                $photo_name ? File::delete(storage_path('app/public/users/' . $photo_name)) : ''; //hapus foto lama jika punya
                $photo_name = $request->email . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/users', $photo_name); //memindahkan foto ke folder public/users
            }
            
            //update data di database
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $password;
            $user->photo = $photo_name;
            $user->outlet_id = $request->outlet_id;
            $user->role = $request->role;
            $user->save();

            return response()->json(['status' => 'Success', 'message' => 'Berhasil mengupdate user'], 200);
        } catch (\Exception $err) {
            //jika errornya disini, FE nya masih belum bisa menangkap error terjadi di bagian apa. di FE baru bisa menangani error validasi
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }
}
