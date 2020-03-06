<?php

namespace App\Http\Controllers;

use App\Http\Resources\Tag as ResourcesTag;
use Illuminate\Http\Request;
use \Spatie\Tags\Tag;



class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Tags found',
            'data' => ResourcesTag::collection($tags),
        ], 200);
    }

    public function store(Request $request)
    {
        $tag = Tag::create(['name' => $request->name]);

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Tag created',
            'data' => new ResourcesTag($tag),
        ], 200);
    }

    public function update(Request $request, Tag $tag)
    {
        $tag->name = $request->name;
        $tag->save();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Tag updated',
            'data' => new ResourcesTag($tag),
        ], 200);
    }

    public function destroy($tag)
    {
        $tag = Tag::findOrFail($tag);
        if ($tag) {
            $tag->delete();
        } else {
            return response()->json(['error' => 'Tag not found']);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Tag deleted',

        ], 200);
    }
}
