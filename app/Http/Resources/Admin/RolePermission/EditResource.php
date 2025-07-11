<?php

namespace App\Http\Resources\Admin\RolePermission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'permissions' => $this->getPermissions(),
            'members' =>  $this->getMembers(),
        ];

    }

    private function getPermissions()
    {
        $permissions = $this->permissions;
        // Group permissions by module and subcategories
        $groupedPermissions = $permissions->groupBy('module')->map(function ($modulePermissions) {
            return [
                'id' => $modulePermissions->first()->id,
                'name' => $modulePermissions->first()->module,
                'categories' => $modulePermissions->groupBy('category_module')->map(function ($categoryPermissions) {
                    return [
                        'id' => $categoryPermissions->first()->category_module,
                        'name' => $categoryPermissions->first()->category_module,
                        'description' => null,
                        'canManage' => null,
                        'subcategories' => $categoryPermissions->map(function ($permission) {
                            return [
                                'name' => $permission->label,
                                'description' => $permission->description,
                                'canManage' => true, // you can adjust this as needed
                            ];
                        })->values(),
                    ];
                })->values(),
            ];
        })->values();

        return $groupedPermissions->toArray();
    }

    private function getMembers()
    {
        return $this->users->map(function ($item) {
            return [
                'id' => $item->getKey(),
                'name' => $item->name,
                'email' => $item->email,
                'profile_photo_url' => $item->profile_photo_url,
                'created_at' => $item->created_at,
            ];
        });
    }
}
