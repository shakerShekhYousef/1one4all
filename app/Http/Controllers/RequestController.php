<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DataTables;

class RequestController extends Controller
{

    public function getAllRequestsindex()
    {
        $requeststypes = config('app.request_type');
        return view('pages.listrequests', ['requeststypes' => $requeststypes]);
    }

    // Player Requests APIs:
    public function sendRequestToTrainer(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:users,id',
            'player_id' => 'required|exists:users,id',
            'body' => 'required'
        ]);

        try {
            // check if selected trainer of type trainer
            $trainer = User::find($request->trainer_id);
            if ($trainer->role_id != 2)
                return response()->json(['success' => false, 'message' => 'The selected user is not of type trainer']);

            // check if selected player of type player
            $player = User::find($request->player_id);
            if ($player->role_id != 3)
                return response()->json(['success' => false, 'message' => 'The selected user is not of type player']);

            $sendrequest = ModelsRequest::create([
                'name' => $request->name,
                'body' => $request->body,
                'trainer_id' => $request->trainer_id,
                'player_id' => $request->player_id,
                'request_type' => config('app.request_type.Pending')
            ]);

            // create notification    
            $this->createnotification([
                'player_id' => $request->player_id,
                'trainer_id' => $request->trainer_id,
                'request_id' => $sendrequest->id
            ]);

            if ($sendrequest != null) {
                return response()->json(['success' => true, 'message' => 'Request has been sent successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Error during sending request to trainer']);
            }
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    public function getAllRequests(Request $request)
    {
        try {
            $requests = ModelsRequest::query()->orderBy('created_at', 'desc');
            $users = User::all();

            return Datatables::of($requests)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a class="delete btn btn-danger btn-sm">Delete</a>' . " ";
                    // $actionBtn .= '<a class="edit btn btn-info btn-sm">Edit</a>';
                    return $actionBtn;
                })
                ->addColumn('trainer_name', function ($row) use ($users) {
                    $trainer = $users->where('id', $row->trainer_id)->first();
                    if ($trainer != null)
                        return $trainer->name;
                })
                ->addColumn('trainer_email', function ($row) use ($users) {
                    $trainer = $users->where('id', $row->trainer_id)->first();
                    if ($trainer != null)
                        return $trainer->email;
                })
                ->addColumn('player_name', function ($row) use ($users) {
                    $player = $users->where('id', $row->player_id)->first();
                    if ($player != null)
                        return $player->name;
                })
                ->addColumn('player_email', function ($row) use ($users) {
                    $player = $users->where('id', $row->player_id)->first();
                    if ($player != null)
                        return $player->email;
                })
                // ->editColumn('request_type', function ($row) {
                //     // dd(array_keys(config('app.request_type'), $row->request_type));
                //     // $requesttype = array_keys(config('app.request_type'), $row->request_type)[0];
                //     // if ($requesttype != null)
                //     //     return $requesttype;
                // })
                ->filter(function ($instance) use ($request) {
                    if (!is_null($request->get('requesttype'))) {
                        $instance->where('request_type', $request->get('requesttype'));
                    }
                    if (!is_null($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('name', 'LIKE', "%$search%")
                                ->orWhere('body', 'LIKE', "%$search%")
                                ->orWhere('request_type', 'LIKE', "%$search%")
                                ->orWhere('trainer_id', 'LIKE', "%$search%")
                                ->orWhere('player_id', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    // Trainer Requests APIs:
    public function ChangeRequestStatus($requestid, Request $request)
    {
        $requeststatus = config('app.request_type');
        $request->validate([
            'request_type' => 'required|in:' . implode(',', $requeststatus)
        ]);
        try {
            $requestdata = ModelsRequest::find($requestid);
            if ($requestdata == null) {
                return response()->json(['success' => false, 'message' => 'Request not found!']);
            }
            if ($requestdata->request_type == $request->request_type) {
                return response()->json(['success' => false, 'message' => 'The selected request status is the same as current one!']);
            }

            $requestdata->request_type = $request->request_type;
            $requestdata->save();

            // if request type is accepted then send notification
            $this->createnotification([
                'player_id' => $request->player_id,
                'trainer_id' => $request->trainer_id,
                'request_id' => $requestid,
                'notification_type' => config('app.notification_type.Request_Accepted'),
            ]);
            return response()->json(['success' => true, 'message' => 'Request status has been updated successfully']);
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    public function deleterequest(Request $request)
    {
        ModelsRequest::destroy($request->id);
        return response()->json(['success' => true, 'message' => 'Request deleted']);
    }
}
