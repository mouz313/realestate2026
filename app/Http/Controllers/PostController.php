<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('excerpt', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } else {
                $query->where('is_published', false);
            }
        }

        $posts = $query->paginate(15)->withQueryString();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:2000',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|max:5120',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        $data['slug'] = $this->uniqueSlug($request->title);
        $data['author_id'] = auth()->id();
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published']
            ? ($request->filled('published_at') ? $request->published_at : now())
            : null;

        if ($request->hasFile('featured_image')) {
            [$blob, $mime] = $this->compressImage($request->file('featured_image'));
            $data['featured_image'] = $blob;
            $data['featured_image_mime'] = $mime;
        }

        Post::create($data);

        toastr()->success('Post created successfully.');

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:2000',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|max:5120',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published']
            ? ($request->filled('published_at') ? $request->published_at : ($post->published_at ?? now()))
            : null;

        if ($request->hasFile('featured_image')) {
            [$blob, $mime] = $this->compressImage($request->file('featured_image'));
            $data['featured_image'] = $blob;
            $data['featured_image_mime'] = $mime;
        }

        $post->update($data);

        toastr()->success('Post updated successfully.');

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        toastr()->success('Post deleted successfully.');

        return redirect()->route('posts.index');
    }

    public function image(Post $post)
    {
        if (! $post->featured_image) {
            abort(404);
        }

        return response($post->featured_image)
            ->header('Content-Type', $post->featured_image_mime ?: 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    protected function compressImage(UploadedFile $file, int $maxWidth = 1280, int $quality = 82): array
    {
        $src = $file->getRealPath();
        $info = @getimagesize($src);

        if (! $info) {
            return [$file->get(), $file->getClientMimeType()];
        }

        $type = $info[2];

        $img = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($src),
            IMAGETYPE_PNG => @imagecreatefrompng($src),
            IMAGETYPE_WEBP => @imagecreatefromwebp($src),
            IMAGETYPE_GIF => @imagecreatefromgif($src),
            default => false,
        };

        if (! $img) {
            return [$file->get(), $file->getClientMimeType()];
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $scale = min(1.0, $maxWidth / max(1, $w));
        $nw = (int) round($w * $scale);
        $nh = (int) round($h * $scale);

        $out = imagecreatetruecolor($nw, $nh);

        if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true)) {
            imagealphablending($out, false);
            imagesavealpha($out, true);
            $trans = imagecolorallocatealpha($out, 0, 0, 0, 127);
            imagefilledrectangle($out, 0, 0, $nw, $nh, $trans);
        }

        imagecopyresampled($out, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);

        ob_start();
        if ($type === IMAGETYPE_PNG) {
            imagepng($out, null, 8);
            $mime = 'image/png';
        } elseif ($type === IMAGETYPE_WEBP) {
            imagewebp($out, null, $quality);
            $mime = 'image/webp';
        } elseif ($type === IMAGETYPE_GIF) {
            imagegif($out);
            $mime = 'image/gif';
        } else {
            imagejpeg($out, null, $quality);
            $mime = 'image/jpeg';
        }
        $blob = ob_get_clean();

        imagedestroy($img);
        imagedestroy($out);

        return [$blob, $mime];
    }

    protected function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title) ?: 'post';
        $original = $slug;
        $i = 1;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }
}
