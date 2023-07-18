<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     * path="/api/certificates",
     * summary="create certificate",
     * description="create certificate",
     * tags={"certificate"},
     * security={{ "apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="update certificate",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *        required={"name","image"},
     *       @OA\Property(property="name", type="string", example="lorem ipsum"),
     *       @OA\Property(property="image", type="file"),
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        try {
            if ($request->hasFile('image')) {
                $fileNameTostore = $this->UploadFile($request->image, 'certificates');
            } else {
                $fileNameTostore = null;
            }
            $certificate = Certificate::create([
                'name' => $request->name,
                'image' => $fileNameTostore,
                'user_id' => auth('api')->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return response()->json(['message' => 'Certificate created', 'certificate' => CertificateResource::make($certificate), 'status' => '200 success'], 200);
        } catch
        (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     * path="/api/certificates/{certificate_id}",
     * summary="show certificate",
     * description="show certificate",
     * tags={"certificate"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="certificate_id",
     * in="path",
     * required=true,
     * description="certificate_id",
     * ),
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
    public function show($id)
    {
        try {
            $certificate = Certificate::findOrFail($id);
            return response()->json(CertificateResource::make($certificate));
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
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
     * @OA\Post(
     * path="/api/certificates/update",
     * summary="update certificate",
     * description="update certificate",
     * tags={"certificate"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="certificate_id",
     * in="query",
     * required=true,
     * description="certificate_id",
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="update certificate",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *       @OA\Property(property="name", type="string", example="lorem ipsum"),
     *       @OA\Property(property="image", type="file"),
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
    public function update(Request $request)
    {
        try {
            $id = $_GET['certificate_id'];
            $certificate = Certificate::findOrFail($id);
            if ($request->hasFile('image')) {
                $fileNameTostore = $this->UploadFile($request->image, 'certificates');
            } else {
                $fileNameTostore = $certificate->image;
            }
            $certificate->update([
                'name' => $request->name,
                'image' => $fileNameTostore,
                'user_id' => auth('api')->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return response()->json(['message' => 'Certificate updated', 'certificate' => CertificateResource::make($certificate), 'status' => '200 success'], 200);
        } catch
        (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/certificates/{certificate_id}",
     * summary="delete certificate by id",
     * description="delete certificate by id",
     * tags={"certificate"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="certificate_id",
     * in="path",
     * required=true,
     * description="delete certificate by id",
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
    public function destroy($id)
    {
        try {
            $certificate = Certificate::findOrFail($id);
            $certificate->delete();
            return response()->json(['message' => 'Certificate deleted', 'status' => "200 success"]);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    // Upload image function
    public function UploadFile($file, $path)
    {
        //get file name with extention
        $filenameWithExt = $file->getClientOriginalName();
        //get just file name
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //GET EXTENTION
        $extention = $file->getClientOriginalExtension();
        //file name to store
        $fileNameToStore = $filename . '_' . time() . '.' . $extention;
        //upload image
        $path = $file->storeAs('public/' . $path . '/', $fileNameToStore);
        return $fileNameToStore;
    }
}
