# Thiết kế Database cho Website Bán Khóa Học

## Tổng quan

Database được thiết kế để hỗ trợ đầy đủ các chức năng của một website bán khóa học online, bao gồm quản lý khóa học, đăng ký, thanh toán, đánh giá, và theo dõi tiến độ học tập.

## Cấu trúc Database

### 1. Bảng `courses` - Khóa học

**Mục đích**: Lưu trữ thông tin các khóa học

**Các trường chính**:
- `id`: ID khóa học
- `instructor_id`: ID giảng viên (FK → users)
- `category_id`: ID danh mục (FK → categories)
- `title`: Tên khóa học
- `slug`: URL thân thiện
- `description`: Mô tả ngắn
- `content`: Nội dung chi tiết
- `thumbnail`: Ảnh đại diện
- `video_intro`: Video giới thiệu
- `price`: Giá gốc
- `sale_price`: Giá khuyến mãi
- `level`: Cấp độ (beginner, intermediate, advanced, all)
- `language`: Ngôn ngữ
- `duration`: Tổng thời lượng (phút)
- `lessons_count`: Số bài học
- `students_count`: Số học viên đã đăng ký
- `rating`: Đánh giá trung bình (0-5)
- `reviews_count`: Số lượt đánh giá
- `status`: Trạng thái (draft, published, archived)
- `is_featured`: Khóa học nổi bật
- `is_free`: Khóa học miễn phí
- `published_at`: Ngày xuất bản

### 2. Bảng `chapters` - Chương học

**Mục đích**: Tổ chức các bài học thành chương

**Các trường chính**:
- `id`: ID chương
- `course_id`: ID khóa học (FK → courses)
- `title`: Tên chương
- `description`: Mô tả
- `order`: Thứ tự chương
- `is_free_preview`: Cho xem trước miễn phí

### 3. Bảng `lessons` - Bài học

**Mục đích**: Lưu trữ các bài học cụ thể

**Các trường chính**:
- `id`: ID bài học
- `chapter_id`: ID chương (FK → chapters)
- `title`: Tên bài học
- `description`: Mô tả
- `type`: Loại bài học (video, text, quiz, assignment)
- `video_url`: URL video
- `video_duration`: Thời lượng video
- `content`: Nội dung bài học (cho type text)
- `order`: Thứ tự trong chương
- `is_free_preview`: Cho xem trước miễn phí
- `is_published`: Đã xuất bản

### 4. Bảng `orders` - Đơn hàng

**Mục đích**: Quản lý đơn hàng mua khóa học

**Các trường chính**:
- `id`: ID đơn hàng
- `order_number`: Mã đơn hàng (unique)
- `user_id`: ID người mua (FK → users)
- `subtotal`: Tổng tiền trước giảm giá
- `discount`: Số tiền giảm giá
- `total`: Tổng tiền sau giảm giá
- `coupon_code`: Mã giảm giá đã sử dụng
- `status`: Trạng thái (pending, processing, completed, cancelled, refunded)
- `payment_status`: Trạng thái thanh toán (pending, paid, failed, refunded)
- `payment_method`: Phương thức thanh toán
- `notes`: Ghi chú
- `completed_at`: Ngày hoàn thành

### 5. Bảng `order_items` - Chi tiết đơn hàng

**Mục đích**: Lưu trữ các khóa học trong đơn hàng

**Các trường chính**:
- `id`: ID chi tiết
- `order_id`: ID đơn hàng (FK → orders)
- `course_id`: ID khóa học (FK → courses)
- `course_title`: Tên khóa học tại thời điểm mua
- `price`: Giá tại thời điểm mua
- `sale_price`: Giá khuyến mãi tại thời điểm mua
- `final_price`: Giá cuối cùng đã trả

### 6. Bảng `payments` - Thanh toán

**Mục đích**: Quản lý các giao dịch thanh toán

**Các trường chính**:
- `id`: ID thanh toán
- `order_id`: ID đơn hàng (FK → orders)
- `transaction_id`: Mã giao dịch từ cổng thanh toán (unique)
- `amount`: Số tiền
- `method`: Phương thức thanh toán
- `status`: Trạng thái (pending, processing, completed, failed, cancelled, refunded)
- `payment_data`: Dữ liệu từ cổng thanh toán (JSON)
- `notes`: Ghi chú
- `paid_at`: Thời gian thanh toán

