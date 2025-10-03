<?php

namespace App\Jobs;

use App\Models\Lecture;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessUploadedVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Lecture $lecture;

    public function __construct(Lecture $lecture)
    {
        $this->lecture = $lecture;
    }

    public function handle(): void
    {
        Log::info("--- START PROCESSING LECTURE ID: {$this->lecture->id} ---");

        // ... (kode persiapan path tetap sama) ...
        $videoPathS3 = $this->lecture->video_path;
        $thumbnailName = uniqid() . '.jpg';
        $tempVideoPathLocal = 'temp/' . basename($videoPathS3);
        $tempThumbnailPathLocalAbsolute = storage_path('app/public/' . uniqid() . '.jpg');

        try {
            // ... (kode unduh video tetap sama) ...
            $videoContent = Storage::disk('s3')->get($videoPathS3);
            if (!$videoContent) {
                Log::error("Failed to download video from S3 for lecture ID: {$this->lecture->id}");
                return;
            }
            Storage::disk('local')->put($tempVideoPathLocal, $videoContent);
            $absoluteVideoPath = Storage::disk('local')->path($tempVideoPathLocal);

            // === BAGIAN PENYADAPAN DIMULAI DI SINI ===
            $durationCommand = "ffprobe -v quiet -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '{$absoluteVideoPath}'";
            
            // 1. Jalankan perintah dan simpan output MENTAH-nya ke variabel baru
            $rawOutput = shell_exec($durationCommand);
            
            // 2. Catat output mentah itu ke log agar kita bisa melihatnya
            Log::info("--- RAW FFPROBE OUTPUT START ---");
            Log::info($rawOutput);
            Log::info("--- RAW FFPROBE OUTPUT END ---");
            
            // 3. Lanjutkan dengan logika lama, tapi gunakan variabel baru
            $durationInSeconds = (int) $rawOutput;
            Log::info("Calculated duration (after int conversion): {$durationInSeconds} seconds.");
            // === BAGIAN PENYADAPAN SELESAI ===

            $thumbnailCommand = "ffmpeg -i '{$absoluteVideoPath}' -ss 00:00:05 -frames:v 1 -y '{$tempThumbnailPathLocalAbsolute}'";
            shell_exec($thumbnailCommand);

            $finalThumbnailPathS3 = null;
            if (file_exists($tempThumbnailPathLocalAbsolute)) {
                $thumbnailFile = fopen($tempThumbnailPathLocalAbsolute, 'r');
                $finalThumbnailPathS3 = 'lecture_thumbnails/' . $thumbnailName;
                Storage::disk('s3')->put($finalThumbnailPathS3, $thumbnailFile);
                fclose($thumbnailFile);
            } else {
                Log::warning("Thumbnail file was not created for lecture ID: {$this->lecture->id}");
            }

            $this->lecture->update([
                'duration_in_seconds' => $durationInSeconds,
                'thumbnail_path' => $finalThumbnailPathS3,
            ]);
            Log::info("Lecture record updated in database.");

        } catch (\Exception $e) {
            Log::error("An exception occurred. Error: " . $e->getMessage());
        
        } finally {
            Storage::disk('local')->delete($tempVideoPathLocal);
            if (file_exists($tempThumbnailPathLocalAbsolute)) {
                unlink($tempThumbnailPathLocalAbsolute);
            }
            Log::info("--- END PROCESSING LECTURE ID: {$this->lecture->id} ---");
        }
    }
}