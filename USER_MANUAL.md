# 📖 USER MANUAL - TodoList Application

**Version:** 1.0.0  
**Last Updated:** November 2, 2025

---

## 🎯 GETTING STARTED

### Creating Your Account

1. Visit the application URL
2. Click **"Register"** button
3. Fill in:
   - Your name
   - Email address
   - Password (minimum 8 characters, mixed case, numbers, symbols)
   - Confirm password
4. Click **"Create Account"**
5. Check your email for verification (if enabled)
6. Login with your credentials

---

## 📝 TASK MANAGEMENT

### Creating a Task

1. Click **"+ New Task"** button (top right) or press `⌘N` (Mac) / `Ctrl+N` (Windows)
2. Fill in task details:
   - **Title**: Task name (required)
   - **Description**: Additional details
   - **Category**: Select from your categories
   - **Priority**: Low, Medium, or High
   - **Status**: Pending, In Progress, or Completed
   - **Due Date**: When the task should be completed
   - **Tags**: Add labels (comma-separated)
3. Click **"Create Task"**

### Viewing Tasks

**List View:**
- All tasks displayed in cards
- Shows: title, category, priority, due date, status
- Color-coded priorities (Red=High, Yellow=Medium, Blue=Low)

**Filtering Tasks:**
- **Search**: Type in search box to find tasks by title/description
- **Category**: Filter by specific category
- **Priority**: Show only High, Medium, or Low priority
- **Status**: View Completed or Pending tasks
- **Due Date**: Filter by Today, Overdue, or This Week

**Sorting Tasks:**
- By Created Date
- By Due Date
- By Priority

### Editing a Task

1. Click on the task card
2. Click **"Edit"** button
3. Modify any field
4. Click **"Save Changes"**

### Task Actions

**Toggle Completion:**
- Click the checkbox on any task
- Task will mark as complete/incomplete instantly

**Archive Task:**
- Click **"..."** menu on task
- Select **"Archive"**
- Archived tasks moved to Archive page

**Duplicate Task:**
- Click **"..."** menu
- Select **"Duplicate"**
- Creates a copy with " (Copy)" appended to title

**Delete Task:**
- Click **"..."** menu
- Select **"Delete"**
- Confirm deletion (cannot be undone)

### Bulk Actions

1. Select multiple tasks using checkboxes
2. Choose action from dropdown:
   - **Complete** - Mark all as completed
   - **Incomplete** - Mark all as pending
   - **Archive** - Move all to archive
   - **Delete** - Delete all selected
   - **Change Priority** - Set priority for all
   - **Change Category** - Move to different category
3. Click **"Apply"**

### Comments

**Add Comment:**
1. Open task detail
2. Scroll to Comments section
3. Type your comment
4. Click **"Add Comment"**

**Delete Comment:**
- Click trash icon next to your comment
- Only you or task owner can delete

---

## 🏷️ CATEGORY MANAGEMENT

### Creating a Category

1. Go to **"Categories"** page
2. Click **"+ New Category"**
3. Fill in:
   - **Name**: Category name (required)
   - **Description**: Category purpose
   - **Color**: Choose a color
   - **Icon**: Select an icon
4. Click **"Create"**

**Default Categories:**
When you register, 4 default categories are created:
- Personal (Blue)
- Work (Purple)
- Shopping (Green)
- Health (Red)

### Managing Categories

**Edit Category:**
1. Click on category card
2. Modify details
3. Save changes

**Delete Category:**
- Click **"Delete"** button
- Tasks in this category will have no category assigned
- Confirm deletion

**Statistics:**
Each category shows:
- Total tasks
- Completed tasks
- Completion percentage

---

## 👥 COLLABORATION

### Inviting Collaborators

1. Open a task
2. Click **"Invite Collaborator"**
3. Enter collaborator's email
4. Choose role:
   - **Viewer**: Can only view
   - **Editor**: Can view and edit
   - **Admin**: Full access (edit, delete, invite others)
5. Click **"Send Invitation"**

### Managing Invitations

**Accepting Invitation:**
1. Check your notifications or collaboration page
2. View pending invitations
3. Click **"Accept"**

**Declining Invitation:**
- Click **"Decline"** on invitation

**Removing Collaborator:**
1. Open task
2. Go to Collaborators section
3. Click **"Remove"** next to collaborator's name

### Collaboration Features

- **View shared tasks**: See all tasks you're collaborating on
- **Comment on tasks**: Discuss with team members
- **Track changes**: See who made what changes
- **Role-based permissions**: Control what collaborators can do

---

## 📊 REPORTS & ANALYTICS

### Dashboard Overview

The dashboard shows:
- **Total Tasks**: All your tasks
- **Completed**: Finished tasks
- **Pending**: Ongoing tasks
- **Recent Activity**: Last 5 tasks you worked on

### Productivity Report

Access: **Reports → Productivity**

Shows:
- Daily completion rate
- Tasks by priority breakdown
- Average completion time
- Most productive hours

**Filters:**
- Last 7 days
- Last 30 days
- Last 90 days
- Custom date range

### Time Tracking Report

Access: **Reports → Time Tracking**

Shows:
- Time spent per category
- Estimated vs actual time
- Weekly time distribution

### Completion Report

Access: **Reports → Completion**

Shows:
- Completion rate over time
- Tasks completed by period
- Category-wise completion

---

## 📤 EXPORT & IMPORT

### Exporting Data

**Export Tasks:**
1. Go to **Settings → Data & Privacy**
2. Click **"Export Data"**
3. Choose format:
   - **CSV**: Spreadsheet format
   - **JSON**: Complete data with relationships
   - **PDF**: Printable format
4. File downloads automatically

**Export Options:**
- All tasks
- Filtered tasks (by category, status, priority)
- Include comments
- Include collaborators

