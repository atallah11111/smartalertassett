<!-- <?php
// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;

// class RegistereddUserController extends Controller
// {
//     public function create(){
//         return view('admin.user-create');
//     }

//     public function store(Request $request){
//         $request->validate([
//             'name'=>'required',
//             'email'=>'required|email|unique:users',
//             'password'=>'required|confirmed|min:6',
//         ]);

//         User::create([
//             'name'=>$request->name,
//             'email'=>$request->email,
//             'password'=>Hash::make($request->password),
//             'role'=>'user',
//         ]);

//         return redirect()->route('admin.dashboard')->with('success','User berhasil ditambahkan');
//     }
// } -->
