<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/paypal/payment",
     * summary="payment page",
     * description="payment page",
     * tags={"payment"},
     * security={{ "apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="update certificate",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *        required={"request_id","amount"},
     *       @OA\Property(property="request_id", type="string"),
     *       @OA\Property(property="amount", type="string"),
     *      )
     *    )
     *  ),
     * @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     * @OA\Response(
     *      response=201,
     *       description="Created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     * @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     * @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     */
    public function payment(Request $request){
        $request_id=$request->request_id;
        $amount=$request->amount;
        $user_id=auth('api')->user()->id;
        return view('payment',compact('user_id','amount','request_id'));
    }
}