### Export Formats

**CSV Format:**
- Columns: ID, Title, Description, Category, Priority, Status, Due Date, Created, Completed
- Opens in Excel/Google Sheets

**JSON Format:**
- Complete data structure
- Includes: tasks, categories, comments, collaborators
- Good for backup or migration

**PDF Format:**
- Formatted table view
- Ready for printing
- Professional layout

---

## ⚙️ SETTINGS

### Profile Settings

Access: **Profile Icon → Profile**

**Update Profile:**
- Change name
- Update email
- Upload avatar (coming soon)

**Change Password:**
1. Enter current password
2. Enter new password (min 8 chars)
3. Confirm new password
4. Click **"Update Password"**

**Account Statistics:**
- View your total tasks
- See completion rate
- Check categories count

### Application Settings

Access: **Settings Icon → Settings**

**Appearance:**
- **Theme**: Light, Dark, or Auto (follows system)
- Changes apply instantly

**Notifications:**
- **Email Notifications**: Receive emails for:
  - Task reminders
  - Due date alerts
  - Collaboration invites
  - Weekly summary
- **Push Notifications**: Browser notifications
- **Notification Types**: Customize what you want to receive

**Data & Privacy:**
- Export your data
- View activity log (coming soon)
- Delete all data
- Delete account

### Keyboard Shortcuts

Access: Press `⌘/` (Mac) or `Ctrl/` (Windows)

**Available Shortcuts:**
- `⌘K` - Open search
- `⌘B` - Toggle sidebar
- `⌘N` - New task
- `⌘/` - Show shortcuts help
- `Esc` - Close modals

---

## 🔔 NOTIFICATIONS

### Notification Center

Click the **bell icon** (top right) to view notifications.

**Notification Types:**
- Task reminders
- Due date alerts
- Collaboration invitations
- Task assignments
- Comments on your tasks

**Managing Notifications:**
- **Mark as Read**: Click on notification
- **Mark All as Read**: Button at bottom
- **Delete**: Click X icon
- **View All**: Link at bottom

---

## 🔍 SEARCH

### Quick Search

Use the search bar (top of page) or press `⌘K`:
1. Type task title, description, or tag
2. Results appear instantly
3. Click result to open task

### Advanced Search

Coming soon:
- Search by multiple criteria
- Saved searches
- Search history

---

## 📱 MOBILE APP

### Responsive Design

TodoList works on all devices:
- **Desktop**: Full features
- **Tablet**: Optimized layout
- **Mobile**: Touch-friendly interface

**Mobile Features:**
- Swipe actions
- Pull to refresh
- Touch gestures
- Responsive navigation

---

## ❓ FAQ

### Q: How do I recover a deleted task?
A: Deleted tasks cannot be recovered. Use Archive instead of Delete to keep tasks.

### Q: Can I change the owner of a task?
A: Not currently. You can invite collaborators with Admin role instead.

### Q: How many tasks can I create?
A: Unlimited! There's no limit on tasks or categories.

### Q: Can I use TodoList offline?
A: Not currently. Internet connection required.

### Q: How do I cancel a collaboration invitation I sent?
A: Go to task → Collaborators → Remove the invited user.

### Q: Can I export archived tasks?
A: Yes, use the export feature and select "Include Archived".

### Q: How do I change the default categories?
A: Go to Categories page and edit/delete the default ones.

### Q: Can I import tasks from other apps?
A: Import feature coming soon. Currently supports export only.

---

## 🆘 TROUBLESHOOTING

### Common Issues

**Problem: Can't login**
- Check email and password
- Verify email address (check spam folder)
- Try password reset

**Problem: Tasks not updating**
- Refresh page (F5 or ⌘R)
- Check internet connection
- Clear browser cache

**Problem: Notifications not showing**
- Enable notifications in browser
- Check notification settings
- Allow notifications permission

**Problem: Export not downloading**
- Check pop-up blocker
- Try different browser
- Check download folder

---

## 💡 TIPS & TRICKS

### Productivity Tips

1. **Use Priorities Wisely**
   - High: Urgent and important
   - Medium: Important but not urgent
   - Low: Nice to have

2. **Organize with Categories**
   - Create categories for different life areas
   - Use colors to identify quickly
   - Keep categories simple

3. **Set Realistic Due Dates**
   - Add buffer time
   - Break large tasks into smaller ones
   - Review and adjust regularly

4. **Use Tags Effectively**
   - Tag related tasks
   - Create searchable labels
   - Use consistent naming

5. **Review Regularly**
   - Check dashboard daily
   - Review reports weekly
   - Archive completed tasks monthly

### Keyboard Efficiency

- Learn keyboard shortcuts
- Use quick search (`⌘K`)
- Navigate with Tab key
- Use Enter to submit forms

### Collaboration Best Practices

- Communicate via comments
- Set clear roles
- Review shared tasks regularly
- Keep team informed

---

## 📞 SUPPORT

**Need Help?**
- Email: support@todolist.com
- Documentation: /docs
- FAQ: /faq
- Community Forum: /community

**Report a Bug:**
- Email: bugs@todolist.com
- Include: Steps to reproduce, screenshots, error messages

**Feature Requests:**
- Email: features@todolist.com
- Describe: What feature, why needed, use case

---

## 🔄 UPDATES

**Version History:**
- v1.0.0 (Nov 2025) - Initial release
  - Task management
  - Categories
  - Collaboration
  - Reports
  - Export

**Coming Soon:**
- Mobile apps (iOS/Android)
- Calendar integration
- Recurring tasks automation
- Advanced analytics
- Custom themes
- Team workspaces

---

**Happy Task Managing! 🎉**

For the latest updates and tips, visit our blog or follow us on social media.
