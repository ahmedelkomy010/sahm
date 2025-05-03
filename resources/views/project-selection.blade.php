@extends('layouts.app')

@section('content')
<div class="min-h-screen py-16 bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 project-selection-container">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-3 font-tajawal">اختيار المشروع</h1>
            <p class="text-gray-600 text-lg mb-4 font-tajawal">الرجاء اختيار المشروع الذي ترغب في العمل عليه</p>
            <div class="flex items-center justify-center">
                <div class="h-1 w-16 bg-blue-500 rounded-full"></div>
                <div class="h-1 w-32 bg-green-500 mx-3 rounded-full"></div>
                <div class="h-1 w-16 bg-blue-500 rounded-full"></div>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="flex flex-col md:flex-row justify-center gap-10 mb-16">
            <!-- Riyadh Project Card -->
            <div class="w-full md:w-1/2 bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 border border-gray-100">
                <div class="h-64 overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-blue-200 opacity-90"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center p-6">
                            <div class="w-20 h-20 rounded-full bg-blue-500/20 flex items-center justify-center mx-auto mb-4 border-2 border-blue-500/40 shadow-lg">
                                <i class="fas fa-city text-blue-600 text-4xl"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-black mb-3 font-tajawal tracking-wide">مشروع الرياض</h3>
                            <div class="w-16 h-1 bg-black/50 mx-auto"></div>
                        </div>
                    </div>
                </div>
                <div class="p-8 bg-white">
                    <div class="flex flex-col items-center justify-between h-full">
                        <p class="text-gray-600 text-center mb-8 text-lg font-tajawal leading-relaxed">مشروع العقد الموحد في منطقة الرياض لإدارة وتنفيذ أوامر العمل</p>
                        <a href="{{ route('admin.work-orders.index', ['project' => 'riyadh']) }}" 
                           class="w-full flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-center font-bold py-4 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl group font-tajawal text-lg relative overflow-hidden btn-hover-effect">
                           <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-blue-400 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                           <span class="absolute -left-12 w-10 h-32 bg-white/20 rotate-12 transform -skew-x-12 transition-all duration-700 group-hover:translate-x-96"></span>
                           <span class="relative z-10 flex items-center">
                               <span class="bg-blue-800 rounded-lg p-2 mr-3 shadow-lg">
                                   <i class="fas fa-city text-black"></i>
                               </span>
                               <span class="text-xl font-bold tracking-wider shadow-text">مشروع الرياض</span>
                               <i class="fas fa-arrow-left transition-transform duration-300 mr-3 group-hover:translate-x-2 text-white/80"></i>
                           </span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Madinah Project Card -->
            <div class="w-full md:w-1/2 bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 border border-gray-100">
                <div class="h-64 overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-100 to-green-200 opacity-90"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center p-6">
                            <div class="w-20 h-20 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-4 border-2 border-green-500/40 shadow-lg">
                                <i class="fas fa-mosque text-green-600 text-4xl"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-blue-600 mb-3 font-tajawal tracking-wide">مشروع المدينة المنورة</h3>
                            <div class="w-16 h-1 bg-black/50 mx-auto"></div>
                        </div>
                    </div>
                </div>
                <div class="p-8 bg-white">
                    <div class="flex flex-col items-center justify-between h-full">
                        <p class="text-gray-600 text-center mb-8 text-lg font-tajawal leading-relaxed">مشروع العقد الموحد في المدينة المنورة لإدارة وتنفيذ أوامر العمل</p>
                        <a href="{{ route('admin.dashboard', ['project' => 'madinah']) }}" 
                           class="w-full flex items-center justify-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-center font-bold py-4 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl group font-tajawal text-lg relative overflow-hidden btn-hover-effect">
                           <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-green-300 to-green-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                           <span class="absolute -left-12 w-10 h-32 bg-white/20 rotate-12 transform -skew-x-12 transition-all duration-700 group-hover:translate-x-96"></span>
                           <span class="relative z-10 flex items-center">
                               <span class="bg-green-700 rounded-lg p-2 mr-3 shadow-lg">
                                   <i class="fas fa-mosque text-black"></i>
                               </span>
                               <span class="text-xl font-bold tracking-wider shadow-text">مشروع المدينة المنورة</span>
                               <i class="fas fa-arrow-left transition-transform duration-300 mr-3 group-hover:translate-x-2 text-white/80"></i>
                           </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Link -->
        <div class="text-center">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-white text-gray-700 hover:text-blue-600 transition-colors duration-300 py-3 px-8 rounded-full shadow-md hover:shadow-lg font-tajawal relative overflow-hidden group">
               <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-30 transition-opacity duration-300"></span>
               <span class="relative z-10 flex items-center">
                  <span>العودة إلى لوحة التحكم</span>
                  <span class="bg-blue-100 rounded-full p-1.5 text-blue-500 mr-3 shadow transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white">
                     <i class="fas fa-home"></i>
                  </span>
               </span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
