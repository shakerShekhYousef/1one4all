<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Level;
use App\Models\User;
use Error;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    public function index()
    {
        // return view('dashboard');
        return view('pages.listusers');
    }

    public function showuserindex($id)
    {
        try {
            $user = User::find($id);
            if ($user == null)
                return response()->json(['success' => false, 'message' => 'User not found!']);
            return view('pages.showuserinfo', ['trainer' => $user]);
        } catch (\Throwable $th) {
        }
    }

    public function approvetrainerindex($id)
    {
        try {
            $user = User::find($id);
            if ($user == null)
                return response()->json(['success' => false, 'message' => 'User not found!']);
            if (!$user->isTrainer())
                return response()->json(['success' => false, 'message' => 'This user is not trainer!']);
            return view('pages.approvetrainer', ['trainer' => $user]);
        } catch (\Throwable $th) {
        }
    }

    public function deapprovetrainer($id)
    {
        try {
            $user = User::find($id);
            if ($user == null)
                return response()->json(['success' => false, 'message' => 'User not found!']);
            if (!$user->isSpecificTrainer($user)) {
                return response()->json(['success' => false, 'message' => 'This user is not trainer!']);
            }
            $user->approved = false;
            $user->save();
            return response()->json(['success' => true, 'message' => 'Trainer deapproved successfully']);
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }
    public function approvetrainer($id)
    {
        try {
            $user = User::find($id);
            if ($user == null)
                return response()->json(['success' => false, 'message' => 'User not found!']);
            if (!$user->isTrainer())
                return response()->json(['success' => false, 'message' => 'This user is not trainer!']);
            $user->approved = true;
            $user->save();
            return response()->json(['success' => true, 'message' => 'Trainer approved successfully']);
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    public function usersindex()
    {
        return view('pages.listusers');
    }

    public function createadminindex()
    {
        return view('pages.createadmins');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function otheredit($id)
    {
        $user = User::find($id);
        return view('pages.edit', ['user' => $user]);
    }

    public function createuserindex()
    {
        return view('auth.register');
    }

    public function changePassword($id, Request $request)
    {
        $request->validate([
            'password' => 'required|required_with:password_confirmation|same:password_confirmation'
        ]);

        $user = User::find($id);
        if ($user == null)
            return response()->json(['success' => false, 'message' => 'User not found!']);

        // $credentials = $request->only('email', 'password');

        // $currentpass = $user->password;
        // $oldpassword = Hash::make($request->old_password);
        if (!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
            return response()->json(['success' => false, 'message' => 'Old password not correct!']);
        }
        // if ($currentpass != $oldpassword)

        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['success' => true, 'message' => 'User password updated successfully']);
    }

    public function createuser(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'regex:/^\+(?:[0-9] ?){8,14}[0-9]$/',
                'level' => 'required_if:roletype,3',
                'password' => 'required|required_with:password_confirmation|same:password_confirmation'
            ],
            [
                'level.required_if' => 'required when role type is player',
                'age.gt' => ' must be positive value'
            ]
        );

        try {
            if ($request->image != null && $request->image != "undefined") {
                $fileName = rand(0, 10000) . time() . '.' . $request->image->extension();
                // $request->image->move(storage_path('images/'), $fileName);
                $request->image->move(public_path('images/'), $fileName);
            }

            // if trainer without certificate
            if ($request->roletype == 2 && ($request->certificate == null || $request->certificate == "undefined")) {
                return response()->json(['success' => false, 'message' => 'You should select certificate for this trainer']);
            }

            if ($request->certificate != null && $request->certificate != "undefined") {
                $certificatefileName = rand(0, 10000) . time() . '.' . $request->certificate->extension();
                // $request->certificate->move(storage_path('certificates/'), $certificatefileName);
                $request->certificate->move(public_path('certificates/'), $certificatefileName);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'age' => $request->age,
                'password' => Hash::make($request->password),
                'bio' => $request->bio,
                'level' => $request->level,
                'profile_pic' => $request->image != null ? ('images/' . $fileName) : null,
                'role_id' => $request->roletype
            ]);
            if ($request->roletype == 2) {
                $user->approved = false;
                $user->save();

                // add certificate
                if ($request->certificate != null)
                    Certificate::create([
                        'user_id' => $user->id,
                        'image' => $request->image != null ? ('certificates/' . $certificatefileName) : null,
                    ]);
            }
            if ($user != null)
                return response()->json(['success' => true, 'message' => 'User created successfully']);
            else
                return response()->json(['success' => false, 'message' => 'Error while creating user']);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            Log::channel('1one4allcustomelog')->info($ex->getMessage());
        }
    }

    public function getusers(Request $request)
    {
        try {
            $users = User::join('levels', 'users.level_id', '=', 'levels.id')
                ->select('approved', 'users.id as userid', 'users.name', 'email', 'mobile', 'age', 'role_id', 'bio', 'levels.name as levelname')
                ->where('role_id', '!=', 1);
            // $users = User::with('level')->where('role_id', '!=', 1);
            // $users = Level::with('users')->where('role_id', '!=', 1);
            // dd($users->where('role_id', '3')->get());
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('roles', function ($row) {
                    if ($row->role_id == 1)
                        return "Admin";
                    else if ($row->role_id == 2)
                        return "Trainer";
                    else if ($row->role_id == 3)
                        return "Player";
                })
                ->addColumn('user_id', function ($row) {
                    return $row->userid;
                })
                ->addColumn('action', function () {
                    $actionBtn = '<a class="delete btn btn-danger btn-sm">Delete</a>' . " ";
                    $actionBtn .= '<a class="showinfo btn btn-info btn-sm">Show</a>' . " ";
                    $actionBtn .= '<a class="editinfo btn btn-warning btn-sm">Edit</a>' . " ";
                    return $actionBtn;
                })
                ->addColumn('level', function ($row) {
                    return $row->levelname;
                })
                ->addColumn('Approved', function ($row) {
                    $actionBtn = "";
                    if ($row->isTrainer()) {
                        if (!$row->approved)
                            $actionBtn .= '<a class="approve btn btn-success btn-sm">Approve</a>';
                        else
                            $actionBtn .= '<a class="deapprove btn btn-danger btn-sm">X</a>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action', 'Approved'])
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('roletype'))) {
                        $instance->where('role_id', $request->get('roletype'));
                    }

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%")
                                ->orWhere('mobile', 'LIKE', "%$search%")
                                ->orWhere('age', 'LIKE', "%$search%")
                                ->orWhere('levels.name', 'LIKE', "%$search%")
                                ->orWhere('bio', 'LIKE', "%$search%");
                        });
                    }
                })
                ->make(true);
        } catch (Exception $ex) {
            Log::channel('1one4allcustomelog')->info($ex->getMessage());
        }
    }

    public function getuser($id)
    {
        try {
            $user = User::find($id);
            if ($user != null) {
                return response()->json([
                    'success' => true,
                    'message' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'mobile' => $user->mobile,
                        'age' => $user->age,
                        'bio' => $user->bio,
                        'profile_pic' => $user->profile_pic,
                        'level' => $user->level,
                        'created_at' => $user->created_at
                    ]
                ]);
            } else
                return response()->json(['success' => false, 'message' => 'User not found']);
        } catch (Exception $ex) {
            Log::channel('1one4allcustomelog')->info($ex->getMessage());
        }
    }

    public function edituser(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $fileName = null;
            if ($request->image != null && $request->image != "undefined") {
                if ($user->profile_pic != null) {
                    $imagepath = public_path('/') . $user->profile_pic;
                    if (file_exists($imagepath)) {
                        File::delete($imagepath);
                    }
                }
                $fileName = rand(0, 10000) . time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/'), $fileName);
            }
            if ($user != null) {
                $request->name != null ? $user->name = $request->name : null;
                // $request->email != null ? $user->email = $request->email : null;
                $request->mobile != null ? $user->mobile = $request->mobile : null;
                $request->age != null ? $user->age = $request->age : null;
                $request->bio != null ? $user->bio = $request->bio : null;
                $request->image != "undefined" ? ($user->profile_pic = 'images/' . $fileName) : null;
                $request->level != null ? $user->level = $request->level : null;
                $user->save();
                return response()->json(['success' => true, 'message' => 'User information updated successfully']);
            } else
                return response()->json(['success' => false, 'message' => 'User not found!']);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            Log::channel('1one4allcustomelog')->info($ex->getMessage());
        }
    }

    public function deleteuser(Request $request)
    {
        try {
            $user = User::find($request->id);
            if ($user != null) {
                // remove user image
                if ($user->profile_pic != null) {
                    $imagepath = public_path('/') . $user->profile_pic;
                    if (file_exists($imagepath)) {
                        File::delete($imagepath);
                    }
                }

                if ($user->certificate() != null) {
                    // remove certificate image
                    $imagepath = public_path('/') . $user->certificate()->image;
                    if (file_exists($imagepath)) {
                        File::delete($imagepath);
                    }
                    $user->certificate()->delete();
                }
                $user->delete();
                return response()->json(['success' => true, 'message' => 'User have been deleted successfully']);
            } else
                return response()->json(['success' => false, 'message' => 'User not found!']);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            Log::channel('1one4allcustomelog')->info($ex->getMessage());
        }
    }
}
