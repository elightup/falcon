# Cache

Tính năng này tạo HTML cache cho 1 trang.

## Cách hoạt động

- Tạo 1 thư mục `cache` nằm trong folder `wp-content/uploads`, trong đó mỗi 1 trang sẽ là 1 file có dạng `hash.html`, trong đó `hash` là mã hoá `md5` của URL. URL sẽ được chuẩn hoá trước khi hash: xoá các query param và fragment, chỉ giữ path.
- Khi nhận được request, plugin làm các công việc sau:
	- Kiểm tra xem trang đang request có cache chưa. Nếu có rồi thì flush ra trình duyệt và ngừng xử lý tiếp theo.
	- Nếu chưa có thì dùng output buffering để lưu HTMl của trang, ghi vào thư mục `cache` và flush ra trình duyệt.
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

## Cấu trúc thư mục

Code được đặt trong folder `src/Components/Cache`, trong đó:

- File `Manager.php` dùng để
	- Xử lý khi tắt/bật tính năng:
		- Thêm/xoá constant `WP_CACHE` vào file `wp-config.php`
		- Tạo/xoá thư mục cache
		- Tạo/xoá file `wp-content/advanced-cache.php`. File này chỉ đơn giản là tham chiếu đến code nằm trong file `Serve.php`. Mục đích là giữ phần code này dễ cập nhật khi cần thay đổi.
	- Xoá cache khi có sự thay đổi trên website
- File `Serve.php` dùng để:
	- Serve cache khi nhận được yêu cầu. Khi serve cache thì các hàm, plugin, theme của WordPress chưa được load, nên chỉ xử lý điều kiện theo PHP thuần.
	- Lưu cache vào file `.html` nếu chưa có. Khi lưu cache qua output buffering, chỉ cần khai báo hàm callback cho `ob_start`, vì ở hook `shutdown`, WordPres sẽ flush cache và gọi tới hàm này. Do đó, lúc này toàn bộ các hàm, plugin, theme của WordPress đều đã được load, nên có thể kiểm tra điều kiện thông qua các template tags được.

## Tham khảo

- https://github.com/webatleten/advanced-cache