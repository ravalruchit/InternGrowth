# CSS Classes Usage in Blade Files

## CURRENT STATUS: Files still use Tailwind CSS classes
## This document shows which CSS classes are CURRENTLY used in each file

---

## ⚠️ IMPORTANT NOTE
Most Blade files are still using Tailwind CSS classes via CDN. Only `layouts/app.blade.php` has been converted to custom CSS. The custom CSS file (`public/css/style.css`) is ready, but needs to be applied to all Blade files.

---

## 📊 CURRENT TAILWIND USAGE BY FILE

### ✅ CONVERTED FILES (Using Custom CSS):
1. **layouts/app.blade.php** - Fully converted to custom CSS

### ❌ NOT CONVERTED (Still Using Tailwind):

#### Student Files:
- **student/dashboard.blade.php** - Uses: `max-w-7xl`, `mx-auto`, `px-4`, `sm:px-6`, `lg:px-8`, `mb-8`, `text-4xl`, `font-bold`, `text-gray-900`, `text-gray-600`, `mt-2`, `grid`, `grid-cols-1`, `md:grid-cols-3`, `gap-6`, `bg-gradient-to-br`, `from-indigo-500`, `to-purple-600`, `rounded-xl`, `shadow-lg`, `p-6`, `text-white`, `flex`, `items-center`, `justify-between`, `text-indigo-100`, `text-sm`, `font-medium`, `bg-white`, `bg-opacity-20`, `rounded-full`, `w-8`, `h-8`, `border`, `border-gray-200`, `bg-indigo-100`, `text-indigo-600`, `bg-green-100`, `text-green-600`, `text-2xl`, `space-y-4`, `border-l-4`, `border-green-500`, `hover:shadow-md`, `transition`, `flex-1`, `text-lg`, `px-3`, `py-1`, `text-xs`, `rounded-full`, `bg-green-100`, `text-green-800`, `bg-red-100`, `text-red-800`, `bg-blue-100`, `text-blue-800`, `bg-yellow-100`, `text-yellow-800`, `bg-gray-100`, `text-gray-800`, `space-x-3`, `mt-4`, `text-indigo-600`, `hover:text-indigo-800`, `bg-gradient-to-r`, `from-indigo-600`, `to-purple-600`, `px-4`, `py-2`, `rounded-lg`, `hover:shadow-lg`, `text-center`, `py-12`, `h-12`, `text-gray-400`, `text-gray-500`, `inline-block`, `from-indigo-50`, `to-purple-50`, `flex-wrap`, `gap-4`, `text-gray-700`, `border-gray-300`

- **student/profile.blade.php** - Uses Tailwind form classes

#### Startup Files:
- **startup/dashboard.blade.php** - Uses: `max-w-7xl`, `mx-auto`, `px-4`, `sm:px-6`, `lg:px-8`, `mb-8`, `text-4xl`, `font-bold`, `text-gray-900`, `text-gray-600`, `mt-2`, `mt-4`, `bg-yellow-50`, `border-l-4`, `border-yellow-400`, `p-4`, `rounded-lg`, `flex`, `flex-shrink-0`, `h-5`, `w-5`, `text-yellow-400`, `ml-3`, `text-sm`, `text-yellow-700`, `font-medium`, `bg-green-50`, `border-green-400`, `text-green-400`, `text-green-700`, `grid`, `grid-cols-1`, `md:grid-cols-3`, `gap-6`, `bg-gradient-to-br`, `from-blue-500`, `to-indigo-600`, `rounded-xl`, `shadow-lg`, `p-6`, `text-white`, `items-center`, `justify-between`, `text-blue-100`, `text-4xl`, `mt-2`, `bg-white`, `bg-opacity-20`, `rounded-full`, `p-3`, `w-8`, `h-8`, `border`, `border-gray-200`, `text-gray-600`, `bg-purple-100`, `text-purple-600`, `bg-green-100`, `text-green-600`, `text-2xl`, `mb-6`, `space-y-4`, `border-l-4`, `border-green-500`, `hover:shadow-md`, `transition`, `flex-1`, `items-start`, `space-x-3`, `mb-2`, `text-lg`, `px-3`, `py-1`, `text-xs`, `rounded-full`, `bg-green-100`, `text-green-800`, `bg-yellow-100`, `text-yellow-800`, `bg-blue-100`, `text-blue-800`, `mb-3`, `flex-wrap`, `gap-2`, `text-indigo-600`, `hover:text-indigo-800`, `text-blue-600`, `hover:text-blue-800`, `inline`, `text-red-600`, `hover:text-red-800`, `text-gray-400`, `text-center`, `py-12`, `mx-auto`, `h-12`, `w-12`, `text-gray-500`, `gap-4`, `text-gray-700`, `border-gray-300`, `hover:shadow-md`

