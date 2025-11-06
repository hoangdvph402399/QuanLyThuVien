# Library Management - Quick Start

## 1) Requirements
- PHP 8.x, Composer
- MySQL/MariaDB
- Node (nếu cần build frontend)
- Mail SMTP (để gửi email notifications)

## 2) Setup
1. Sao chép `.env.example` thành `.env` và cập nhật:
   - DB_*  (database, username, password)
   - MAIL_* (SMTP)
2. Cài đặt phụ thuộc:
```bash
composer install
php artisan key:generate
```
3. Migrate và seed:
```bash
php artisan migrate
php artisan db:seed
```

## 3) Cấu hình nghiệp vụ
- `config/library.php` chứa:
  - rental_flat, deposit_verified, deposit_unverified
  - loan_days, late_fee_per_day
  - ship_fee_default, reservation_hold_minutes
  - reminder_due_soon_days, reminder_overdue_days

## 4) Cron/Scheduler
Chạy scheduler (dev):
```bash
php artisan schedule:work
```
Tác vụ:
- `library:housekeeping` (hourly): đánh dấu overdue, no-show
- `library:reminders` (daily 09:00): gửi nhắc sắp đến hạn và quá hạn

## 5) Quyền & Tài khoản
- Seeder `RoleSeeder` tạo roles `admin/librarian/warehouse/user`.
- User đầu tiên sẽ được gán role `admin`.
- Dùng Sanctum token để gọi API yêu cầu `auth:sanctum`.

## 6) API chính (MVP)
- Pricing: `GET /api/pricing/quote?book_ids[]=1&delivery_type=ship&kyc_status=guest`
- Inventory: `POST /api/inventory/intake`, `POST /api/inventory/move`, `GET /api/inventory/stock`
- Loans: `POST /api/loans` → `POST /api/loans/{id}/confirm` → `.../pickup` → `.../return` → `.../close`
- KYC: `POST /api/users/verify`, `GET /api/users/me/verification`
- Seating: `GET /api/spaces/availability`, `POST /api/reservations`, `POST /api/reservations/{id}/check-in`
- Reports (admin): `GET /api/reports/stock|loans|utilization`

## 7) Audit
- Middleware `ApiAuditLogger` ghi log vào bảng `audit_logs` cho mọi request API.

## 8) Backup/Restore (nếu module có sẵn)
- Tham khảo các route backup trong `routes/api.php` nhóm admin.

## 9) Triển khai (Prod)
- Thiết lập Supervisor/Task Scheduler để chạy `schedule:run` mỗi phút.
- Bật queue worker nếu dùng hàng đợi mail.
- Cấu hình backup tự động và theo dõi logs.


