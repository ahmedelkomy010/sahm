<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;
use App\Models\WorkOrderFile;
use Illuminate\Support\Facades\DB;

class AssociateSurveyFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:associate-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Associate existing files with their respective surveys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Associating files with surveys...');

        // Get all surveys
        $surveys = Survey::all();
        $count = 0;

        foreach ($surveys as $survey) {
            $this->info("Processing survey ID: " . $survey->id);
            
            // Get all image files for this work order
            $files = WorkOrderFile::where('work_order_id', $survey->work_order_id)
                ->where('file_type', 'like', 'image/%')
                ->whereNull('survey_id')
                ->get();

            if ($files->count() > 0) {
                $this->info("Found " . $files->count() . " files for survey " . $survey->id);
                
                // Associate files with this survey if they are related to the same work order
                foreach ($files as $file) {
                    if (strpos($file->file_path, 'survey') !== false) {
                        $file->survey_id = $survey->id;
                        $file->save();
                        $count++;
                    }
                }
            }
        }

        $this->info("Associated $count files with their respective surveys.");
    }
}