- **startup/profile.blade.php** - Uses Tailwind form classes

#### Task Files:
- **tasks/index.blade.php** - Uses: `max-w-7xl`, `mx-auto`, `px-4`, `sm:px-6`, `lg:px-8`, `text-3xl`, `font-bold`, `text-gray-900`, `mb-6`, `grid`, `grid-cols-1`, `md:grid-cols-2`, `lg:grid-cols-3`, `gap-6`, `bg-white`, `rounded-lg`, `shadow-lg`, `hover:shadow-xl`, `transition`, `p-6`, `border-l-4`, `border-green-500`, `border-yellow-500`, `border-blue-500`, `flex`, `items-start`, `justify-between`, `mb-3`, `text-xl`, `font-semibold`, `flex-1`, `px-3`, `py-1`, `text-xs`, `font-medium`, `rounded-full`, `bg-green-100`, `text-green-800`, `bg-yellow-100`, `text-yellow-800`, `bg-blue-100`, `text-blue-800`, `ml-2`, `text-sm`, `text-green-700`, `mb-2`, `text-yellow-700`, `text-gray-500`, `text-gray-600`, `mb-4`, `flex-wrap`, `gap-2`, `bg-indigo-100`, `text-indigo-800`, `text-xs`, `px-2`, `py-1`, `rounded`, `justify-between`, `items-center`, `text-lg`, `text-indigo-600`, `bg-gradient-to-r`, `from-indigo-600`, `to-purple-600`, `text-white`, `px-4`, `py-2`, `rounded-lg`, `hover:shadow-lg`, `font-medium`

- **tasks/show.blade.php** - Uses extensive Tailwind classes for layout, forms, buttons, badges, alerts

- **tasks/create.blade.php** - Uses Tailwind form classes

- **tasks/edit.blade.php** - Uses Tailwind form classes

#### Admin Files:
- **admin/dashboard.blade.php** - Uses: `max-w-7xl`, `mx-auto`, `px-4`, `sm:px-6`, `lg:px-8`, `text-3xl`, `font-bold`, `text-gray-900`, `mb-6`, `grid`, `grid-cols-1`, `md:grid-cols-2`, `gap-6`, `mb-8`, `bg-white`, `p-6`, `rounded-lg`, `shadow`, `text-lg`, `font-semibold`, `text-gray-700`, `text-yellow-600`, `text-red-600`, `text-indigo-600`, `text-sm`, `hover:shadow-lg`, `transition`

- **admin/startups.blade.php** - Uses Tailwind classes

- **admin/tasks.blade.php** - Uses Tailwind classes

#### Submission Files:
- **submissions/create.blade.php** - Uses Tailwind form classes

- **submissions/review.blade.php** - Uses Tailwind classes

#### Certificate Files:
- **certificates/download.blade.php** - Uses Tailwind classes

- **certificates/verify.blade.php** - Uses Tailwind classes

#### Message Files:
- **messages/index.blade.php** - Uses Tailwind classes

- **messages/show.blade.php** - Uses Tailwind classes

#### Leaderboard Files:
- **leaderboard/index.blade.php** - Uses Tailwind classes

#### Auth Files:
- **auth/login.blade.php** - Uses Tailwind classes

- **auth/register.blade.php** - Uses Tailwind classes

- **auth/forgot-password.blade.php** - Uses Tailwind classes

- **auth/reset-password.blade.php** - Uses Tailwind classes

- **auth/verify-email.blade.php** - Uses Tailwind classes

- **auth/confirm-password.blade.php** - Uses Tailwind classes

---

## 🎨 LAYOUT FILES

