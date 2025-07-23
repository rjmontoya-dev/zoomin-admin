<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileHandler
{
    /**
     * Store a base64 encoded file, determining type from the MIME data.
     *
     * @param string|null $base64File The base64 encoded file string.
     * @param string $defaultDirectory The default directory to store the file in.
     * @return string|null The public URL of the stored file, or null on failure.
     */
    public function storeBase64File(?string $base64File, string $defaultDirectory): ?string
    {
        if (empty($base64File) || !str_contains($base64File, ',')) {
            return null;
        }

        try {
            // Extract MIME type and data
            list($type, $data) = explode(';', $base64File);
            list(, $data) = explode(',', $data);
            $fileData = base64_decode($data);

            // Determine extension from MIME type
            $mimeType = str_replace('data:', '', $type);
            $extension = $this->getExtensionFromMimeType($mimeType);

            // Generate unique filename
            $filename = Str::uuid() . '.' . $extension;
            $path = $defaultDirectory . '/' . $filename;

            // Use transaction-aware storage to prevent orphaned files
            if (DB::transactionLevel() > 0) {
                DB::afterCommit(function () use ($path, $fileData) {
                    Storage::disk('public')->put($path, $fileData);
                });
            } else {
                Storage::disk('public')->put($path, $fileData);
            }

            return Storage::url($path);

        } catch (\Exception $e) {
            // Log the error or handle it as needed
            report($e);
            return null;
        }
    }

    /**
     * Get the appropriate file extension from a MIME type.
     */
    private function getExtensionFromMimeType(string $mimeType): string
    {
        $mappings = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        ];

        return $mappings[$mimeType] ?? 'bin'; // Default to binary
    }

}
