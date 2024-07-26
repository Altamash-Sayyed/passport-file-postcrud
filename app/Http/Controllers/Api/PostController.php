<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    //
    public function create(Request $request)
    {
        //--->set the multipart formdata header in postman

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'file' => 'required|mimes:jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('uploads', 'public'); // Store the file in the 'uploads' directory in the 'public' disk
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'File upload failed!'
            ], 400);
        }

        // Create a new post with the validated data
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'file' => $filePath, // Assuming 'file_path' is a column in your 'posts' table to store the file path
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'post' => $post,
            'status' => true,
            'msg' => 'Post Created Successfully!'
        ], 201);
    }


    public function getall()
    {
        return Post::all();
    }

    public function getone($id)
    {
        $post = Post::find($id);
        if ($post) {
            $fileUrl = asset('storage/' . $post->file_path);

            return response()->json([
                'post' => $post,
                'file_url' => $fileUrl . '/' . $post->file,
                'status' => true,
            ], 200);
        } else {

            return response()->json([
                'msg' => 'post not found!',
                'status' => false,
            ], 400);
        }
    }

    public function update(Request  $request, $id)
    {
        $post = Post::find($id);
        $incomingFields = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'file' => 'required|mimes:jpg,png|max:2048'
        ]);
        if ($incomingFields->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $incomingFields->errors(),
            ], 400);
        }

        if (!$post) {
            return response()->json([
                'status' => false,
                'msg' => 'Post not found!'
            ], 404);
        }

        // Handle file upload if provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('uploads', 'public');

            // Update post with new file path
            $post->file_path = $filePath;
        }

        // Update other fields
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        // Return response
        return response()->json([
            'post' => $post,
            'status' => true,
            'msg' => 'Post Updated Successfully!'
        ]);
    }

    public function delete($id)
    {
        $post = Post::find($id);

        if ($post) {
            $post->delete();
            return response()->json([
                'status' => true,
                'msg' => 'Post deleted Successfully!'
            ]);
        } else {
            return response()->json([

                'status' => false,
                'msg' => 'Post not deleted!'
            ]);
        }
    }
}
