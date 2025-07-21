<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function createBlog(Request $request)
    {
        try {
            // ✅ Validate input
            $validated = $request->validate([
                'title'   => 'required|string|max:255',
                'message' => 'required|string',
                'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
            ]);

            $imagePath = null;

            // ✅ Handle image upload (if provided)
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // ✅ Store inside public/uploads/blog
                $image->move(public_path('uploads/blog'), $image->getClientOriginalName());

                // Save relative path for DB
                $imagePath = 'uploads/blog/' . $image->getClientOriginalName();
            }

            // ✅ Save blog post in MySQL
            Blog::create([
                'title'   => $validated['title'],
                'message' => $validated['message'],
                'image'   => $imagePath,
            ]);

            return response()->json(['message' => 'Blog created successfully.']);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }







    // ✅ Get all blogs (sorted by latest)
    public function getAllBlogs()
    {
        try {
            $blogs = Blog::orderBy('created_at', 'desc')->get();
            return response()->json($blogs);
        } catch (\Exception $e) {
            \Log::error('❌ listBlogs error: '.$e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }







    // Get Latest News
    public function latestNews(): JsonResponse
    {
        try {
            // ✅ Fetch latest 10 blogs sorted by created_at desc
            $latestBlogs = Blog::orderBy('created_at', 'desc')->take(10)->get();

            return response()->json($latestBlogs);

        } catch (\Exception $e) {
            \Log::error('Error fetching latest news: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to fetch news'], 500);
        }
    }








    // ✅ Get a single blog by ID
    public function getSingleBlog($id)
    {
        try {
            $blog = Blog::find($id); // MySQL (Eloquent) equivalent of findById

            if (!$blog) {
                return response()->json(['error' => 'Blog not found'], 404);
            }

            return response()->json($blog, 200);

        } catch (\Exception $e) {
            \Log::error('❌ getSingleBlog error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }







    // Update blog
    public function updateBlog(Request $request, $id)
    {
        try {
            // ✅ Validate input
            $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // ✅ Find blog
            $blog = Blog::find($id);
            if (!$blog) {
                return response()->json(['message' => 'Blog not found'], 404);
            }

            $title = trim($request->input('title'));

            // ✅ Check if another blog has same title
            $titleExists = Blog::where('id', '!=', $id)
                ->where('title', $title)
                ->exists();

            if ($titleExists) {
                return response()->json(['message' => 'A blog with this title already exists.'], 400);
            }

            // ✅ Update data
            $blog->title = $title;
            $blog->content = $request->input('message');

            // ✅ Handle image upload if present
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Move to public/uploads/blog
                $fileName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/blog'), $fileName);

                // Update blog image path (accessible via /uploads/blog/filename)
                $blog->image = 'uploads/blog/'.$fileName;
            }

            $blog->save();

            return response()->json($blog);

        } catch (\Exception $e) {
            \Log::error('❌ updateBlog error: '.$e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }







    // Delete blog
    public function deleteBlog($id)
    {
        // ✅ Find blog by ID
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found.'], 404);
        }

        // ✅ If blog has an image, delete it from storage
        if (!empty($blog->image)) {
            $filePath = public_path('uploads/blog/' . $blog->image);
            
            if (File::exists($filePath)) {
                File::delete($filePath);
                \Log::info("✅ Blog image deleted: {$filePath}");
            } else {
                \Log::warning("⚠️ Blog image not found: {$filePath}");
            }
        }

        // ✅ Delete blog from DB (MySQL)
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully.'
        ]);
    }






    // Add Comment
    public function addComment(Request $request, $id)
    {
        // ✅ 1. Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'comment' => 'required|string'
        ]);

        // ✅ 2. Find blog by ID
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['error' => 'Blog not found'], 404);
        }

        // ✅ 3. Create a new comment linked to this blog
        $comment = new BlogComment([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'comment' => $validated['comment']
        ]);

        $blog->comments()->save($comment);

        // ✅ 4. Return blog with updated comments
        return response()->json([
            'message' => 'Comment added successfully',
            'blog' => $blog->load('comments') // eager load all comments
        ]);
    }
}
