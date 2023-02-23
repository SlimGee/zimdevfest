<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImagesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data['image']->storeAs('images', $data['image']->getClientOriginalName());

        return response()->json([
            'status' => 'success',
            'data' => [
                'name' => $data['image']->getClientOriginalName(),
                'size' => $data['image']->getSize(),
                'last_modified' => Carbon::createFromTimestamp($data['image']->getCTime()),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Filesystem $filesystem): \Illuminate\Http\JsonResponse
    {
        $fileName = $request->get('file');

        chdir(storage_path('app/images'));

        return response()->json([
            'status' => 'success',
            'data' => [
                'size' => $filesystem->size($fileName),
                'name' => $fileName,
                'last_modified' => Carbon::createFromTimestamp($filesystem->lastModified($fileName)),
            ]
        ]);
    }
}
