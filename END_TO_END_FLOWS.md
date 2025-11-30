# 🔄 END-TO-END FLOWS - TODOLIST

Dokumen ini menjelaskan alur lengkap setiap fitur dari awal hingga akhir untuk memastikan semua komponen saling terhubung dengan baik.

---

## 1. USER REGISTRATION & ONBOARDING FLOW

### Current Status: ✅ Working (Parsial)

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: User Registration                                 │
└─────────────────────────────────────────────────────────┘

1. User visits homepage (/)
   ↓ [Redirects to login]
   
2. User clicks "Register" link
   ↓ [Route: /auth/register]
   
3. User fills registration form
   - Name ✅
   - Email ✅
   - Password ✅
   - Password Confirmation ✅
   ↓ [POST /auth/register]
   
4. AuthController@register processes:
   - Validates input ✅
   - Creates user ✅
   - Creates default categories ✅
   - Logs user in ✅
   - Sets default preferences ✅
   ↓
   
5. Redirects to Dashboard
   ✅ Success message shown
   ❌ ISSUE: Dashboard crashes (undefined variables)
```

### Fix Required:
- Dashboard Controller must send data (see QUICK_FIX_CHECKLIST.md #1)

### Test Scenario:
```bash
# Manual Test
1. Visit /register
2. Fill: Name="Test User", Email="test@example.com", Password="Test@123"
3. Submit form
4. Should redirect to /dashboard
5. Should see welcome message
6. Should see stats (0 tasks initially)
7. Should see 4 default categories created
```

---

## 2. TASK CREATION FLOW (Complete End-to-End)

### Current Status: ✅ Working

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: Create New Task                                   │
└─────────────────────────────────────────────────────────┘

1. User clicks "New Task" button
   ↓ [Route: /tasks/create]
   ↓ [TaskController@create]
   
2. Controller loads:
   - User's active categories ✅
   ↓
   
3. View displays form:
   - Title input ✅
   - Description textarea ✅
   - Category select ✅
   - Priority select ✅
   - Status select ✅
   - Due date picker ✅
   - Tags input ✅
   ↓
   
4. User submits form
   ↓ [POST /tasks]
   ↓ [TaskController@store]
   
5. Controller validates:
   - Title required ✅
   - Category must exist ✅
   - Priority must be valid ✅
   - Due date format ✅
   ↓
   
6. Task created in database ✅
   ↓
   
7. Redirects to task list
   ✅ Success message shown
```

### Database Flow:
```sql
INSERT INTO tasks (
    user_id,          -- Current auth user
    category_id,      -- Selected category
    title,            -- Form input
    description,      -- Form input
    priority,         -- Form select
    status,           -- Form select (default: pending)
    due_date,         -- Form date
    tags,             -- JSON array
    created_at,       -- Auto
    updated_at        -- Auto
)
```

### Connections Verified:
- ✅ User relationship (belongsTo)
- ✅ Category relationship (belongsTo)
- ✅ Task Policy (authorization)
- ✅ Validation rules
- ✅ Redirect with message

---

## 3. TASK LIFECYCLE FLOW

### Current Status: ⚠️ Partially Working

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: Task Complete Lifecycle                          │
└─────────────────────────────────────────────────────────┘

CREATE → VIEW → EDIT → TOGGLE COMPLETE → ARCHIVE → DELETE

1. CREATE (Working ✅)
   POST /tasks
   └─ Task created with status: pending
   
2. VIEW (Working ✅)
   GET /tasks/{task}
   └─ Shows task details
   
3. EDIT (Working ✅)
   GET /tasks/{task}/edit
   PUT /tasks/{task}
   └─ Updates task details
   
4. TOGGLE COMPLETE (MISSING ❌)
   POST /tasks/{task}/toggle
   └─ Route exists, controller method MISSING
   └─ FIX: See QUICK_FIX_CHECKLIST.md #2
   
5. ARCHIVE (MISSING ❌)
   POST /tasks/{task}/archive
   └─ Route exists, controller method MISSING
   └─ FIX: See QUICK_FIX_CHECKLIST.md #3
   
6. DELETE (Working ✅)
   DELETE /tasks/{task}
   └─ Soft deletes task
