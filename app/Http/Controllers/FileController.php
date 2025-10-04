<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{
    public function index(): JsonResponse
    {
        $files = File::with('user')->latest()->get();
        return response()->json($files);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'filename' => 'required|string|max:255',
            'filepath' => 'required|string|max:500',
            'filetype' => 'required|in:image,video',
            'filesize' => 'required|integer|min:0'
        ]);

        $file = File::create($validated);
        return response()->json($file, 201);
    }

    public function show(File $file): JsonResponse
    {
        return response()->json($file->load('user'));
    }

    public function update(Request $request, File $file): JsonResponse
    {
        $validated = $request->validate([
            'filename' => 'sometimes|string|max:255',
            'filepath' => 'sometimes|string|max:500',
            'filetype' => 'sometimes|in:image,video',
            'filesize' => 'sometimes|integer|min:0'
        ]);

        $file->update($validated);
        return response()->json($file);
    }

    public function destroy(File $file): JsonResponse
    {
        $file->delete();
        return response()->json(['message' => 'File deleted successfully']);
    }
}