# Cache

Tính năng này tạo HTML cache cho 1 trang.

## Cách hoạt động

- Tạo 1 thư mục `cache` nằm trong folder `wp-content/uploads`, trong đó mỗi 1 trang sẽ là 1 file có dạng `hash.html`, trong đó `hash` là mã hoá `md5` của URL. URL sẽ được chuẩn hoá trước khi hash: xoá các query param và fragment, chỉ giữ path.
- Khi nhận được request, plugin làm các công việc sau:
	- Kiểm tra xem trang đang request có cache chưa. Nếu có rồi thì flush ra trình duyệt và ngừng xử lý tiếp theo.
	- Nếu chưa có thì dùng output buffering để lưu HTMl của trang, ghi vào thư mục `cache` và flush ra trình duyệt.
- Khi flush ra trình duyệt thì thêm header để biết ngày giờ tạo cache
- Mỗi khi có 1 thay đổi trên website thì xoá toàn bộ thư mục cache. Các thay đổi bao gồm (có thể nhiều hơn):
	- Thêm, xoá, sửa, cập nhật trạng thái bài viết, term, comment
	- Đổi theme
	- Bật, tắt plugin
	- Chỉnh sửa template trong editor
	- Thay đổi widget, menu
	- Update WordPress

## Các trường hợp không cache:

- Trang kết quả tìm kiếm
- Trang 404
- Trang bài viết có password
- User đã login
- Request dạng ajax, REST API, XMLRPC
- Request method không phải là `GET`
- Có session

## Tham khảo

- https://github.com/webatleten/advanced-cache