### 7. Bảng `enrollments` - Đăng ký khóa học

**Mục đích**: Quản lý việc đăng ký khóa học của học viên

**Các trường chính**:
- `id`: ID đăng ký
- `user_id`: ID học viên (FK → users)
- `course_id`: ID khóa học (FK → courses)
- `order_id`: ID đơn hàng (FK → orders, nullable)
- `status`: Trạng thái (active, completed, cancelled)
- `progress`: Tiến độ học tập (%)
- `completed_at`: Ngày hoàn thành
- `expires_at`: Thời hạn truy cập (nếu có)

**Ràng buộc**: Mỗi học viên chỉ có thể đăng ký 1 lần cho mỗi khóa học (unique constraint)

### 8. Bảng `reviews` - Đánh giá

**Mục đích**: Lưu trữ đánh giá và nhận xét của học viên

**Các trường chính**:
- `id`: ID đánh giá
- `user_id`: ID người đánh giá (FK → users)
- `course_id`: ID khóa học (FK → courses)
- `enrollment_id`: ID đăng ký (FK → enrollments, nullable)
- `rating`: Điểm đánh giá (1-5)
- `comment`: Nhận xét
- `is_approved`: Đã được duyệt
- `is_featured`: Đánh giá nổi bật

**Ràng buộc**: Mỗi người chỉ có thể đánh giá 1 lần cho mỗi khóa học (unique constraint)

### 9. Bảng `course_materials` - Tài liệu khóa học

**Mục đích**: Lưu trữ các file tài liệu đính kèm

**Các trường chính**:
- `id`: ID tài liệu
- `course_id`: ID khóa học (FK → courses)
- `lesson_id`: ID bài học (FK → lessons, nullable)
- `title`: Tên tài liệu
- `file_path`: Đường dẫn file
- `file_name`: Tên file gốc
- `file_type`: Loại file
- `file_size`: Kích thước file (bytes)
- `download_count`: Số lượt tải
- `order`: Thứ tự

### 10. Bảng `lesson_progress` - Tiến độ học tập

**Mục đích**: Theo dõi tiến độ học tập của từng học viên

**Các trường chính**:
- `id`: ID tiến độ
- `user_id`: ID học viên (FK → users)
- `enrollment_id`: ID đăng ký (FK → enrollments)
- `lesson_id`: ID bài học (FK → lessons)
- `is_completed`: Đã hoàn thành
- `watch_time`: Thời gian đã xem (giây)
- `progress_percent`: % đã xem (0-100)
- `completed_at`: Thời gian hoàn thành
- `last_watched_at`: Lần xem cuối

**Ràng buộc**: Mỗi học viên chỉ có 1 bản ghi tiến độ cho mỗi bài học (unique constraint)

### 11. Bảng `certificates` - Chứng chỉ

**Mục đích**: Quản lý chứng chỉ hoàn thành khóa học

**Các trường chính**:
- `id`: ID chứng chỉ
- `user_id`: ID học viên (FK → users)
- `course_id`: ID khóa học (FK → courses)
- `enrollment_id`: ID đăng ký (FK → enrollments)
- `certificate_number`: Số chứng chỉ (unique)
- `certificate_file`: File chứng chỉ (PDF)
- `issued_at`: Ngày cấp

### 12. Bảng `wishlists` - Danh sách yêu thích

**Mục đích**: Lưu trữ các khóa học học viên yêu thích

**Các trường chính**:
- `id`: ID
- `user_id`: ID học viên (FK → users)
- `course_id`: ID khóa học (FK → courses)

**Ràng buộc**: Mỗi người chỉ thêm 1 lần vào wishlist (unique constraint)

### 13. Bảng `coupons` - Mã giảm giá

**Mục đích**: Quản lý các mã giảm giá

**Các trường chính**:
- `id`: ID mã giảm giá
- `code`: Mã giảm giá (unique)
- `name`: Tên chương trình
- `description`: Mô tả
- `type`: Loại (percentage, fixed)
- `value`: Giá trị giảm giá
- `min_amount`: Đơn hàng tối thiểu
- `max_discount`: Giảm giá tối đa (cho loại percentage)
- `usage_limit`: Số lần sử dụng tối đa
- `used_count`: Số lần đã sử dụng
- `usage_limit_per_user`: Số lần sử dụng tối đa mỗi user
- `applicable_courses`: Danh sách khóa học áp dụng (JSON, null = tất cả)
- `starts_at`: Bắt đầu từ
- `expires_at`: Hết hạn vào
- `is_active`: Đang hoạt động

