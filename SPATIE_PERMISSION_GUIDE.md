# دليل استخدام Spatie Permission في نظام ERP

## نظرة عامة
تم تثبيت وإعداد حزمة Spatie Permission لإدارة الأدوار والصلاحيات في نظام ERP الخاص بك. هذا الدليل يوضح كيفية استخدام النظام.

## الأدوار المتاحة

### 1. Super Admin (سوبر أدمن)
- لديه جميع الصلاحيات
- لا يمكن حذفه
- المستخدم الافتراضي: admin@erp.com / password

### 2. Admin (أدمن)
- إدارة المستخدمين والعملاء والفئات والمنتجات
- إدارة الطلبات والمبيعات
- عرض التقارير
- رفع وإدارة الملفات

### 3. Sales Manager (مدير المبيعات)
- إدارة العملاء والطلبات والمبيعات
- الموافقة على الطلبات والمبيعات
- عرض التقارير

### 4. Sales Representative (مندوب مبيعات)
- إنشاء وتعديل العملاء والطلبات والمبيعات
- عرض المنتجات

### 5. Inventory Manager (مدير المخزون)
- إدارة الفئات والمنتجات
- إدارة الملفات
- عرض الطلبات والتقارير

### 6. Accountant (محاسب)
- عرض البيانات المالية والتقارير
- تصدير التقارير

### 7. Employee (موظف)
- صلاحيات عرض أساسية فقط

## الصلاحيات المتاحة

### إدارة المستخدمين
- `view-users` - عرض المستخدمين
- `create-users` - إنشاء مستخدمين جدد
- `edit-users` - تعديل المستخدمين
- `delete-users` - حذف المستخدمين

### إدارة العملاء
- `view-clients` - عرض العملاء
- `create-clients` - إنشاء عملاء جدد
- `edit-clients` - تعديل العملاء
- `delete-clients` - حذف العملاء

### إدارة الفئات
- `view-categories` - عرض الفئات
- `create-categories` - إنشاء فئات جديدة
- `edit-categories` - تعديل الفئات
- `delete-categories` - حذف الفئات

### إدارة المنتجات
- `view-items` - عرض المنتجات
- `create-items` - إنشاء منتجات جديدة
- `edit-items` - تعديل المنتجات
- `delete-items` - حذف المنتجات

### إدارة الطلبات
- `view-orders` - عرض الطلبات
- `create-orders` - إنشاء طلبات جديدة
- `edit-orders` - تعديل الطلبات
- `delete-orders` - حذف الطلبات
- `approve-orders` - الموافقة على الطلبات

### إدارة المبيعات
- `view-sales` - عرض المبيعات
- `create-sales` - إنشاء مبيعات جديدة
- `edit-sales` - تعديل المبيعات
- `delete-sales` - حذف المبيعات
- `approve-sales` - الموافقة على المبيعات

### إدارة الملفات
- `view-files` - عرض الملفات
- `upload-files` - رفع ملفات
- `edit-files` - تعديل الملفات
- `delete-files` - حذف الملفات

### التقارير
- `view-reports` - عرض التقارير
- `export-reports` - تصدير التقارير

### إعدادات النظام
- `manage-settings` - إدارة إعدادات النظام
- `manage-roles` - إدارة الأدوار
- `manage-permissions` - إدارة الصلاحيات

### لوحة التحكم
- `view-dashboard` - عرض لوحة التحكم

## كيفية الاستخدام في الكود

### 1. في الـ Controllers
```php
public function __construct()
{
    $this->middleware('permission:view-users');
}

// أو للصلاحيات المختلفة حسب الوظيفة
public function __construct()
{
    $this->middleware('permission:view-users')->only(['index', 'show']);
    $this->middleware('permission:create-users')->only(['create', 'store']);
    $this->middleware('permission:edit-users')->only(['edit', 'update']);
    $this->middleware('permission:delete-users')->only(['destroy']);
}
```

### 2. في الـ Routes
```php
// حماية route واحد
Route::get('/users', [UserController::class, 'index'])
    ->middleware('permission:view-users');

// حماية مجموعة routes
Route::middleware('permission:view-clients')->group(function () {
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
});

// استخدام أكثر من صلاحية
Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('role_or_permission:admin|view-reports');
```

### 3. في الـ Blade Templates
```blade
@can('view-users')
    <a href="{{ route('users.index') }}">إدارة المستخدمين</a>
@endcan

@role('admin')
    <div class="admin-panel">
        <!-- محتوى خاص بالأدمن -->
    </div>
@endrole

@hasrole('super-admin|admin')
    <button>إعدادات النظام</button>
@endhasrole
```

### 4. في الكود PHP
```php
// التحقق من الصلاحيات
if (auth()->user()->can('edit-users')) {
    // المستخدم لديه صلاحية تعديل المستخدمين
}

// التحقق من الأدوار
if (auth()->user()->hasRole('admin')) {
    // المستخدم أدمن
}

// إعطاء صلاحية لمستخدم
$user = User::find(1);
$user->givePermissionTo('view-reports');

// إعطاء دور لمستخدم
$user->assignRole('sales-manager');

// إزالة صلاحية
$user->revokePermissionTo('delete-users');

// إزالة دور
$user->removeRole('employee');
```

## الأوامر المهمة

### تشغيل الـ Migrations
```bash
php artisan migrate
```

### تشغيل الـ Seeders
```bash
php artisan db:seed --class=RolePermissionSeeder
```

### مسح الـ Cache
```bash
php artisan permission:cache-reset
```

## إدارة الأدوار والصلاحيات

### الروابط الإدارية
- إدارة المستخدمين: `/admin/users`
- إدارة الأدوار: `/admin/roles`

### إنشاء دور جديد
```php
$role = Role::create(['name' => 'new-role']);
$role->givePermissionTo(['permission1', 'permission2']);
```

### إنشاء صلاحية جديدة
```php
$permission = Permission::create(['name' => 'new-permission']);
```

## ملاحظات مهمة

1. **المستخدم الافتراضي**: تم إنشاء مستخدم سوبر أدمن بالبيانات التالية:
   - البريد الإلكتروني: admin@erp.com
   - كلمة المرور: password

2. **الحماية**: جميع الروابط الإدارية محمية بالصلاحيات المناسبة

3. **الـ Cache**: النظام يستخدم cache لتحسين الأداء، استخدم `php artisan permission:cache-reset` عند إضافة صلاحيات جديدة

4. **الأمان**: لا يمكن حذف دور السوبر أدمن أو المستخدم السوبر أدمن

## أمثلة عملية

### إنشاء مستخدم جديد مع دور
```php
$user = User::create([
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'full_name' => 'John Doe',
    'password' => Hash::make('password'),
    'status' => 'active',
]);

$user->assignRole('sales-representative');
```

### التحقق من الصلاحيات في Controller
```php
public function deleteUser(User $user)
{
    if (!auth()->user()->can('delete-users')) {
        abort(403, 'غير مصرح لك بحذف المستخدمين');
    }
    
    $user->delete();
    return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح');
}
```

هذا النظام يوفر مرونة كاملة في إدارة الصلاحيات والأدوار لنظام ERP الخاص بك.
