<?php

namespace App\Http\Service\Maintenance;

use App\Http\Service\Base\BaseService;
use App\Http\Traits\FileHandler;
use App\Jobs\Maintenance\MaintenanceScheduler;
use App\Models\Admin\Maintenance\Maintenance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaintenanceService extends BaseService
{
    use FileHandler;

    public function update(Request $request, string $id): Maintenance
    {
        $maintenance = $this->model->findOrFail($id);
        $data = $request->validated();

        if ($data['maintenance_type'] === 'immediate') {
            $data['status'] = $data['status'] ? 'enabled' : 'disabled';
            $data['start_at'] = null;
            $data['end_at'] = null;
        } elseif ($data['maintenance_type'] === 'scheduled') {
            $data['status'] = 'disabled';
        }

        if (array_key_exists('image', $data)) {
            if ($maintenance->image) {
                $oldImagePath = str_replace(Storage::url(''), '', $maintenance->image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $imageValue = $data['image'];

            if ($imageValue instanceof \Illuminate\Http\UploadedFile) {
                $path = $imageValue->store('maintenance_images', 'public');
                $data['image'] = Storage::url($path);
            } elseif (is_string($imageValue) && str_starts_with($imageValue, 'data:image')) {
                $data['image'] = $this->storeBase64File($imageValue, 'maintenance_images');
            } else {
                $data['image'] = null;
            }
        }

        $maintenanceType = $data['maintenance_type'];
        unset($data['maintenance_type']);

        $maintenance->update($data);

        if ($maintenanceType === 'scheduled' && isset($data['start_at']) && Carbon::parse($data['start_at'])->isFuture()) {
            MaintenanceScheduler::dispatch($maintenance, 'enabled')->delay(Carbon::parse(time: $data['start_at']));

            if (isset($data['end_at']) && Carbon::parse($data['end_at'])->isFuture()) {
                MaintenanceScheduler::dispatch($maintenance, 'disabled')->delay(Carbon::parse(time: $data['end_at']));
            }
        }

        return $maintenance;
    }
}