```

### Required Model State Changes:

```php
// Task States
STATUS_PENDING       → STATUS_IN_PROGRESS → STATUS_COMPLETED
is_completed: false  → is_completed: false → is_completed: true
completed_at: null   → completed_at: null  → completed_at: now()

// Archive State
is_archived: false → is_archived: true
archived_at: null  → archived_at: now()
```

### Integration Points:
```
Task Status Change
├─ Update model attributes ✅
├─ Fire event (MISSING) ❌
│  └─ TaskCompleted event
│     └─ Send notification
│     └─ Update statistics
│     └─ Log activity
└─ Redirect with message ✅
```

---

## 4. DASHBOARD STATISTICS FLOW

### Current Status: ❌ Broken (Critical)

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: Dashboard Statistics Display                      │
└─────────────────────────────────────────────────────────┘

1. User visits /dashboard
   ↓ [DashboardController@index]
   
2. Controller should query:
   ❌ Total tasks count
   ❌ Completed tasks count
   ❌ Pending tasks count
   ❌ Overdue tasks count
   ❌ Tasks due today count
   ❌ Recent tasks list
   
3. CURRENT PROBLEM:
   Controller only sends $user
   View expects all above variables
   Result: Undefined variable errors
   
4. REQUIRED FIX:
   ```php
   $totalTasks = $user->tasks()->active()->count();
   $completedTasks = $user->tasks()->completed()->count();
   $pendingTasks = $user->tasks()->pending()->count();
   $recentTasks = $user->tasks()->with('category')
       ->active()->latest()->limit(5)->get();
   ```
```

### Data Dependencies:
```
Dashboard View Requirements:
├─ $user (✅ passed)
├─ $totalTasks (❌ missing)
├─ $completedTasks (❌ missing)
├─ $pendingTasks (❌ missing)
├─ $recentTasks (❌ missing)
└─ $categoriesCount (❌ missing)

Each Task in $recentTasks needs:
├─ $task->title ✅
├─ $task->completed ✅
├─ $task->category (❌ not eager loaded)
├─ $task->priority_color ✅
└─ $task->formatted_due_date ✅
```

### Database Queries Needed:
```sql
-- Total Tasks
SELECT COUNT(*) FROM tasks 
WHERE user_id = ? AND is_archived = 0;

-- Completed Tasks
SELECT COUNT(*) FROM tasks 
WHERE user_id = ? AND is_completed = 1 AND is_archived = 0;

-- Pending Tasks
SELECT COUNT(*) FROM tasks 
WHERE user_id = ? AND is_completed = 0 AND is_archived = 0;

-- Recent Tasks (with category)
SELECT tasks.*, categories.name, categories.color
FROM tasks
LEFT JOIN categories ON tasks.category_id = categories.id
WHERE tasks.user_id = ?
  AND tasks.is_archived = 0
ORDER BY tasks.created_at DESC
LIMIT 5;
```

---

## 5. CATEGORY-TASK RELATIONSHIP FLOW

### Current Status: ✅ Working

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: Category → Tasks Relationship                     │
└─────────────────────────────────────────────────────────┘

1. Create Category
   POST /categories
   └─ Category stored with user_id ✅
   
2. Assign Category to Task
   POST /tasks
   └─ Task.category_id = category.id ✅
   
3. View Tasks by Category
   GET /tasks?category={id}
   └─ Filters tasks by category_id ✅
   
4. Delete Category
   DELETE /categories/{category}
   └─ Sets task.category_id = NULL (on delete set null) ✅
   
5. View Category Details
   GET /categories/{category}
   └─ Shows category with task count ✅
```

### Relationship Configuration:
```php
// Task Model
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
} ✅

// Category Model
public function tasks(): HasMany
{
    return $this->hasMany(Task::class);
} ✅

public function activeTasks(): HasMany
{
    return $this->hasMany(Task::class)
        ->where('is_archived', false);
} ✅
```

### Data Integrity:
```
Category Deletion
├─ Soft delete category ✅
├─ Set tasks.category_id to NULL ✅
└─ Tasks remain accessible ✅

