# Comic Reader WordPress Theme

Theme WordPress đọc truyện tranh với hiệu ứng đẹp, sử dụng Bootstrap, JavaScript, AJAX và jQuery.

## Cài đặt

1. Tải theme lên thư mục '/wp-content/themes/' của WordPress
2. Kích hoạt theme trong phần Appearance > Themes
3. Tạo menu và gán vào vị trí "Primary Menu"

## Cấu trúc Danh mục

Để sử dụng theme đúng cách, bạn cần thiết lập cấu trúc danh mục như sau:

1. Tạo danh mục chính có tên "Truyện Tranh" (slug: truyen-tranh)
2. Tạo các danh mục con của "Truyện Tranh" cho từng bộ truyện (ví dụ: Naruto, One Piece, Dragon Ball...)
3. Thêm hình đại diện cho mỗi danh mục truyện (sử dụng trường "Category Image")
4. Mỗi bài viết là một chapter của truyện, thuộc danh mục truyện tương ứng

## Sử dụng Shortcode

### 1. Hiển thị danh sách truyện

[comic_list columns="3" limit="6"]


Tham số:
- 'columns': Số cột hiển thị (mặc định: 3)
- 'limit': Số lượng truyện hiển thị (mặc định: -1, hiển thị tất cả)

### 2. Hiển thị hình ảnh truyện

[comic_images ids="123,124,125"]


hoặc

[comic_images] https://example.com/image1.jpg| https://example.com/image2.jpg| https://example.com/image3.jpg [/comic_images]


Tham số:
- 'ids': Danh sách ID của các hình ảnh đã tải lên WordPress

### 3. Hiển thị thông tin truyện

[comic_info title="Tên truyện" author="Tác giả" artist="Họa sĩ" status="Đang cập nhật" genres="Hành động, Phiêu lưu" summary="Tóm tắt nội dung truyện..."]


Tham số:
- 'title': Tên truyện
- 'author': Tên tác giả
- 'artist': Tên họa sĩ
- 'status': Trạng thái truyện
- 'genres': Thể loại truyện
- 'summary': Tóm tắt nội dung

### 4. Hiển thị danh sách chapter

[comic_chapters category_id="123" limit="10"]


Tham số:
- 'category_id': ID của danh mục truyện
- 'limit': Số lượng chapter hiển thị (mặc định: -1, hiển thị tất cả)

## Đăng Chapter Mới

1. Tạo bài viết mới
2. Chọn danh mục truyện tương ứng
3. Thêm shortcode '[comic_images]' và tải lên các hình ảnh của chapter
4. Đặt tiêu đề là tên chapter (ví dụ: "Chapter 1: Khởi đầu")
5. Xuất bản bài viết

## Tùy Chỉnh Theme

Bạn có thể tùy chỉnh theme bằng cách:

1. Chỉnh sửa file 'comic-style.css' để thay đổi giao diện
2. Chỉnh sửa file 'comic-script.js' để thay đổi hiệu ứng
3. Chỉnh sửa các file template PHP để thay đổi cấu trúc trang

## Hỗ trợ

Nếu bạn gặp vấn đề hoặc cần hỗ trợ, vui lòng liên hệ qua email: admin@teamcodedao.com