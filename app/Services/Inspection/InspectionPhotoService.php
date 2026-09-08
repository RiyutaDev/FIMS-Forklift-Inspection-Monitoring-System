<?php

namespace App\Services\Inspection;

use App\Models\InspectionDetail;
use App\Models\InspectionPhoto;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class InspectionPhotoService
{
    public function store(InspectionDetail $detail, mixed $file, ?int $uploadedBy = null): InspectionPhoto
    {
        if (!$file) {
            throw new RuntimeException('File foto tidak ditemukan.');
        }

        $path = $file->store('inspection-photos', 'public');

        return InspectionPhoto::create([
            'inspection_detail_id' => $detail->id,
            'uploaded_by' => $uploadedBy,
            'photo_name' => $file->getClientOriginalName(),
            'photo_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'photo_size' => $file->getSize(),
        ]);
    }

    public function delete(InspectionPhoto $photo): bool
    {
        Storage::disk('public')->delete($photo->photo_path);

        return $photo->delete();
    }
}
