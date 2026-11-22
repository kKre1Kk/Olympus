<?php

namespace App\Http\Controllers;

use App\Models\God;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

class GodController extends Controller
{
    public function index(): View
    {
        $gods = God::with('user')->get();

        return view('welcome', compact('gods'));
    }

    public function show(God $god): View
    {
        if ($god->trashed()) {
            Gate::authorize('admin');
        }

        $god->load('user');

        return view('gods.show', compact('god'));
    }

    public function create(): View
    {
        return view('gods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->validationRules(imageRequired: true));
        $data['image_path'] = $this->saveImage($data['image']);
        unset($data['image']);

        $data['recorded_at'] = today()->toDateString();

        $request->user()->gods()->create($data);

        return redirect()->route('gods.index');
    }

    public function edit(God $god): View
    {
        Gate::authorize('update-god', $god);

        return view('gods.edit', compact('god'));
    }

    public function update(Request $request, God $god): RedirectResponse
    {
        Gate::authorize('update-god', $god);

        $data = $request->validate($this->validationRules(imageRequired: false));
        $oldImagePath = $god->image_path;

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->saveImage($request->file('image'));
        }

        unset($data['image']);

        $god->update($data);

        if ($oldImagePath !== $god->image_path) {
            $this->deleteImageIfUnused($oldImagePath);
        }

        return redirect()->route('gods.show', $god);
    }

    public function destroy(God $god): RedirectResponse
    {
        Gate::authorize('delete-god', $god);

        $god->delete();

        return redirect()->route('gods.index');
    }

    public function trashed(): View
    {
        Gate::authorize('admin');

        $gods = God::onlyTrashed()
            ->with('user')
            ->latest('deleted_at')
            ->get();

        return view('gods.trashed', compact('gods'));
    }

    public function restore(God $god): RedirectResponse
    {
        Gate::authorize('restore-god', $god);

        $god->restore();

        return redirect()->route('gods.trashed');
    }

    public function forceDelete(God $god): RedirectResponse
    {
        Gate::authorize('force-delete-god', $god);

        $imagePath = $god->image_path;
        $deleted = $god->forceDelete();

        if ($deleted) {
            $this->deleteImageIfUnused($imagePath);
        }

        return redirect()->route('gods.trashed');
    }

    private function validationRules(bool $imageRequired): array
    {
        return [
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
            'image' => ($imageRequired ? 'required' : 'nullable')
                .'|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    private function saveImage(UploadedFile $file): string
    {
        if (! function_exists('imagecreatefromstring')) {
            throw new RuntimeException(
                'Для обработки изображений необходимо включить расширение PHP GD.'
            );
        }

        $contents = file_get_contents($file->getRealPath());
        $source = $contents === false ? false : @imagecreatefromstring($contents);

        if (! ($source instanceof \GdImage)) {
            throw ValidationException::withMessages([
                'image' => 'Не удалось обработать загруженное изображение.',
            ]);
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(960 / $width, 1200 / $height, 1);

        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));
        $image = imagecreatetruecolor($newWidth, $newHeight);

        if (! ($image instanceof \GdImage)) {
            imagedestroy($source);

            throw new RuntimeException('Не удалось создать изображение для обработки.');
        }

        try {
            imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));

            if (! imagecopyresampled(
                $image,
                $source,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            )) {
                throw new RuntimeException('Не удалось изменить размер изображения.');
            }

            $disk = Storage::disk('public');
            $disk->makeDirectory('gods');

            $path = 'gods/'.Str::uuid().'.jpg';

            if (! imagejpeg($image, $disk->path($path), 85)) {
                $disk->delete($path);

                throw new RuntimeException('Не удалось сохранить изображение.');
            }

            return $path;
        } finally {
            imagedestroy($source);
            imagedestroy($image);
        }
    }

    private function deleteImageIfUnused(?string $path): void
    {
        if (! $path) {
            return;
        }

        $isUsed = God::withTrashed()
            ->where('image_path', $path)
            ->exists();

        if (! $isUsed) {
            Storage::disk('public')->delete($path);
        }
    }
}
