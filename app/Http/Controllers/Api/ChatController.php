<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendResource;
use App\Http\Resources\UserResource;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/chat/getMessages",
     * summary="get all messages",
     * description="get all messages",
     * tags={"chat"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="friend_id",
     * in="query",
     * required=false,
     * description="friend_id",
     * ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     */
    //return all messages
    public function getMessages()
    {
        try {
            $friend_id = $_GET['friend_id'];
            $messages = Chat::where([
                    ['sender_id', auth('api')->user()->id],
                    ['receiver_id', $friend_id]
                ]
            )->orWhere([
                ['receiver_id', auth('api')->user()->id],
                ['sender_id', $friend_id]
            ])->orderBy('created_at', 'desc')->paginate(25);
            return response()->json([$messages], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     * path="/api/chat/getFriends",
     * summary="get all Friends",
     * description="get all Friends",
     * tags={"chat"},
     * security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     */
    public function getFriends()
    {

        try {
            $friends1 = User::whereIn('id', function ($query) {
                $query->from('chats')
                    ->where('sender_id', auth('api')->user()->id)
                    ->select('receiver_id')
                    ->distinct('receiver_id')->get();
            })->whereNotIn('id', [auth('api')->user()->id]);
            $friends2 = User::whereIn('id', function ($query) {
                $query->from('chats')
                    ->where('receiver_id', auth('api')->user()->id)
                    ->select('sender_id')
                    ->distinct('receiver_id')->get();
            })->whereNotIn('id', [auth('api')->user()->id]);

            return response()->json(FriendResource::collection($friends1->union($friends2)->paginate(10))->response()->getData(true), 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
