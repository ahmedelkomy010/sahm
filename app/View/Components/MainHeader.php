<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MainHeader extends Component
{
    public $isHomePage;
    public $isUsersPage;
    public $isWorkOrdersPage;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $currentRoute = request()->route()->getName();
        
        $this->isHomePage = $currentRoute === 'dashboard';
        $this->isUsersPage = str_contains($currentRoute, 'admin.users');
        $this->isWorkOrdersPage = str_contains($currentRoute, 'admin.work-orders');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|string
    {
        return view('components.main-header');
    }
} 