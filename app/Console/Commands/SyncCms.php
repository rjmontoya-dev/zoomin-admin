<?php

namespace App\Console\Commands;

use App\Models\Admin\Pages\CmsPage;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncCms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:cms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync CMS Schema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    $schema = config('cms.schema');

        $totalCount = count($schema);
        $this->comment("Syncing process for <fg=gray>{$totalCount}</> CMS " . Str::plural('schema', $totalCount) . ' started!');
        $this->newLine();

        foreach ($schema as $index => $page) {
            $count = $index + 1;
            $this->info("({$count} of {$totalCount}) Schema: `{$page['slug']}` - {$page['label']}");

            CmsPage::firstOrCreate([
                'slug' => $page['slug'],
            ], [
                'label' => $page['label'],
                'show_metadata' => $page['show_metadata'],
            ]);
        }

        $this->newLine();
        $this->alert("<fg=gray>{$totalCount}</> CMS " . Str::plural('schema', $totalCount) . ' synced');
    }
}
