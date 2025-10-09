@extends('layouts.app')

@push('styles')
<style>
    /* تنسيق LTR للصفحة */
    .ltr-page {
        direction: ltr;
        text-align: left;
    }
    
    .ltr-page * {
        text-align: left;
    }
    
    /* تعديل الـ margin والـ padding للتنسيق LTR */
    .ltr-page .mr-2 { margin-right: 0.5rem; margin-left: 0; }
    .ltr-page .mr-3 { margin-right: 0.75rem; margin-left: 0; }
    .ltr-page .mr-1 { margin-right: 0.25rem; margin-left: 0; }
    .ltr-page .ml-2 { margin-left: 0.5rem; margin-right: 0; }
    .ltr-page .ml-3 { margin-left: 0.75rem; margin-right: 0; }
    .ltr-page .ml-1 { margin-left: 0.25rem; margin-right: 0; }
    
    /* تعديل التوجه للعناصر */
    .ltr-page .flex-row-reverse { flex-direction: row; }
    .ltr-page .justify-end { justify-content: flex-start; }
    .ltr-page .items-end { align-items: flex-start; }
    .ltr-page .text-right { text-align: left; }
    
    /* تعديل مخصص للبطاقات */
    .ltr-page .project-card {
        text-align: left;
    }
    
    .ltr-page .card-link {
        text-align: left;
    }
    
    /* تعديل أيقونات الأسهم للتنسيق LTR */
    .ltr-page .back-arrow {
        transform: scaleX(-1);
    }
    
    /* تعديل أسهم "View Details" */
    .ltr-page .view-details-arrow {
        transform: scaleX(-1);
    }
    .project-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .project-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-color);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .project-card:hover::before {
        transform: scaleX(1);
    }
    
    .project-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        border-color: var(--card-color);
    }
    
    .project-card .icon-wrapper {
        background: var(--card-bg);
        transition: all 0.3s ease;
    }
    
    .project-card:hover .icon-wrapper {
        background: var(--card-color);
        transform: scale(1.1);
    }
    
    .project-card:hover .icon-wrapper svg {
        color: white !important;
    }
    
    .project-card .card-link {
        color: var(--card-color);
        transition: all 0.3s ease;
    }
    
    .project-card:hover .card-link {
        color: var(--card-color);
        font-weight: 600;
    }
    
    .project-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .project-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.3;
    }
    
    .section-title {
        position: relative;
        display: inline-block;
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }
    
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        padding: 2rem 0;
    }
    
    @media (max-width: 768px) {
        .card-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .project-card {
            margin: 0 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-12 ltr-page">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back to Projects Button -->
        <div class="mb-8 flex justify-start">
            <a href="{{ route('project.type-selection') }}" 
               class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-xl font-semibold shadow-lg hover:bg-white hover:shadow-xl transition-all duration-300 hover:transform hover:scale-105">
                <svg class="w-5 h-5 mr-2 back-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Projects
            </a>
        </div>
        
        <!-- Project Header -->
        <div class="project-header rounded-3xl shadow-2xl overflow-hidden mb-12 relative">
            <div class="relative z-10 p-8 text-left text-white">
                <div class="mb-6">
                    <h1 class="text-4xl font-bold mb-3">{{ $project->name }}</h1>
                    <p class="text-blue-100 text-lg">Contract Number: {{ $project->contract_number }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-blue-100 text-sm mb-1">Project Type</p>
                        <p class="font-semibold text-lg">{{ $project->getProjectTypeText() }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-blue-100 text-sm mb-1">Location</p>
                        <p class="font-semibold text-lg">{{ $project->location }}</p>
                    </div>
                    <a href="{{ route('projects.revenues', $project) }}" class="bg-white/10 backdrop-blur-sm rounded-xl p-4 hover:bg-white/20 transition-all duration-300 flex flex-col items-center justify-center group">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-blue-100 text-sm">Turnkey Revenues</p>
                        </div>
                        <p class="font-semibold text-lg text-white group-hover:scale-110 transition-transform">View Details →</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Project Management Section -->
        <div class="text-left mb-12">
            <h2 class="section-title">Project Management</h2>
            <p class="text-gray-600 mt-4 text-lg">Comprehensive project oversight and control</p>
        </div>
        <div class="card-grid">
            
            <!-- Bid Package Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #F97316; --card-bg: #FED7AA;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">Bid Package</h3>
                        <p class="text-gray-600 text-sm text-left">Tender documents & specifications</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Completion</span>
                        <span class="text-sm font-semibold text-orange-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.bid-package', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Study Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #7C3AED; --card-bg: #EDE9FE;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">Study</h3>
                        <p class="text-gray-600 text-sm text-left">Feasibility & analysis</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Completion</span>
                        <span class="text-sm font-semibold text-violet-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-violet-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.study', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Clarification Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #EC4899; --card-bg: #FCE7F3;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">Clarification</h3>
                        <p class="text-gray-600 text-sm text-left">Queries & clarifications</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Resolved</span>
                        <span class="text-sm font-semibold text-pink-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-pink-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.clarification', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Design Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #3B82F6; --card-bg: #DBEAFE;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">Design</h3>
                        <p class="text-gray-600 text-sm text-left">Engineering drawings & blueprints</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Completion</span>
                        <span class="text-sm font-semibold text-blue-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.design', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        
                        <!-- Supplying Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #10B981; --card-bg: #D1FAE5;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">Supply Chain</h3>
                        <p class="text-gray-600 text-sm text-left">Materials & equipment management</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Completion</span>
                        <span class="text-sm font-semibold text-green-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.supplying', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Testing Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #F59E0B; --card-bg: #FEF3C7;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">Testing</h3>
                        <p class="text-gray-600 text-sm text-left">Quality testing & inspections</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Completion</span>
                        <span class="text-sm font-semibold text-yellow-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.testing', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Reports Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #14B8A6; --card-bg: #CCFBF1;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">Reports</h3>
                        <p class="text-gray-600 text-sm text-left">Progress reports & analytics</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Generated</span>
                        <span class="text-sm font-semibold text-teal-600">0</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-teal-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.reports', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quality Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #6366F1; --card-bg: #E0E7FF;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">Quality</h3>
                        <p class="text-gray-600 text-sm text-left">Quality control</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Completion</span>
                        <span class="text-sm font-semibold text-indigo-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.quality', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- H.S.E Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #EF4444; --card-bg: #FEE2E2;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">H.S.E</h3>
                        <p class="text-gray-600 text-sm text-left">H.S.E procedures & protocols</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Compliance</span>
                        <span class="text-sm font-semibold text-red-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.safety', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>


            <!-- Documents Card -->
            <div class="project-card rounded-2xl p-8" style="--card-color: #6B7280; --card-bg: #F3F4F6;">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-16 h-16 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-1 text-left">Documents</h3>
                        <p class="text-gray-600 text-sm text-left">Project documentation & files</p>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Files</span>
                        <span class="text-sm font-semibold text-gray-600">0</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gray-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div class="text-left">
                    <a href="{{ route('projects.documents', $project) }}" class="card-link inline-flex items-center font-semibold">
                        <span>View Details</span>
                        <svg class="w-5 h-5 mr-2 view-details-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 