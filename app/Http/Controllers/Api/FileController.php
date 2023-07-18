<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 ** path="/api/files",
 *   tags={"file"},
 *   summary="store file",
 *   operationId="store file",
 *   security={{ "apiAuth": {} }},
 * @OA\RequestBody(
 *    required=true,
 *    description="update user info",
 *    @OA\MediaType(
 *      mediaType="multipart/form-data",
 *    @OA\Schema(
 *       @OA\Property(property="file", type="file"),
 *      )
 *    )
 *  ),
 *   @OA\Response(
 *      response=200,
 *       description="Success",
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
 **/
class FileController extends Controller
{
    public function store(Request $request){
        //get file
        $file=$request->file;
        //get file name with extention
        $filenameWithExt = $file->getClientOriginalName();
        //get just file name
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //GET EXTENTION
        $extention = $file->getClientOriginalExtension();
        //file name to store
        $fileNameToStore = $filename . '_' . time() . '.' . $extention;
        //upload image
        $path = $file->storeAs('public/files', $fileNameToStore);
        //link
        $link='storage/files/'.$fileNameToStore;
        return response()->json(['message'=>'File updloaded successfully','link'=>$link]);
    }

}