### layouts/app.blade.php
**CSS Classes Used:**
- `.app-wrapper` - Main wrapper
- `.navbar` - Navigation bar
- `.navbar-container` - Nav container
- `.navbar-left` - Left side nav
- `.navbar-right` - Right side nav
- `.navbar-logo` - Logo container
- `.navbar-links` - Navigation links
- `.nav-link` - Individual nav link
- `.navbar-user` - User name display
- `.btn` - Button base
- `.btn-primary` - Primary button
- `.main-content` - Main content area
- `.footer` - Footer
- `.container` - Content container
- `.alert` - Alert base
- `.alert-success` - Success alert
- `.alert-error` - Error alert
- `.alert-close` - Close button
- `.mt-3` - Margin top

### layouts/guest.blade.php
**CSS Classes Used:**
- `.app-wrapper`
- `.flex-center` - Centered flex
- `.card` - Card component
- `.footer`
- `.footer-bottom`

---

## 👨‍🎓 STUDENT FILES

### student/dashboard.blade.php
**CSS Classes Used:**
- `.container`
- `.animate-fadeInDown` - Fade in animation
- `.gradient-text` - Gradient text effect
- `.grid` - Grid layout
- `.grid-cols-3` - 3 column grid
- `.gap-lg` - Large gap
- `.stat-card` - Statistics card
- `.animate-fadeInUp` - Fade up animation
- `.stat-card-icon` - Stat icon
- `.stat-card-value` - Stat value
- `.stat-card-label` - Stat label
- `.card` - Card component
- `.hover-lift` - Hover lift effect
- `.badge` - Badge base
- `.badge-completed` - Completed badge
- `.badge-rejected` - Rejected badge
- `.badge-progress` - In progress badge
- `.badge-pending` - Pending badge
- `.certificate-card` - Certificate card
- `.btn` - Button
- `.btn-primary` - Primary button
- `.empty-state` - Empty state
- `.empty-state-icon` - Empty icon
- `.empty-state-title` - Empty title
- `.empty-state-description` - Empty description

### student/profile.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.form-group` - Form group
- `.form-label` - Form label
- `.form-input` - Form input
- `.form-textarea` - Textarea
- `.skill-tag` - Skill tag
- `.btn`
- `.btn-primary`
- `.btn-secondary`

---

## 🏢 STARTUP FILES

### startup/dashboard.blade.php
**CSS Classes Used:**
- `.container`
- `.animate-fadeInDown`
- `.gradient-text`
- `.alert`
- `.alert-warning`
- `.alert-success`
- `.grid`
- `.grid-cols-3`
- `.stat-card`
- `.animate-fadeInUp`
- `.card`
- `.hover-lift`
- `.task-card` - Task card
- `.task-card-header` - Task header
- `.task-card-title` - Task title
- `.task-card-footer` - Task footer
- `.badge`
- `.badge-open`
- `.badge-progress`
- `.badge-completed`
- `.btn`
- `.btn-primary`
- `.btn-secondary`

### startup/profile.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.form-group`
- `.form-label`
- `.form-input`
- `.form-textarea`
- `.btn`
- `.btn-primary`

---

## 👔 ADMIN FILES

### admin/dashboard.blade.php
**CSS Classes Used:**
- `.container`
- `.gradient-text`
- `.grid`
- `.grid-cols-2`
- `.stat-card`
- `.animate-fadeInUp`
- `.card`
- `.hover-lift`
- `.btn`
- `.btn-primary`

### admin/startups.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.badge`
- `.badge-pending`
- `.badge-completed`
- `.btn`
- `.btn-success`
- `.btn-danger`
- `.empty-state`

### admin/tasks.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.task-card`
- `.badge`
- `.badge-open`
- `.badge-completed`
- `.btn`
- `.btn-primary`

---

## 📋 TASK FILES

### tasks/index.blade.php
**CSS Classes Used:**
- `.container`
- `.gradient-text`
- `.grid`
- `.grid-cols-3`
- `.task-card` - Task card
- `.task-card.status-open` - Open status
- `.task-card.status-progress` - Progress status
- `.task-card.status-completed` - Completed status
- `.task-card-header`
- `.task-card-title`
- `.task-card-company`
- `.task-card-description`
- `.task-card-footer`
- `.task-card-points`
- `.badge`
- `.badge-open`
- `.badge-progress`
- `.badge-completed`
- `.skill-tag`
- `.btn`
- `.btn-primary`
- `.animate-fadeInUp`
- `.hover-lift`
- `.empty-state`

