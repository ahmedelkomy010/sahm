<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الصلاحيات المتاحة في النظام
     */
    const PERMISSIONS = [
        'manage_users' => 'إدارة المستخدمين',
        'manage_work_orders' => 'إدارة أوامر العمل',
        'manage_materials' => 'إدارة المواد',
        'manage_licenses' => 'إدارة التراخيص',
        'manage_violations' => 'إدارة المخالفات',
        'view_reports' => 'عرض التقارير',
        'manage_settings' => 'إدارة إعدادات النظام',
        // صلاحيات أنواع المشاريع
        'access_unified_contracts' => 'الوصول إلى العقود الموحدة',
        'access_turnkey_projects' => 'الوصول إلى مشاريع تسليم المفتاح',
        'access_special_projects' => 'الوصول إلى المشاريع الخاصة',
        // صلاحيات المدن
        'access_riyadh_contracts' => 'الوصول إلى عقود الرياض',
        'access_madinah_contracts' => 'الوصول إلى عقود المدينة المنورة',
    ];

    /**
     * أنواع المشاريع المتاحة
     */
    const PROJECT_TYPES = [
        'unified_contracts' => [
            'name' => 'العقد الموحد',
            'permission' => 'access_unified_contracts',
            'route' => 'project.selection',
            'icon' => 'document',
            'color' => 'blue'
        ],
        'turnkey_projects' => [
            'name' => 'تسليم مفتاح',
            'permission' => 'access_turnkey_projects',
            'route' => 'project.type-selection',
            'icon' => 'key',
            'color' => 'green'
        ],
        'special_projects' => [
            'name' => 'مشاريع خاصة',
            'permission' => 'access_special_projects',
            'route' => '#',
            'icon' => 'briefcase',
            'color' => 'purple'
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * أنواع المستخدمين في النظام
     */
    const USER_TYPES = [
        'admin' => 1,      // مشرف النظام
        'branch' => 2,     // مدير فرع
        'normal' => 0      // مستخدم عادي
    ];

    /**
     * المدن المتاحة في النظام
     */
    const CITIES = [
        'riyadh' => [
            'name' => 'الرياض',
            'permission' => 'access_riyadh_contracts'
        ],
        'madinah' => [
            'name' => 'المدينة المنورة',
            'permission' => 'access_madinah_contracts'
        ]
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'user_type',
        'city',
        'permissions',
        'phone',
        'job_title',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'is_admin' => 'integer',
        ];
    }
    
    /**
     * Get the work orders for the user.
     */
    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->user_type == self::USER_TYPES['admin'];
    }

    /**
     * التحقق مما إذا كان المستخدم مدير فرع
     *
     * @return bool
     */
    public function isBranchManager(): bool
    {
        return $this->user_type == self::USER_TYPES['branch'];
    }

    /**
     * الحصول على اسم المدينة التابع لها المستخدم
     *
     * @return string|null
     */
    public function getCityName(): ?string
    {
        if (!$this->city) {
            return null;
        }
        return self::CITIES[$this->city]['name'] ?? null;
    }

    /**
     * التحقق من صلاحيات المستخدم للمدينة
     *
     * @param string $city
     * @return bool
     */
    public function hasAccessToCity(string $city): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isBranchManager()) {
            return $this->city === $city;
        }

        $cityConfig = self::CITIES[$city] ?? null;
        if (!$cityConfig) {
            return false;
        }

        return $this->hasPermission($cityConfig['permission']);
    }
    
    /**
     * Check if user has specific permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        $permissions = $this->permissions;
        
        // التأكد من أن الـ permissions عبارة عن array
        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true) ?? [];
        }
        
        if (!is_array($permissions)) {
            $permissions = [];
        }
        
        return in_array($permission, $permissions);
    }

    /**
     * Check if user has any of the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        $userPermissions = $this->permissions;
        
        // التأكد من أن الـ permissions عبارة عن array
        if (is_string($userPermissions)) {
            $userPermissions = json_decode($userPermissions, true) ?? [];
        }
        
        if (!is_array($userPermissions)) {
            $userPermissions = [];
        }
        
        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * تحقق من إمكانية الوصول لنوع مشروع معين
     *
     * @param string $projectType
     * @return bool
     */
    public function canAccessProjectType(string $projectType): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $projectConfig = self::PROJECT_TYPES[$projectType] ?? null;
        if (!$projectConfig) {
            return false;
        }

        return $this->hasPermission($projectConfig['permission']);
    }

    /**
     * الحصول على أنواع المشاريع المسموح بها للمستخدم
     *
     * @return array
     */
    public function getAllowedProjectTypes(): array
    {
        if ($this->isAdmin()) {
            return self::PROJECT_TYPES;
        }

        $allowedTypes = [];
        foreach (self::PROJECT_TYPES as $key => $projectType) {
            if ($this->hasPermission($projectType['permission'])) {
                $allowedTypes[$key] = $projectType;
            }
        }

        return $allowedTypes;
    }

    /**
     * تحقق من إمكانية الوصول لأي نوع من المشاريع
     *
     * @return bool
     */
    public function hasAnyProjectAccess(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        foreach (self::PROJECT_TYPES as $projectType) {
            if ($this->hasPermission($projectType['permission'])) {
                return true;
            }
        }

        return false;
    }
}
