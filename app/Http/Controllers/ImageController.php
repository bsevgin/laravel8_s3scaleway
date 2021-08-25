<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $data = [];

        if ($request->hasFile('image')) {
            try {
                $data['uploadedImage'] = $request->file('image')->store('uploads/images', 'scaleway_s3');

                $data['uploadedUrl'] = Storage::disk('scaleway_s3')->url($data['uploadedImage']);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        return view('index', $data);
    }

    public function show(Request $request, $image)
    {
        return Storage::disk('scaleway_s3')->response('uploads/images/'.$image);
    }

    private function randomFileName()
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 15);
    }

    //
}