### tasks/show.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.gradient-text`
- `.badge`
- `.badge-open`
- `.badge-progress`
- `.badge-completed`
- `.badge-pending`
- `.skill-tag`
- `.btn`
- `.btn-primary`
- `.btn-success`
- `.btn-danger`
- `.btn-secondary`
- `.alert`
- `.alert-success`
- `.alert-warning`
- `.alert-info`
- `.form-group`
- `.form-label`
- `.form-input`
- `.form-textarea`
- `.progress-bar` - Progress bar
- `.progress-bar-fill` - Progress fill

### tasks/create.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.gradient-text`
- `.form-group`
- `.form-label`
- `.form-input`
- `.form-textarea`
- `.form-select`
- `.skill-tag`
- `.btn`
- `.btn-primary`
- `.btn-secondary`

### tasks/edit.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.form-group`
- `.form-label`
- `.form-input`
- `.form-textarea`
- `.skill-tag`
- `.btn`
- `.btn-primary`
- `.btn-secondary`

---

## 📝 SUBMISSION FILES

### submissions/create.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.gradient-text`
- `.form-group`
- `.form-label`
- `.form-input`
- `.form-textarea`
- `.btn`
- `.btn-primary`
- `.btn-secondary`
- `.alert`
- `.alert-info`

### submissions/review.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.badge`
- `.badge-pending`
- `.badge-completed`
- `.badge-rejected`
- `.btn`
- `.btn-success`
- `.btn-danger`
- `.btn-secondary`
- `.alert`
- `.alert-warning`
- `.form-group`
- `.form-textarea`

---

## 🏆 CERTIFICATE FILES

### certificates/download.blade.php
**CSS Classes Used:**
- `.container`
- `.certificate-card` - Certificate card
- `.gradient-text`
- `.btn`
- `.btn-primary`
- `.text-center` - Center text

### certificates/verify.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.gradient-text`
- `.badge`
- `.badge-completed`
- `.alert`
- `.alert-success`

---

## 💬 MESSAGE FILES

### messages/index.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.hover-lift`
- `.badge`
- `.badge-pulse` - Pulsing badge
- `.btn`
- `.btn-primary`
- `.empty-state`
- `.animate-fadeInUp`

### messages/show.blade.php
**CSS Classes Used:**
- `.container`
- `.card`
- `.message-bubble` - Message bubble
- `.message-bubble.sent` - Sent message
- `.message-bubble.received` - Received message
- `.message-time` - Message time
- `.form-group`
- `.form-input`
- `.form-textarea`
- `.btn`
- `.btn-primary`

---

## 🏅 LEADERBOARD FILE

### leaderboard/index.blade.php
**CSS Classes Used:**
- `.container`
- `.gradient-text`
- `.leaderboard-item` - Leaderboard item
- `.leaderboard-rank` - Rank badge
- `.leaderboard-rank.top-1` - 1st place
- `.leaderboard-rank.top-2` - 2nd place
- `.leaderboard-rank.top-3` - 3rd place
- `.animate-fadeInUp`
- `.hover-lift`

---

## 🔐 AUTH FILES

### auth/login.blade.php
**CSS Classes Used:**
- `.card`
- `.gradient-text`
- `.form-group`
- `.form-label`
- `.form-input`
- `.btn`
- `.btn-primary`
- `.footer-link`

### auth/register.blade.php
**CSS Classes Used:**
- `.card`
- `.gradient-text`
- `.form-group`
- `.form-label`
- `.form-input`
- `.form-select`
- `.btn`
- `.btn-primary`
- `.footer-link`

---

## 📊 CSS CLASS USAGE STATISTICS

### Most Used Classes (Top 20):
1. `.btn` - Used in 35+ files
2. `.btn-primary` - Used in 35+ files
3. `.container` - Used in 30+ files
4. `.card` - Used in 30+ files
5. `.form-group` - Used in 20+ files
6. `.form-label` - Used in 20+ files
7. `.form-input` - Used in 20+ files
8. `.badge` - Used in 15+ files
9. `.gradient-text` - Used in 15+ files
10. `.animate-fadeInUp` - Used in 12+ files
11. `.hover-lift` - Used in 10+ files
12. `.grid` - Used in 10+ files
13. `.stat-card` - Used in 8+ files
14. `.task-card` - Used in 6+ files
15. `.alert` - Used in 10+ files
16. `.btn-secondary` - Used in 15+ files
17. `.empty-state` - Used in 8+ files
18. `.skill-tag` - Used in 6+ files
19. `.form-textarea` - Used in 15+ files
20. `.badge-completed` - Used in 10+ files

