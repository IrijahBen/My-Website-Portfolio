<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    // Handle GET request
    public function index(Request $request)
    {
        $limit = $request->input('limit', 12);
        $offset = $request->input('offset', 0);
        
        $query = Project::query();

        if ($request->category && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', $searchTerm)
                  ->orWhere('description', 'LIKE', $searchTerm)
                  // Note: Searching inside a JSON string can be tricky, but this mimics your old logic
                  ->orWhere('tags', 'LIKE', $searchTerm); 
            });
        }

        $total = $query->count();
        $projects = $query->orderBy('created_at', 'desc')
                          ->skip($offset)
                          ->take($limit)
                          ->get();

        return response()->json([
            'success' => true,
            'projects' => $projects,
            'total' => $total,
            'hasMore' => ($offset + $limit) < $total
        ]);
    }

    // Handle POST request
    public function store(Request $request)
    {
        // Validation (Laravel automatically returns a 422 JSON error if this fails)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'author' => 'required|string',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'tags' => 'nullable|string', 
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
            'author_avatar' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Automatically saves to storage/app/public/uploads with a unique name
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $avatarPath = null;
        if ($request->hasFile('author_avatar')) {
            $avatarPath = $request->file('author_avatar')->store('uploads/avatars', 'public');
        }

        // Convert comma-separated string back to array before saving
        $tagsArray = isset($validatedData['tags']) ? explode(',', $validatedData['tags']) : [];

        $project = Project::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? '',
            'category' => $validatedData['category'],
            'image' => $imagePath,
            'author' => $validatedData['author'],
            'author_avatar' => $avatarPath,
            'link' => $validatedData['link'] ?? '',
            'tags' => $tagsArray, // The Model's $casts will JSON encode this automatically
        ]);

        return response()->json(['success' => true, 'id' => $project->id]);
    }

    // Handle PUT request
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Note: PUT requests generally don't handle file uploads well in PHP/Laravel. 
        // If you need to update images later, use POST with a _method=PUT field.
        $tagsArray = $request->has('tags') ? explode(',', $request->tags) : $project->tags;

        $project->update([
            'title' => $request->title ?? $project->title,
            'description' => $request->description ?? $project->description,
            'category' => $request->category ?? $project->category,
            'author' => $request->author ?? $project->author,
            'link' => $request->link ?? $project->link,
            'tags' => $tagsArray,
        ]);

        return response()->json(['success' => true]);
    }

    // Handle DELETE request
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        // Delete images from storage if they exist
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        if ($project->author_avatar) {
            Storage::disk('public')->delete($project->author_avatar);
        }

        $project->delete();

        return response()->json(['success' => true]);
    }
}