Category Statistics
├─ Total tasks count ✅
├─ Completed tasks count ✅
├─ Completion percentage ✅
└─ Calculated in real-time ✅
```

---

## 6. SEARCH & FILTER FLOW

### Current Status: ✅ Working (Basic)

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: Search and Filter Tasks                          │
└─────────────────────────────────────────────────────────┘

1. User enters search query
   ↓ [GET /tasks?search={query}]
   
2. TaskController@index applies filters:
   ├─ Search query ✅
   │  └─ Searches: title, description, tags
   │
   ├─ Category filter ✅
   │  └─ WHERE category_id = ?
   │
   ├─ Priority filter ✅
   │  └─ WHERE priority = ?
   │
   ├─ Status filter ✅
   │  └─ WHERE status = ?
   │
   ├─ Due date filter ✅
   │  ├─ Today
   │  ├─ Overdue
   │  └─ This week
   │
   └─ Sorting ✅
      ├─ Created at
      ├─ Due date
      └─ Priority
   
3. Results paginated ✅
   └─ 15 per page
   
4. View displays filtered results ✅
```

### Query Builder Flow:
```php
$query = $user->tasks()->with('category');

// Apply search
if ($request->filled('search')) {
    $query->where(function($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%")
          ->orWhereJsonContains('tags', $search);
    });
}

// Apply category filter
if ($request->filled('category')) {
    $query->where('category_id', $request->category);
}

// Apply priority filter
if ($request->filled('priority')) {
    $query->byPriority($request->priority);
}

// Apply sorting
$query->orderBy($sortBy, $sortDirection);

// Paginate
$tasks = $query->paginate(15);
```

### Performance Considerations:
```
Optimization Needed:
├─ ✅ Indexes on: user_id, category_id, priority, due_date
├─ ✅ Eager loading: category relationship
├─ ⚠️ Consider: Full-text search for better search performance
└─ ⚠️ Consider: Caching popular searches
```

---

## 7. AUTHENTICATION & AUTHORIZATION FLOW

### Current Status: ✅ Working (Mostly)

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: Authentication & Authorization                    │
└─────────────────────────────────────────────────────────┘

AUTHENTICATION:
1. Login
   POST /auth/login
   └─ AuthController@login
      ├─ Validates credentials ✅
      ├─ Attempts login ✅
      ├─ Regenerates session ✅
      └─ Updates last_login_at ✅
   
2. Logout
   POST /logout
   └─ AuthController@logout
      ├─ Logs out user ✅
      └─ Redirects to login ✅

AUTHORIZATION:
3. Task Access Control
   TaskPolicy:
   ├─ view: user owns task OR is collaborator ✅
   ├─ create: authenticated ✅
   ├─ update: user owns task ✅
   ├─ delete: user owns task ✅
   └─ Checked via $this->authorize() ✅
   
4. Category Access Control
   CategoryPolicy:
   ├─ view: user owns category ✅
   ├─ create: authenticated ✅
   ├─ update: user owns category ✅
   └─ delete: user owns category ✅
```

### Middleware Protection:
```
Route Groups:
├─ Guest routes (login, register)
│  └─ middleware('guest') ✅
│
└─ Authenticated routes
   └─ middleware(['auth', 'verified']) ✅
      ├─ Dashboard ✅
      ├─ Tasks ✅
      ├─ Categories ✅
      ├─ Profile ⚠️ (view missing)
      └─ Settings ⚠️ (view missing)
```

---

## 8. PROFILE MANAGEMENT FLOW

### Current Status: ❌ Views Missing

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: Profile Management                                │
└─────────────────────────────────────────────────────────┘

CURRENT STATE:
├─ Routes defined ✅
├─ Controller methods exist ✅
└─ Views MISSING ❌

REQUIRED FLOW:
1. View Profile
   GET /profile
   └─ AuthController@show
      └─ Returns profile view with user data
      
2. Edit Profile
   GET /profile (edit view)
   PUT /profile
   └─ AuthController@update
      ├─ Validates input
      ├─ Updates user data
      └─ Redirects with success
      
3. Change Password
   PUT /profile/password
   └─ AuthController@updatePassword
      ├─ Validates current password
      ├─ Hashes new password
      └─ Updates user
      
4. Delete Account
   DELETE /profile
   └─ AuthController@destroy
      ├─ Confirms password
      ├─ Deletes user data
      └─ Logs out
```