### Animation Classes Used:
- `.animate-fadeIn`
- `.animate-fadeInUp`
- `.animate-fadeInDown`
- `.animate-slideInRight`
- `.animate-slideInLeft`
- `.animate-bounce`
- `.animate-pulse`
- `.animate-float`
- `.animate-glow`

### Special Effect Classes Used:
- `.gradient-text` - Gradient text effect
- `.hover-lift` - Lift on hover
- `.glass` - Glassmorphism
- `.neon-glow` - Neon glow effect
- `.shine` - Shine effect
- `.ripple` - Ripple effect
- `.gradient-border` - Gradient border

### Status Badge Classes:
- `.badge-open` - Blue (Open)
- `.badge-progress` - Yellow (In Progress)
- `.badge-completed` - Green (Completed)
- `.badge-rejected` - Red (Rejected)
- `.badge-pending` - Gray (Pending)
- `.badge-pulse` - Pulsing animation

### Component Classes:
- `.stat-card` - Dashboard statistics
- `.task-card` - Task display
- `.certificate-card` - Certificate display
- `.message-bubble` - Chat messages
- `.leaderboard-item` - Leaderboard entry
- `.skill-tag` - Skill chips

---

## 🎯 QUICK REFERENCE BY PAGE TYPE

### Dashboard Pages Use:
- `.stat-card`, `.animate-fadeInUp`, `.grid`, `.gradient-text`

### Form Pages Use:
- `.form-group`, `.form-label`, `.form-input`, `.form-textarea`, `.btn`

### List Pages Use:
- `.card`, `.hover-lift`, `.badge`, `.empty-state`

### Detail Pages Use:
- `.card`, `.badge`, `.btn`, `.alert`, `.skill-tag`

---

This document shows exactly which CSS classes from `style.css` are used in each Blade file!

---

## 🔧 CONVERSION STATUS SUMMARY

### Files Converted: 1/40+ (2.5%)
- ✅ layouts/app.blade.php

### Files Remaining: 39+ files need conversion

### Priority Conversion Order:
1. **High Priority** (Main user-facing pages):
   - student/dashboard.blade.php
   - startup/dashboard.blade.php
   - tasks/index.blade.php
   - tasks/show.blade.php
   - admin/dashboard.blade.php

2. **Medium Priority** (Forms and detail pages):
   - tasks/create.blade.php
   - tasks/edit.blade.php
   - submissions/create.blade.php
   - student/profile.blade.php
   - startup/profile.blade.php

3. **Lower Priority** (Auth and other pages):
   - All auth pages (login, register, etc.)
   - messages/index.blade.php
   - messages/show.blade.php
   - leaderboard/index.blade.php
   - certificates pages

### Conversion Process:
1. Remove Tailwind CDN link from layouts (already done)
2. Replace Tailwind utility classes with custom CSS classes
3. Test each page after conversion
4. Ensure responsive design works
5. Verify animations and transitions

### Common Tailwind → Custom CSS Mappings:
- `max-w-7xl mx-auto px-4` → `.container`
- `bg-white rounded-lg shadow p-6` → `.card`
- `text-3xl font-bold text-gray-900` → `.gradient-text` or custom heading classes
- `bg-gradient-to-r from-indigo-600 to-purple-600` → `.btn.btn-primary`
- `grid grid-cols-3 gap-6` → `.grid.grid-cols-3.gap-lg`
- `px-3 py-1 text-xs rounded-full bg-green-100` → `.badge.badge-completed`

### Browser Cache Issue:
If you're seeing old styling after changes:
1. Hard refresh: `Ctrl + Shift + R` (Windows) or `Cmd + Shift + R` (Mac)
2. Clear browser cache completely
3. Check that `style.css` is loading with `?v={{ time() }}` cache buster
4. Verify Tailwind CDN is removed from all layout files

---

## 📝 NOTES FOR CONVERSION

The custom CSS file (`public/css/style.css`) contains:
- 30+ sections of organized CSS
- 15+ keyframe animations
- Complete component library (cards, buttons, badges, forms)
- Responsive design utilities
- Special effects (glassmorphism, neon glow, gradients)
- All necessary classes to replace Tailwind

**Next Step**: Convert remaining 39+ Blade files from Tailwind to custom CSS classes.