### 14. Bảng `coupon_usages` - Lịch sử sử dụng mã giảm giá

**Mục đích**: Theo dõi việc sử dụng mã giảm giá

**Các trường chính**:
- `id`: ID
- `coupon_id`: ID mã giảm giá (FK → coupons)
- `user_id`: ID người dùng (FK → users)
- `order_id`: ID đơn hàng (FK → orders)
- `discount_amount`: Số tiền đã giảm

## Quan hệ giữa các bảng

```
users (Giảng viên/Học viên)
  ├── courses (1-n) - Giảng viên tạo khóa học
  ├── enrollments (1-n) - Học viên đăng ký
  ├── orders (1-n) - Người mua
  ├── reviews (1-n) - Người đánh giá
  ├── lesson_progress (1-n) - Tiến độ học tập
  ├── certificates (1-n) - Chứng chỉ nhận được
  ├── wishlists (1-n) - Danh sách yêu thích
  └── coupon_usages (1-n) - Sử dụng mã giảm giá

categories
  └── courses (1-n) - Danh mục khóa học

courses
  ├── chapters (1-n) - Chương học
  ├── enrollments (1-n) - Đăng ký
  ├── order_items (1-n) - Chi tiết đơn hàng
  ├── reviews (1-n) - Đánh giá
  ├── course_materials (1-n) - Tài liệu
  ├── certificates (1-n) - Chứng chỉ
  └── wishlists (1-n) - Yêu thích

chapters
  └── lessons (1-n) - Bài học

lessons
  ├── course_materials (1-n) - Tài liệu
  └── lesson_progress (1-n) - Tiến độ học

orders
  ├── order_items (1-n) - Chi tiết đơn hàng
  ├── payments (1-n) - Thanh toán
  ├── enrollments (1-n) - Đăng ký
  └── coupon_usages (1-n) - Sử dụng mã giảm giá

enrollments
  ├── lesson_progress (1-n) - Tiến độ học
  ├── certificates (1-n) - Chứng chỉ
  └── reviews (1-n) - Đánh giá

coupons
  └── coupon_usages (1-n) - Lịch sử sử dụng
```

## Các tính năng được hỗ trợ

1. ✅ Quản lý khóa học với nhiều chương và bài học
2. ✅ Hệ thống đăng ký và thanh toán
3. ✅ Đánh giá và xếp hạng khóa học
4. ✅ Theo dõi tiến độ học tập chi tiết
5. ✅ Tài liệu đính kèm cho khóa học/bài học
6. ✅ Chứng chỉ hoàn thành khóa học
7. ✅ Danh sách yêu thích
8. ✅ Mã giảm giá với nhiều tùy chọn
9. ✅ Hỗ trợ nhiều phương thức thanh toán
10. ✅ Xem trước miễn phí (free preview)
11. ✅ Quản lý giảng viên và học viên
12. ✅ Phân loại khóa học theo danh mục

## Lưu ý khi sử dụng

1. **Thứ tự migration**: Các migration đã được sắp xếp theo thứ tự phụ thuộc. Chạy `php artisan migrate` để tạo tất cả các bảng.

2. **Soft Deletes**: Một số bảng sử dụng soft deletes để có thể khôi phục dữ liệu sau khi xóa.

3. **Indexes**: Các trường thường được tìm kiếm đã được đánh index để tối ưu hiệu suất.

4. **Foreign Keys**: Tất cả các foreign key đều có `onDelete` được cấu hình phù hợp (cascade hoặc set null).

5. **Unique Constraints**: Các ràng buộc unique đảm bảo tính toàn vẹn dữ liệu (ví dụ: mỗi học viên chỉ đăng ký 1 lần cho mỗi khóa học).

## Bước tiếp theo

Sau khi chạy migrations, bạn cần:
1. Tạo các Model tương ứng cho các bảng
2. Tạo các Repository và Controller
3. Tạo các Request Validation
4. Tạo các Seeder để có dữ liệu mẫu
5. Xây dựng API/Views cho frontend

