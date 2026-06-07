# Task Management Guide

## Overview
Startups can now fully manage their tasks: create, view, edit, update, and delete.

## Features Added

### 1. Edit Task ✅
- Edit task title, description, skills, points, and stipend
- Pre-filled form with current values
- Only task owner can edit
- Validation with error messages

### 2. Delete Task ✅
- Delete tasks that have no applications
- Confirmation dialog before deletion
- Cannot delete tasks with existing applications (safety feature)
- Only task owner can delete

### 3. View Task Management ✅
- Edit and delete buttons on startup dashboard
- Edit and delete buttons on task detail page
- Status badges showing task state
- Application count display

## How to Use

### Creating a Task

1. **Login as Startup**
   ```
   Email: startup@example.com
   Password: password
   ```

2. **Navigate to Dashboard**
   - Click "Post New Task" button

3. **Fill the Form**
   - Title: Required
   - Description: Required
   - Skills: Select at least 1
   - Reward Points: Required (minimum 0)
   - Stipend: Optional

4. **Submit**
   - Click "Post Task"
   - Redirected to dashboard with success message

### Editing a Task

**Method 1: From Dashboard**
1. Go to Startup Dashboard
2. Find the task in "My Tasks" section
3. Click "Edit" link
4. Update the form fields
5. Click "Update Task"

**Method 2: From Task Detail Page**
1. View any of your tasks
2. Click "Edit Task" button at the top
3. Update the form fields
4. Click "Update Task"

**What You Can Edit:**
- ✅ Task title
- ✅ Task description
- ✅ Required skills (add/remove)
- ✅ Reward points
- ✅ Stipend amount

**What You Cannot Edit:**
- ❌ Task owner (always you)
- ❌ Task status (managed by workflow)
- ❌ Existing applications

### Deleting a Task

**Method 1: From Dashboard**
1. Go to Startup Dashboard
2. Find the task in "My Tasks" section
3. Click "Delete" link (only visible if no applications)
4. Confirm deletion in popup
5. Task is permanently deleted

**Method 2: From Task Detail Page**
1. View your task
2. Click "Delete Task" button (only visible if no applications)
3. Confirm deletion in popup
4. Task is permanently deleted

**Important Notes:**
- ⚠️ You can only delete tasks with ZERO applications
- ⚠️ Once deleted, the task cannot be recovered
- ⚠️ If a task has applications, you'll see "Cannot delete (has applications)"

## Security & Permissions

### Authorization Rules
1. **Only the task owner can edit/delete their tasks**
   - Other startups cannot modify your tasks
   - Students cannot edit any tasks
   - Admins can moderate but not edit

2. **Cannot delete tasks with applications**
   - Protects student work and applications
   - Prevents data loss
   - Maintains workflow integrity

3. **Automatic ownership check**
   - System verifies you own the task
   - Returns 403 error if unauthorized
   - No manual permission management needed

## Routes Added

```
GET    /tasks/{id}/edit    - Show edit form
PUT    /tasks/{id}         - Update task
DELETE /tasks/{id}         - Delete task
```

## UI Elements

### Dashboard View
```
Task Title
Applications: 3 | Points: 100
[View Details] [Edit] [Cannot delete (has applications)]
Status: Posted
```

### Task Detail Page (Owner View)
```
[Edit Task] [Delete Task]  (buttons at top)

Task Title
Description...
Skills...
Points & Stipend...
```

## Validation Rules

### Edit/Update Validation
- Title: Required, max 255 characters
- Description: Required
- Skills: At least 1 skill required
- Reward Points: Required, integer, minimum 0
- Stipend: Optional, numeric, minimum 0

### Delete Validation
- Must be task owner
- Task must have 0 applications
- Task must exist

## Error Messages

### Success Messages
- ✅ "Task created successfully"
- ✅ "Task updated successfully"
- ✅ "Task deleted successfully"

### Error Messages
- ❌ "Unauthorized action" (not your task)
- ❌ "Cannot delete task with existing applications"
- ❌ Validation errors (shown per field)

## Workflow Integration

### Task Lifecycle
1. **Posted** - Can edit/delete (if no applications)
2. **Has Applications** - Can edit, cannot delete
3. **Closed** - Can view only (admin action)
4. **Moderated** - Can view only (admin action)

### Application Impact
- Tasks with 0 applications: Full control (edit/delete)
- Tasks with 1+ applications: Can edit, cannot delete
- Protects student work and maintains data integrity

## Testing Checklist

### Create Task
- [ ] Can access create form
- [ ] Form validation works
- [ ] Task appears in dashboard
- [ ] Task appears in public list

### Edit Task
- [ ] Can access edit form from dashboard
- [ ] Can access edit form from task page
- [ ] Form pre-filled with current values
- [ ] Skills checkboxes show current selection
- [ ] Can update all fields
- [ ] Changes saved correctly
- [ ] Redirects to dashboard with success message

### Delete Task
- [ ] Delete button visible for tasks with 0 applications
- [ ] Delete button hidden for tasks with applications
- [ ] Confirmation dialog appears
- [ ] Task deleted from database
- [ ] Redirects to dashboard with success message
- [ ] Cannot delete other users' tasks

### Authorization
- [ ] Cannot edit other startups' tasks
- [ ] Cannot delete other startups' tasks
- [ ] Students cannot edit any tasks
- [ ] Proper 403 error for unauthorized access

## Common Issues & Solutions

### Issue: "Cannot delete task with existing applications"
**Reason:** Task has one or more applications
**Solution:** This is by design. You cannot delete tasks that students have applied to.

### Issue: "Unauthorized action"
**Reason:** Trying to edit/delete someone else's task
**Solution:** You can only edit/delete your own tasks.

### Issue: Edit button not showing
**Reason:** Not logged in as the task owner
**Solution:** Login as the startup that created the task.

### Issue: Changes not saving
**Reason:** Validation errors
**Solution:** Check error messages above form fields. All required fields must be filled.

### Issue: Skills not updating
**Reason:** No skills selected
**Solution:** Select at least one skill before submitting.

## Best Practices

1. **Before Deleting:**
   - Make sure you really want to delete
   - Check if task has applications
   - Consider closing instead of deleting

2. **When Editing:**
   - Update task details if requirements change
   - Adjust points if task complexity changes
   - Add/remove skills as needed

3. **Communication:**
   - If editing a task with applications, consider notifying applicants
   - Major changes might affect student work

4. **Task Status:**
   - Keep tasks "posted" if accepting applications
   - Admin can moderate if needed

## Quick Reference

| Action | Location | Requirements |
|--------|----------|--------------|
| Create | Dashboard → "Post New Task" | Startup role |
| Edit | Dashboard → "Edit" or Task Page → "Edit Task" | Task owner, Startup role |
| Delete | Dashboard → "Delete" or Task Page → "Delete Task" | Task owner, 0 applications |
| View | Tasks list or direct link | Anyone |

## Summary

You now have complete control over your tasks:
- ✅ Create new tasks
- ✅ Edit existing tasks
- ✅ Delete tasks (without applications)
- ✅ View all your tasks
- ✅ Manage applications
- ✅ Protected by authorization

All changes are validated and secured! 🎉
