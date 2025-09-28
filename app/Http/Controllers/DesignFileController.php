<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Project;

class DesignFileController extends Controller
{
    /**
     * Upload design files
     */
    public function upload(Request $request, Project $project)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,xlsx,xls,doc,docx,dwg,dxf|max:50240', // 50MB max
            'section' => 'required|in:general,detail,base'
        ]);

        $uploadedFiles = [];
        $section = $request->input('section');

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '_' . $originalName;
                
                // Store file in project-specific directory
                $path = $file->storeAs(
                    "projects/{$project->id}/design/{$section}",
                    $filename,
                    'public'
                );

                $uploadedFiles[] = [
                    'original_name' => $originalName,
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $size,
                    'extension' => $extension,
                    'section' => $section,
                    'uploaded_at' => now()->format('Y-m-d H:i:s')
                ];
            }
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
            'message' => 'Files uploaded successfully'
        ]);
    }

    /**
     * Upload supplying files
     */
    public function uploadSupplying(Request $request, Project $project)
    {
        try {
            \Log::info('Upload supplying files started', [
                'project_id' => $project->id,
                'section' => $request->input('section'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0
            ]);

            $request->validate([
                'files.*' => 'required|file|mimes:pdf,xlsx,xls,doc,docx|max:50240', // 50MB max
                'section' => 'required|in:po,commercial,fat,delivery'
            ]);

            $uploadedFiles = [];
            $section = $request->input('section');

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $size = $file->getSize();
                    
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '_' . $originalName;
                    
                    \Log::info('Storing file', [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'section' => $section
                    ]);
                    
                    // Store file in project-specific directory
                    $path = $file->storeAs(
                        "projects/{$project->id}/supplying/{$section}",
                        $filename,
                        'public'
                    );

                    \Log::info('File stored successfully', ['path' => $path]);

                    $uploadedFiles[] = [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'path' => $path,
                        'size' => $size,
                        'extension' => $extension,
                        'section' => $section,
                        'uploaded_at' => now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            \Log::info('Upload supplying files completed successfully', [
                'uploaded_files_count' => count($uploadedFiles)
            ]);

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles,
                'message' => 'Files uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Upload supplying files failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get files for a project section
     */
    public function getFiles(Project $project, $section)
    {
        $path = "projects/{$project->id}/design/{$section}";
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['files' => []]);
        }

        $files = Storage::disk('public')->files($path);
        $fileList = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $originalName = $this->extractOriginalName($filename);
            $size = Storage::disk('public')->size($file);
            $lastModified = Storage::disk('public')->lastModified($file);

            $fileList[] = [
                'filename' => $filename,
                'original_name' => $originalName,
                'size' => $size,
                'size_formatted' => $this->formatFileSize($size),
                'extension' => pathinfo($filename, PATHINFO_EXTENSION),
                'last_modified' => date('M d, Y', $lastModified),
                'download_url' => route('design.files.download', [$project, $section, $filename]),
                'view_url' => route('design.files.view', [$project, $section, $filename])
            ];
        }

        return response()->json(['files' => $fileList]);
    }

    /**
     * Get supplying files for a project section
     */
    public function getSupplyingFiles(Project $project, $section)
    {
        $path = "projects/{$project->id}/supplying/{$section}";
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['files' => []]);
        }

        $files = Storage::disk('public')->files($path);
        $fileList = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $originalName = $this->extractOriginalName($filename);
            $size = Storage::disk('public')->size($file);
            $lastModified = Storage::disk('public')->lastModified($file);

            $fileList[] = [
                'filename' => $filename,
                'original_name' => $originalName,
                'size' => $size,
                'size_formatted' => $this->formatFileSize($size),
                'extension' => pathinfo($filename, PATHINFO_EXTENSION),
                'last_modified' => date('M d, Y', $lastModified),
                'download_url' => route('supplying.files.download', [$project, $section, $filename]),
                'view_url' => route('supplying.files.view', [$project, $section, $filename])
            ];
        }

        return response()->json(['files' => $fileList]);
    }

    /**
     * Download a file
     */
    public function download(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/design/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $originalName = $this->extractOriginalName($filename);
        
        return Storage::disk('public')->download($path, $originalName);
    }

    /**
     * View a file (for PDFs and images)
     */
    public function view(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/design/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Only allow viewing of certain file types
        $viewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extension, $viewableTypes)) {
            return $this->download($project, $section, $filename);
        }

        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return Response::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $this->extractOriginalName($filename) . '"'
        ]);
    }

    /**
     * Delete a file
     */
    public function delete(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/design/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        Storage::disk('public')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }

    /**
     * Download a supplying file
     */
    public function downloadSupplying(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/supplying/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $originalName = $this->extractOriginalName($filename);
        
        return Storage::disk('public')->download($path, $originalName);
    }

    /**
     * View a supplying file (for PDFs and images)
     */
    public function viewSupplying(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/supplying/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Only allow viewing of certain file types
        $viewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extension, $viewableTypes)) {
            return $this->downloadSupplying($project, $section, $filename);
        }

        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return Response::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $this->extractOriginalName($filename) . '"'
        ]);
    }

    /**
     * Delete a supplying file
     */
    public function deleteSupplying(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/supplying/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        Storage::disk('public')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }

    /**
     * Upload installation files
     */
    public function uploadInstallation(Request $request, Project $project)
    {
        try {
            \Log::info('Upload installation files started', [
                'project_id' => $project->id,
                'section' => $request->input('section'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0
            ]);

            $request->validate([
                'files.*' => 'required|file|mimes:pdf,xlsx,xls,doc,docx,dwg,dxf|max:50240', // 50MB max
                'section' => 'required|in:subcontractor,inhouse,general-design'
            ]);

            $uploadedFiles = [];
            $section = $request->input('section');

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $size = $file->getSize();
                    
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '_' . $originalName;
                    
                    \Log::info('Storing installation file', [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'section' => $section
                    ]);
                    
                    // Store file in project-specific directory
                    $path = $file->storeAs(
                        "projects/{$project->id}/installation/{$section}",
                        $filename,
                        'public'
                    );

                    \Log::info('Installation file stored successfully', ['path' => $path]);

                    $uploadedFiles[] = [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'path' => $path,
                        'size' => $size,
                        'extension' => $extension,
                        'section' => $section,
                        'uploaded_at' => now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            \Log::info('Upload installation files completed successfully', [
                'uploaded_files_count' => count($uploadedFiles)
            ]);

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles,
                'message' => 'Files uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Upload installation files failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get installation files for a project section
     */
    public function getInstallationFiles(Project $project, $section)
    {
        $path = "projects/{$project->id}/installation/{$section}";
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['files' => []]);
        }

        $files = Storage::disk('public')->files($path);
        $fileList = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $originalName = $this->extractOriginalName($filename);
            $size = Storage::disk('public')->size($file);
            $lastModified = Storage::disk('public')->lastModified($file);

            $fileList[] = [
                'filename' => $filename,
                'original_name' => $originalName,
                'size' => $size,
                'size_formatted' => $this->formatFileSize($size),
                'extension' => pathinfo($filename, PATHINFO_EXTENSION),
                'last_modified' => date('M d, Y', $lastModified),
                'download_url' => route('installation.files.download', [$project, $section, $filename]),
                'view_url' => route('installation.files.view', [$project, $section, $filename])
            ];
        }

        return response()->json(['files' => $fileList]);
    }

    /**
     * Download an installation file
     */
    public function downloadInstallation(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/installation/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $originalName = $this->extractOriginalName($filename);
        
        return Storage::disk('public')->download($path, $originalName);
    }

    /**
     * View an installation file (for PDFs and images)
     */
    public function viewInstallation(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/installation/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Only allow viewing of certain file types
        $viewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extension, $viewableTypes)) {
            return $this->downloadInstallation($project, $section, $filename);
        }

        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return Response::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $this->extractOriginalName($filename) . '"'
        ]);
    }

    /**
     * Delete an installation file
     */
    public function deleteInstallation(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/installation/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        Storage::disk('public')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }

    /**
     * Upload quality files
     */
    public function uploadQuality(Request $request, Project $project)
    {
        try {
            \Log::info('Upload quality files started', [
                'project_id' => $project->id,
                'section' => $request->input('section'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0
            ]);

            $request->validate([
                'files.*' => 'required|file|mimes:pdf,xlsx,xls,doc,docx,dwg,dxf|max:50240', // 50MB max
                'section' => 'required|in:rft,ncr,quality-plan,timeline,internal-audit,general-design'
            ]);

            $uploadedFiles = [];
            $section = $request->input('section');

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $size = $file->getSize();
                    
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '_' . $originalName;
                    
                    \Log::info('Storing quality file', [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'section' => $section
                    ]);
                    
                    // Store file in project-specific directory
                    $path = $file->storeAs(
                        "projects/{$project->id}/quality/{$section}",
                        $filename,
                        'public'
                    );

                    \Log::info('Quality file stored successfully', ['path' => $path]);

                    $uploadedFiles[] = [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'path' => $path,
                        'size' => $size,
                        'extension' => $extension,
                        'section' => $section,
                        'uploaded_at' => now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            \Log::info('Upload quality files completed successfully', [
                'uploaded_files_count' => count($uploadedFiles)
            ]);

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles,
                'message' => 'Files uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Upload quality files failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quality files for a specific section
     */
    public function getQualityFiles(Project $project, $section)
    {
        $path = "projects/{$project->id}/quality/{$section}";
        $files = [];

        if (Storage::disk('public')->exists($path)) {
            $fileList = Storage::disk('public')->files($path);
            
            foreach ($fileList as $file) {
                $filename = basename($file);
                $originalName = $this->extractOriginalName($filename);
                
                $files[] = [
                    'original_name' => $originalName,
                    'filename' => $filename,
                    'size_formatted' => $this->formatBytes(Storage::disk('public')->size($file)),
                    'last_modified' => date('M d, Y H:i', Storage::disk('public')->lastModified($file)),
                    'download_url' => route('quality.files.download', [$project->id, $section, $filename]),
                    'view_url' => route('quality.files.view', [$project->id, $section, $filename])
                ];
            }
        }

        return response()->json(['files' => $files]);
    }

    /**
     * Download quality file
     */
    public function downloadQuality(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/quality/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $originalName = $this->extractOriginalName($filename);
        
        return Storage::disk('public')->download($path, $originalName);
    }

    /**
     * View quality file in browser
     */
    public function viewQuality(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/quality/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return Response::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline'
        ]);
    }

    /**
     * Delete quality file
     */
    public function deleteQuality(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/quality/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        Storage::disk('public')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }

    /**
     * Upload reports files
     */
    public function uploadReports(Request $request, Project $project)
    {
        try {
            \Log::info('Upload reports files started', [
                'project_id' => $project->id,
                'section' => $request->input('section'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0
            ]);

            $request->validate([
                'files.*' => 'required|file|mimes:pdf,xlsx,xls,doc,docx,dwg,dxf|max:50240', // 50MB max
                'section' => 'required|in:weekly,biweekly,monthly,general-design'
            ]);

            $uploadedFiles = [];
            $section = $request->input('section');

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $size = $file->getSize();
                    
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '_' . $originalName;
                    
                    \Log::info('Storing reports file', [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'section' => $section
                    ]);
                    
                    // Store file in project-specific directory
                    $path = $file->storeAs(
                        "projects/{$project->id}/reports/{$section}",
                        $filename,
                        'public'
                    );

                    \Log::info('Reports file stored successfully', ['path' => $path]);

                    $uploadedFiles[] = [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'path' => $path,
                        'size' => $size,
                        'extension' => $extension,
                        'section' => $section,
                        'uploaded_at' => now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            \Log::info('Upload reports files completed successfully', [
                'uploaded_files_count' => count($uploadedFiles)
            ]);

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles,
                'message' => 'Files uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Upload reports files failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reports files for a specific section
     */
    public function getReportsFiles(Project $project, $section)
    {
        $path = "projects/{$project->id}/reports/{$section}";
        $files = [];

        if (Storage::disk('public')->exists($path)) {
            $fileList = Storage::disk('public')->files($path);
            
            foreach ($fileList as $file) {
                $filename = basename($file);
                $originalName = $this->extractOriginalName($filename);
                
                $files[] = [
                    'original_name' => $originalName,
                    'filename' => $filename,
                    'size_formatted' => $this->formatBytes(Storage::disk('public')->size($file)),
                    'last_modified' => date('M d, Y H:i', Storage::disk('public')->lastModified($file)),
                    'download_url' => route('reports.files.download', [$project->id, $section, $filename]),
                    'view_url' => route('reports.files.view', [$project->id, $section, $filename])
                ];
            }
        }

        return response()->json(['files' => $files]);
    }

    /**
     * Download reports file
     */
    public function downloadReports(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/reports/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $originalName = $this->extractOriginalName($filename);
        
        return Storage::disk('public')->download($path, $originalName);
    }

    /**
     * View reports file in browser
     */
    public function viewReports(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/reports/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return Response::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline'
        ]);
    }

    /**
     * Delete reports file
     */
    public function deleteReports(Project $project, $section, $filename)
    {
        $path = "projects/{$project->id}/reports/{$section}/{$filename}";
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        Storage::disk('public')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }

    /**
     * Extract original filename from stored filename
     */
    private function extractOriginalName($filename)
    {
        // Remove timestamp and unique ID prefix
        $parts = explode('_', $filename, 3);
        return isset($parts[2]) ? $parts[2] : $filename;
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 1) . ' ' . $units[$unitIndex];
    }
}