<style>
    body {
        direction: rtl;
    }
    .font-tajawal {
        font-family: 'Tajawal', sans-serif;
    }
    
    /* Project Selection Container */
    .project-selection-container {
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(5, 57, 112, 0.04) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(72, 187, 120, 0.1) 0%, transparent 20%),
            linear-gradient(to bottom right,rgb(255, 255, 255),rgb(219, 230, 245));
        background-attachment: fixed;
        position: relative;
        overflow: hidden;
    }
    
    .project-selection-container::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.5;
        z-index: 0;
    }
    
    /* Animation for hover effects */
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .hover-pulse:hover {
        animation: pulse 2s infinite;
    }
    
    /* Button shine effect */
    .btn-shine {
        position: relative;
        overflow: hidden;
    }
    
    .btn-shine::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to bottom right,
            rgba(246, 23, 23, 0) 0%,
            rgba(255, 255, 255, 0.1) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        transform: rotate(30deg);
        transition: all 1.5s;
    }
    
    .btn-shine:hover::after {
        transform: rotate(30deg) translate(100%, 100%);
    }
    
    /* Card hover effects */
    .project-card {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .project-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    
    /* Card header image effect */
    .card-header {
        position: relative;
        overflow: hidden;
    }
    
    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(39, 8, 8, 0.12);
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .project-card:hover .card-header::before {
        opacity: 1;
    }
    
    /* Icon container animation */
    .icon-container {
        transition: all 0.5s ease;
    }
    
    .project-card:hover .icon-container {
        transform: translateY(-5px) scale(1.1);
    }
    
    /* Button glow effect */
    .btn-glow {
        position: relative;
    }
    
    .btn-glow::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        z-index: -1;
        border-radius: inherit;
        background: linear-gradient(45deg, #3b82f6, #14b8a6, #3b82f6);
        background-size: 400%;
        opacity: 0;
        transition: 0.5s;
    }
    
    .btn-glow:hover::before {
        opacity: 1;
        animation: glowing 15s linear infinite;
    }
    
    @keyframes glowing {
        0% { background-position: 0 0; }
        50% { background-position: 400% 0; }
        100% { background-position: 0 0; }
    }
    
    /* Progress bar animation */
    .progress-bar {
        width: 0;
        height: 2px;
        background: linear-gradient(to right, #3b82f6, #14b8a6);
        position: absolute;
        bottom: 0;
        left: 0;
        transition: width 0.5s ease;
    }
    
    .project-card:hover .progress-bar {
        width: 100%;
    }
    
    /* 3D button effect */
    .btn-3d {
        transform-style: preserve-3d;
        perspective: 1000px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .btn-3d:hover {
        transform: translateY(-3px) rotateX(5deg);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    .btn-3d:active {
        transform: translateY(0) rotateX(0);
        box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    }
    
    /* Text animation */
    .animated-text span {
        display: inline-block;
        transform: translateY(0);
        transition: transform 0.3s ease;
    }
    
    .animated-text:hover span {
        transform: translateY(-2px);
        transition-delay: calc(0.05s * var(--i));
    }
    
    /* Button text with shadow */
    .shadow-text {
        text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.3);
        letter-spacing: 0.5px;
    }
    
    /* Make buttons more prominent on hover */
    .btn-hover-effect:hover {
        transform: translateY(-2px) scale(1.01);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animated text effect to buttons
        const addAnimatedText = (selector) => {
            const buttons = document.querySelectorAll(selector);
            buttons.forEach(button => {
                const text = button.querySelector('span:not(.bg-blue-800):not(.bg-green-700)');
                if (text) {
                    const chars = text.textContent.split('');
                    let html = '';
                    chars.forEach((char, i) => {
                        html += `<span style="--i:${i}">${char}</span>`;
                    });
                    text.innerHTML = html;
                    text.classList.add('animated-text');
                }
            });
        };
        
        // Add project card classes
        document.querySelectorAll('.md\\:w-1\\/2').forEach(card => {
            card.classList.add('project-card');
            card.querySelector('.h-64').classList.add('card-header');
            
            // Add progress bar
            const progressBar = document.createElement('div');
            progressBar.classList.add('progress-bar');
            card.appendChild(progressBar);
            
            // Add 3D effect to buttons
            const button = card.querySelector('a[href]');
            if (button) {
                button.classList.add('btn-3d', 'btn-glow');
            }
            
            // Add icon container class
            const iconContainer = card.querySelector('.w-20.h-20');
            if (iconContainer) {
                iconContainer.classList.add('icon-container');
            }
        });
        
        // Add animated text to buttons
        addAnimatedText('a[href]');
    });
</script>
@endpush 