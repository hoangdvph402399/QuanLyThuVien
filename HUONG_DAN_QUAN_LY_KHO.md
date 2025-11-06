# HƯỚNG DẪN SỬ DỤNG PHẦN QUẢN LÝ KHO TRONG ADMIN

## MỤC LỤC
1. [Tổng quan](#tổng-quan)
2. [Truy cập vào phần quản lý kho](#truy-cập-vào-phần-quản-lý-kho)
3. [Dashboard kho](#dashboard-kho)
4. [Quản lý sách trong kho](#quản-lý-sách-trong-kho)
5. [Phiếu nhập kho](#phiếu-nhập-kho)
6. [Phân bổ trưng bày](#phân-bổ-trưng-bày)
7. [Lịch sử giao dịch](#lịch-sử-giao-dịch)
8. [Báo cáo kho](#báo-cáo-kho)
9. [Các tính năng khác](#các-tính-năng-khác)

---

## TỔNG QUAN

Phần quản lý kho cho phép bạn:
- Quản lý tất cả các bản copy sách trong kho
- Theo dõi vị trí, tình trạng và trạng thái của từng cuốn sách
- Nhập kho mới qua phiếu nhập kho
- Chuyển sách giữa các vị trí trong kho
- Phân bổ sách từ kho ra trưng bày
- Theo dõi lịch sử tất cả các giao dịch kho
- Xem báo cáo và thống kê tổng quan

---

## TRUY CẬP VÀO PHẦN QUẢN LÝ KHO

### Cách truy cập:
1. Đăng nhập vào hệ thống với quyền Admin
2. Vào menu **Admin** → **Quản lý kho** hoặc truy cập trực tiếp URL: `/admin/inventory`

### Quyền truy cập:
- **Xem kho**: Cần quyền `view-books`
- **Chỉnh sửa kho**: Cần quyền `edit-books`

---

## DASHBOARD KHO

### Truy cập:
- URL: `/admin/inventory-dashboard`
- Menu: **Admin** → **Quản lý kho** → **Dashboard**

### Thông tin hiển thị:
- **Tổng số sách**: Tổng số cuốn sách trong kho
- **Sách có sẵn**: Số sách đang có sẵn để cho mượn
- **Sách đang mượn**: Số sách đang được mượn
- **Sách hư hỏng**: Số sách có tình trạng hư hỏng
- **Sách mất**: Số sách bị mất
- **Giao dịch gần đây**: 10 giao dịch kho gần nhất
- **Thống kê theo loại giao dịch**: Biểu đồ phân loại các loại giao dịch

---

## QUẢN LÝ SÁCH TRONG KHO

### 1. Xem danh sách sách trong kho

**Truy cập**: `/admin/inventory`

**Tính năng**:
- Xem danh sách tất cả sách trong kho (20 sách/trang)
- Tìm kiếm theo:
  - **Mã vạch**: Nhập mã vạch để tìm sách
  - **Tên sách**: Tìm kiếm theo tên sách
  - **Sách**: Chọn từ dropdown danh sách sách
- Lọc theo:
  - **Trạng thái**: Có sẵn, Đang mượn, Mất, Hỏng, Thanh lý
  - **Tình trạng**: Mới, Tốt, Trung bình, Cũ, Hỏng
  - **Vị trí**: Nhập vị trí trong kho

**Thông tin hiển thị cho mỗi sách**:
- Mã vạch
- Tên sách
- Vị trí
- Tình trạng
- Trạng thái
- Ngày nhập kho
- Người tạo

### 2. Thêm sách mới vào kho

**Truy cập**: `/admin/inventory/create` hoặc click nút **"Thêm sách mới"**

**Các bước**:
1. Chọn **Sách** từ dropdown (bắt buộc)
2. Nhập **Mã vạch** (tùy chọn - nếu để trống sẽ tự động tạo)
   - Format: `BK000001`, `BK000002`, ...
3. Nhập **Vị trí** trong kho (bắt buộc)
   - Ví dụ: `Kệ A1`, `Kho chính - Tầng 1`, `Khu vực A - Phòng 101`
4. Chọn **Tình trạng** (bắt buộc):
   - **Mới**: Sách mới hoàn toàn
   - **Tốt**: Sách còn tốt, sử dụng được
   - **Trung bình**: Sách đã qua sử dụng nhưng còn dùng được
   - **Cũ**: Sách cũ nhưng vẫn sử dụng được
   - **Hỏng**: Sách bị hư hỏng
5. Chọn **Trạng thái** (bắt buộc):
   - **Có sẵn**: Sách có sẵn để cho mượn
   - **Đang mượn**: Sách đang được mượn
   - **Mất**: Sách bị mất
   - **Hỏng**: Sách bị hỏng
   - **Thanh lý**: Sách đã thanh lý
6. Nhập **Giá mua** (tùy chọn): Giá mua cuốn sách này
7. Chọn **Ngày mua** (tùy chọn): Ngày mua sách
8. Nhập **Ghi chú** (tùy chọn): Các thông tin bổ sung

**Lưu ý**:
- Sau khi thêm sách, hệ thống sẽ tự động tạo một giao dịch "Nhập kho" trong lịch sử
- Mã vạch phải là duy nhất trong hệ thống

### 3. Xem chi tiết sách

**Truy cập**: Click vào sách trong danh sách hoặc `/admin/inventory/{id}`

**Thông tin hiển thị**:
- Thông tin cơ bản: Mã vạch, Tên sách, Vị trí, Tình trạng, Trạng thái
- Thông tin tài chính: Giá mua, Ngày mua
- Thông tin người tạo
- **Lịch sử giao dịch**: Tất cả các giao dịch liên quan đến cuốn sách này
- **Các thao tác**: Chỉnh sửa, Xóa, Chuyển kho, Sửa chữa

### 4. Chỉnh sửa thông tin sách

**Truy cập**: Click nút **"Chỉnh sửa"** trong trang chi tiết hoặc `/admin/inventory/{id}/edit`

**Các trường có thể chỉnh sửa**:
- Vị trí
- Tình trạng
- Trạng thái
- Giá mua
- Ngày mua
- Ghi chú

**Lưu ý**:
- Không thể thay đổi Sách và Mã vạch sau khi đã tạo
- Nếu có thay đổi về vị trí, tình trạng hoặc trạng thái, hệ thống sẽ tự động tạo giao dịch "Kiểm kê"

### 5. Xóa sách khỏi kho

**Truy cập**: Click nút **"Xóa"** trong trang chi tiết

**Lưu ý**:
- Hệ thống sẽ tạo giao dịch "Thanh lý" trước khi xóa
- Hành động này không thể hoàn tác
- Chỉ nên xóa khi sách thực sự không còn trong hệ thống

### 6. Chuyển kho (Di chuyển sách giữa các vị trí)

**Truy cập**: Trong trang chi tiết sách, click nút **"Chuyển kho"**

**Các bước**:
1. Nhập **Vị trí mới** (bắt buộc)
2. Nhập **Lý do chuyển** (tùy chọn)
3. Nhập **Ghi chú** (tùy chọn)
4. Click **"Xác nhận chuyển"**

**Lưu ý**:
- Hệ thống sẽ tự động tạo giao dịch "Chuyển kho" với thông tin vị trí cũ và mới
- Tình trạng và trạng thái sách không thay đổi khi chuyển kho

### 7. Sửa chữa sách

**Truy cập**: Trong trang chi tiết sách, click nút **"Sửa chữa"**

**Các bước**:
1. Chọn **Tình trạng sau sửa chữa** (bắt buộc):
   - Mới, Tốt, Trung bình, Cũ, Hỏng
2. Nhập **Lý do sửa chữa** (tùy chọn)
3. Nhập **Ghi chú** (tùy chọn)
4. Click **"Xác nhận sửa chữa"**

**Lưu ý**:
- Chức năng này dùng để cập nhật tình trạng sách sau khi sửa chữa
- Hệ thống sẽ tạo giao dịch "Sửa chữa" với thông tin tình trạng trước và sau

---

## PHIẾU NHẬP KHO

### 1. Tạo phiếu nhập kho mới

**Truy cập**: `/admin/inventory-receipts/create` hoặc từ menu **Quản lý kho** → **Phiếu nhập kho** → **Tạo mới**

**Mục đích**: Nhập nhiều bản copy của cùng một cuốn sách vào kho trong một lần

**Các bước**:
1. Chọn **Sách** từ dropdown (bắt buộc)
2. Nhập **Số lượng** sách cần nhập (bắt buộc, tối thiểu 1)
3. Chọn **Ngày nhập** (bắt buộc)
4. Nhập **Vị trí lưu trữ** (bắt buộc)
   - Ví dụ: `Kho chính - Tầng 1`, `Khu vực A`
5. Chọn **Loại lưu trữ** (bắt buộc):
   - **Kho**: Lưu trong kho
   - **Trưng bày**: Lưu ở khu vực trưng bày
6. Nhập **Đơn giá** (tùy chọn): Giá mua một cuốn
7. Nhập **Nhà cung cấp** (tùy chọn): Tên nhà cung cấp
8. Nhập **Ghi chú** (tùy chọn)

**Lưu ý**:
- Mỗi cuốn sách sẽ được tự động tạo mã vạch riêng
- Tất cả sách trong phiếu nhập sẽ có:
  - Tình trạng: **Mới**
  - Trạng thái: **Có sẵn**
  - Ngày mua: Ngày nhập phiếu
- Hệ thống sẽ tự động tính tổng giá = Đơn giá × Số lượng
- Số phiếu nhập sẽ được tự động tạo (format: `PNK-YYYYMMDD-XXXX`)

### 2. Xem danh sách phiếu nhập kho

**Truy cập**: `/admin/inventory-receipts`

**Tính năng**:
- Xem danh sách tất cả phiếu nhập kho (20 phiếu/trang)
- Lọc theo:
  - **Trạng thái**: Chờ duyệt, Đã duyệt, Đã từ chối
  - **Sách**: Chọn sách cụ thể
  - **Từ ngày / Đến ngày**: Lọc theo khoảng thời gian

**Thông tin hiển thị**:
- Số phiếu
- Tên sách
- Số lượng
- Đơn giá / Tổng giá
- Ngày nhập
- Người nhập
- Trạng thái

### 3. Xem chi tiết phiếu nhập kho

**Truy cập**: Click vào phiếu nhập trong danh sách hoặc `/admin/inventory-receipts/{id}`

**Thông tin hiển thị**:
- Thông tin phiếu: Số phiếu, Ngày nhập, Sách, Số lượng, Giá cả
- Thông tin lưu trữ: Vị trí, Loại lưu trữ
- Danh sách các cuốn sách đã được tạo từ phiếu này
- Thông tin người nhập và người duyệt (nếu có)
- Trạng thái phiếu

**Các thao tác**:
- **Phê duyệt**: Nếu phiếu đang ở trạng thái "Chờ duyệt"
- **Từ chối**: Nếu phiếu đang ở trạng thái "Chờ duyệt" (cần nhập lý do)

### 4. Phê duyệt / Từ chối phiếu nhập kho

**Khi nào cần**: Nếu hệ thống yêu cầu phê duyệt phiếu nhập kho trước khi sách được nhập vào kho

**Phê duyệt**:
1. Vào chi tiết phiếu nhập
2. Click nút **"Phê duyệt"**
3. Phiếu sẽ chuyển sang trạng thái "Đã duyệt"
4. Sách đã được nhập vào kho (đã tạo từ khi tạo phiếu)

**Từ chối**:
1. Vào chi tiết phiếu nhập
2. Click nút **"Từ chối"**
3. Nhập **Lý do từ chối**
4. Phiếu sẽ chuyển sang trạng thái "Đã từ chối"
5. Sách vẫn trong kho nhưng phiếu được đánh dấu là từ chối

---

## PHÂN BỔ TRƯNG BÀY

### 1. Tạo phân bổ trưng bày mới

**Truy cập**: `/admin/inventory-display-allocations/create`

**Mục đích**: Chuyển sách từ kho ra khu vực trưng bày để độc giả xem

**Các bước**:
1. Chọn **Sách** từ dropdown (bắt buộc)
2. Nhập **Số lượng** cần trưng bày (bắt buộc, tối thiểu 1)
3. Nhập **Khu vực trưng bày** (bắt buộc)
   - Ví dụ: `Khu vực A`, `Kệ trưng bày số 1`, `Gian hàng chính`
4. Chọn **Ngày bắt đầu trưng bày** (bắt buộc)
5. Chọn **Ngày kết thúc trưng bày** (tùy chọn)
6. Nhập **Ghi chú** (tùy chọn)

**Lưu ý**:
- Hệ thống sẽ kiểm tra số lượng sách có sẵn trong kho
- Nếu không đủ số lượng, sẽ báo lỗi và không cho phép tạo phân bổ
- Sách được chuyển từ kho sang trưng bày sẽ:
  - Thay đổi `storage_type` từ "Kho" sang "Trưng bày"
  - Thay đổi `location` sang khu vực trưng bày
  - Tạo giao dịch "Xuất kho" cho mỗi cuốn sách

### 2. Xem danh sách phân bổ trưng bày

**Truy cập**: `/admin/inventory-display-allocations`

**Tính năng**:
- Xem danh sách tất cả phân bổ trưng bày (20 phân bổ/trang)
- Lọc theo:
  - **Sách**: Chọn sách cụ thể
  - **Khu vực trưng bày**: Tìm kiếm theo khu vực
  - **Trạng thái**: Đang trưng bày, Đã kết thúc

**Thông tin hiển thị**:
- Tên sách
- Số lượng trưng bày
- Số lượng còn trong kho
- Khu vực trưng bày
- Ngày bắt đầu / Kết thúc
- Người phân bổ

### 3. Thu hồi sách từ trưng bày về kho

**Truy cập**: Trong danh sách phân bổ, click nút **"Thu hồi"** hoặc **"Trả về kho"**

**Các bước**:
1. Nhập **Vị trí kho** muốn trả sách về (bắt buộc)
2. Nhập **Ghi chú** (tùy chọn)
3. Click **"Xác nhận thu hồi"**

**Lưu ý**:
- Tất cả sách đang trưng bày sẽ được chuyển về kho
- Sách sẽ được cập nhật:
  - `storage_type` từ "Trưng bày" về "Kho"
  - `location` về vị trí kho mới
  - Tạo giao dịch "Nhập kho" cho mỗi cuốn sách
- Phân bổ sẽ được cập nhật:
  - `quantity_on_display` = 0
  - `display_end_date` = Ngày hiện tại
  - `quantity_in_stock` = Số lượng mới trong kho

---

## LỊCH SỬ GIAO DỊCH

### Truy cập:
- URL: `/admin/inventory-transactions`
- Menu: **Quản lý kho** → **Lịch sử giao dịch**

### Tính năng:
- Xem tất cả các giao dịch liên quan đến kho
- Lọc theo:
  - **Loại giao dịch**: Nhập kho, Xuất kho, Chuyển kho, Kiểm kê, Sửa chữa, Thanh lý
  - **Người thực hiện**: Chọn người dùng cụ thể
  - **Từ ngày / Đến ngày**: Lọc theo khoảng thời gian

### Thông tin hiển thị:
- **Loại giao dịch**: 
  - **Nhập kho**: Sách được nhập vào kho
  - **Xuất kho**: Sách được xuất khỏi kho (thường để trưng bày)
  - **Chuyển kho**: Sách được di chuyển giữa các vị trí
  - **Kiểm kê**: Cập nhật thông tin sách
  - **Sửa chữa**: Cập nhật tình trạng sau sửa chữa
  - **Thanh lý**: Sách được xóa khỏi hệ thống
- **Mã vạch / Tên sách**: Cuốn sách liên quan
- **Vị trí**: Vị trí cũ và mới (nếu có)
- **Tình trạng**: Tình trạng trước và sau (nếu có)
- **Trạng thái**: Trạng thái trước và sau (nếu có)
- **Lý do**: Lý do thực hiện giao dịch
- **Người thực hiện**: Người thực hiện giao dịch
- **Thời gian**: Ngày giờ thực hiện

### Lợi ích:
- Theo dõi toàn bộ lịch sử di chuyển và thay đổi của sách
- Kiểm tra và đối soát kho
- Phát hiện các thay đổi bất thường
- Báo cáo và thống kê

---

## BÁO CÁO KHO

### Truy cập:
- URL: `/admin/inventory-report`
- Menu: **Quản lý kho** → **Báo cáo**

### Thông tin hiển thị:

#### 1. Thống kê tổng quan:
- **Tổng sách trong kho**: Số sách có `storage_type = "Kho"`
- **Tổng sách trưng bày**: Số sách có `storage_type = "Trưng bày"`
- **Có sẵn trong kho**: Sách trong kho có trạng thái "Có sẵn"
- **Có sẵn trưng bày**: Sách trưng bày có trạng thái "Có sẵn"
- **Đang mượn từ kho**: Sách trong kho đang được mượn
- **Đang mượn từ trưng bày**: Sách trưng bày đang được mượn

#### 2. Thống kê phiếu nhập:
- **Tổng phiếu nhập**: Tổng số phiếu nhập kho
- **Phiếu chờ duyệt**: Số phiếu đang chờ phê duyệt
- **Phiếu nhập gần đây**: 10 phiếu nhập gần nhất

#### 3. Thống kê trưng bày:
- **Phân bổ đang hoạt động**: Số phân bổ trưng bày đang diễn ra
- **Phân bổ gần đây**: 10 phân bổ trưng bày gần nhất

### Mục đích:
- Tổng quan về tình hình kho
- Phát hiện các vấn đề (thiếu sách, sách hỏng nhiều, ...)
- Lập kế hoạch nhập kho
- Báo cáo cho cấp trên

---

## CÁC TÍNH NĂNG KHÁC

### 1. Quét mã vạch

**Truy cập**: Có thể tích hợp vào các trang quản lý kho

**Chức năng**:
- Quét mã vạch để tìm kiếm nhanh sách trong kho
- Trả về thông tin chi tiết của sách nếu tìm thấy

**API Endpoint**: `POST /admin/inventory/scan-barcode`
- **Tham số**: `barcode` (mã vạch)
- **Response**: Thông tin sách dạng JSON

### 2. Đồng bộ lên trang chủ

**Truy cập**: Có thể tích hợp vào menu hoặc trang quản lý kho

**Chức năng**:
- Đồng bộ hóa tất cả sách trong kho lên trang chủ
- Đảm bảo tất cả sách trong kho có `trang_thai = "active"` để hiển thị trên trang chủ

**API Endpoint**: `POST /admin/inventory/sync-to-homepage`

**Lưu ý**:
- Chỉ cập nhật các sách chưa active
- Hiển thị số lượng sách đã cập nhật và đã active sẵn

---

## CÁC TRẠNG THÁI VÀ TÌNH TRẠNG

### Trạng thái (Status):
- **Có sẵn**: Sách có sẵn để cho mượn
- **Đang mượn**: Sách đang được độc giả mượn
- **Mất**: Sách bị mất
- **Hỏng**: Sách bị hỏng (không thể sử dụng)
- **Thanh lý**: Sách đã được thanh lý

### Tình trạng (Condition):
- **Mới**: Sách mới hoàn toàn, chưa sử dụng
- **Tốt**: Sách còn tốt, sử dụng bình thường
- **Trung bình**: Sách đã qua sử dụng, còn dùng được
- **Cũ**: Sách cũ nhưng vẫn sử dụng được
- **Hỏng**: Sách bị hư hỏng, cần sửa chữa hoặc thanh lý

### Loại lưu trữ (Storage Type):
- **Kho**: Sách được lưu trong kho
- **Trưng bày**: Sách được đưa ra khu vực trưng bày

---

## LƯU Ý QUAN TRỌNG

1. **Mã vạch**: Phải là duy nhất, không được trùng lặp
2. **Quyền truy cập**: Đảm bảo bạn có đủ quyền trước khi thực hiện các thao tác
3. **Giao dịch tự động**: Hệ thống tự động tạo giao dịch cho mọi thay đổi, không thể xóa
4. **Phân bổ trưng bày**: Kiểm tra số lượng sách trong kho trước khi tạo phân bổ
5. **Xóa sách**: Hành động không thể hoàn tác, chỉ xóa khi thực sự cần thiết
6. **Phiếu nhập kho**: Sách được tạo ngay khi tạo phiếu, phê duyệt chỉ để đánh dấu trạng thái

---

## HỖ TRỢ

Nếu gặp vấn đề hoặc cần hỗ trợ:
- Liên hệ quản trị viên hệ thống
- Kiểm tra lại quyền truy cập của tài khoản
- Xem lại lịch sử giao dịch để theo dõi các thay đổi

---

**Phiên bản**: 1.0  
**Ngày cập nhật**: {{ date('d/m/Y') }}