### FIX REQUIRED:
Create views (see FIXES_PRIORITY.md #3):
- resources/views/profile/show.blade.php
- resources/views/profile/edit.blade.php

---

## 9. NOTIFICATION FLOW (Not Implemented)

### Current Status: ❌ Stub Only

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: Notification System (PLANNED)                     │
└─────────────────────────────────────────────────────────┘

REQUIRED FLOW:
1. Event Triggers
   ├─ Task completed
   ├─ Task due soon
   ├─ Collaboration invite
   └─ Task assigned
   
2. Notification Created
   └─ INSERT INTO notifications table
   
3. Delivery
   ├─ In-app: Show in navbar dropdown
   ├─ Email: Queue job → Send email
   └─ Push: (Future) Browser notification
   
4. User Actions
   ├─ View notification
   ├─ Mark as read
   └─ Delete notification
```

### IMPLEMENTATION NEEDED:
```php
// 1. Create Event
php artisan make:event TaskCompleted

// 2. Create Listener
php artisan make:listener SendTaskCompletionNotification

// 3. Create Notification
php artisan make:notification TaskCompletedNotification

// 4. Implement NotificationController methods
// 5. Create notification views
```

---

## 10. COLLABORATION FLOW (Not Implemented)

### Current Status: ❌ Stub Only

```
┌─────────────────────────────────────────────────────────┐
│  FLOW: Task Collaboration (PLANNED)                      │
└─────────────────────────────────────────────────────────┘

DATABASE READY:
├─ task_collaborators table exists ✅
└─ Relationships defined ✅

REQUIRED FLOW:
1. Invite Collaborator
   POST /collaboration/tasks/{task}/invite
   ├─ Validate user email
   ├─ Create invitation
   ├─ Send notification
   └─ Create collaborator record (pending)
   
2. Accept/Decline Invitation
   POST /collaboration/tasks/{task}/accept
   └─ Update collaborator record
      ├─ accepted_at = now()
      └─ Status = accepted
      
3. Access Task
   GET /tasks/{task}
   └─ Check authorization
      ├─ User is owner OR
      └─ User is collaborator
      
4. Remove Collaborator
   DELETE /collaboration/tasks/{task}/remove/{user}
   └─ Delete collaborator record
```

---

## 🔍 MISSING CONNECTIONS SUMMARY

### Critical Gaps:
1. ❌ Dashboard → Statistics (data not passed)
2. ❌ Task → Toggle Action (method missing)
3. ❌ Task → Archive Action (method missing)
4. ❌ Profile → Views (files missing)
5. ❌ Settings → Views (files missing)

### Medium Priority Gaps:
6. ❌ Task → Comments (not implemented)
7. ❌ Task → Attachments (not implemented)
8. ❌ Notifications → Complete system (stub only)
9. ❌ Collaboration → Complete system (stub only)
10. ❌ Reports → All pages (minimal)

---

## ✅ VERIFICATION CHECKLIST

Use this to verify all flows work end-to-end:

### [ ] Registration Flow
- Register new user → Dashboard loads → Default categories created

### [ ] Task Flow
- Create task → View task → Edit task → Toggle complete → Archive → Delete

### [ ] Category Flow
- Create category → Assign to task → View tasks by category → Delete category

### [ ] Search Flow
- Enter search → Results filter → Apply category filter → Sort results

### [ ] Profile Flow (After fix)
- View profile → Edit profile → Change password → Save successfully

### [ ] Dashboard Flow (After fix)
- Login → Dashboard loads → Stats display → Recent tasks show

---

## 🎯 PRIORITY FIXES FOR END-TO-END CONNECTIVITY

1. **Dashboard** - Fix data flow (URGENT)
2. **Task Actions** - Complete missing methods (HIGH)
3. **Profile/Settings** - Create views (HIGH)
4. **Components** - Create reusable components (MEDIUM)
5. **Notifications** - Implement system (MEDIUM)
6. **Collaboration** - Implement system (LOW)

See QUICK_FIX_CHECKLIST.md for implementation steps.
