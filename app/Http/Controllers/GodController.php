<?php

namespace App\Http\Controllers;

use App\Models\God;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GodController extends Controller
{
    public function index()
    {
        $gods = God::all();

        return view('welcome', compact('gods'));
    }

    public function show(God $god)
    {
        return view('gods.show', compact('god'));
    }

    public function create()
    {
        return view('gods.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'badge' => 'required|string|max:255',
            'sphere' => 'required|string|max:255',
            'description' => 'required|string',
            'symbols' => 'nullable|string',
            'parents' => 'nullable|string',
            'siblings' => 'nullable|string',
            'spouse' => 'nullable|string',
            'children' => 'nullable|string',
            'temple' => 'nullable|string',
            'myth' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->saveImage($request->file('image'));
        }

        unset($data['image']);
        $data['recorded_at'] = today()->toDateString();
        God::create($data);

        return redirect()->route('gods.index');
    }

    public function edit(God $god)
    {
        return view('gods.edit', compact('god'));
    }

    public function update(Request $request, God $god)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'badge' => 'required|string|max:255',
            'sphere' => 'required|string|max:255',
            'description' => 'required|string',
            'symbols' => 'nullable|string',
            'parents' => 'nullable|string',
            'siblings' => 'nullable|string',
            'spouse' => 'nullable|string',
            'children' => 'nullable|string',
            'temple' => 'nullable|string',
            'myth' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        if ($request->hasFile('image')) {
                $data['image_path'] = $this->saveImage($request->file('image'));
        }

        unset($data['image']);

        $god->update($data);

        return redirect()->route('gods.show', $god);
    }

    public function destroy(God $god)
    {
        $god->delete();

        return redirect()->route('gods.index');
    }
    
    private function saveImage(UploadedFile $file): string
    {
        $source = imagecreatefromstring(file_get_contents($file->getRealPath()));

        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(960 / $width, 1200 / $height, 1);

        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $image = imagecreatetruecolor($newWidth, $newHeight);
        imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));

        imagecopyresampled(
            $image, $source, 0, 0, 0, 0,
            $newWidth, $newHeight, $width, $height
        );

        $path = 'gods/' . Str::uuid() . '.jpg';

        Storage::disk('public')->makeDirectory('gods');
        imagejpeg($image, Storage::disk('public')->path($path), 85);

        imagedestroy($source);
        imagedestroy($image);

        return $path;
    }

}