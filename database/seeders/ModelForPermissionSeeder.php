<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ModelForPermission;
use Illuminate\Database\Seeder;

class ModelForPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $myModels = $this->listAllModels();

        foreach ($myModels as $model) {
            $myModelForPermission = ModelForPermission::where('model_name', $model['model_name'])->first();

            if ($myModelForPermission) {
                continue;
            }

            ModelForPermission::create($model);
        }
    }

    private function listAllModels(): array
    {
        $modelList = [];
        $path = app_path() . "/Models";
        $results = scandir($path);

        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;

            $filename = $result;

            if (is_dir($filename)) {
                $modelList = array_merge($modelList, getModels($filename));
            }else{
                $modelList[] = $this->createModelForPermission(substr($filename,0,-4));
            }
        }

        return $modelList;
    }

    private function createModelForPermission(string $modelName): array
    {
        return [
            'model_name' => $modelName,
            'model_path' => $this->getClassPathFromName($modelName),
        ];
    }

    private function getClassPathFromName(string $modelPath): string
    {
        return "App\Models\\" . $modelPath;
    }
}
