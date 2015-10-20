## Mục đích
1. Tích hợp File Browser vào CKEditor
2. File Browse cho form cơ bản

## Cài đặt
- Kiểm tra file `php.ini` đã bỏ comment dòng `extension=php_fileinfo.dll`

- Thêm vào `require` trong `composer.json` đoạn code:
```
"studio-42/elfinder": "2.x-dev",
 "barryvdh/elfinder-flysystem-driver": "~0.1”
```
rồi chạy lệnh `composer update -vvv`

- Sửa file `routing` trong phần `/admin`
```
'/finder/browse' => 'Admin\FinderController:browse:admin_finder_browse',
'/finder/connector' => 'Admin\FinderController:connector:admin_finder_connector’,
```

- Thêm file `FinderController.php` vào `app/controller/Admin/`
- Thêm file `finder.html.twig` vào `app/view/admin/utils/`
- Copy thư mực `elfinder` vào `web/assets/`
- Tạo thư mục `upload` và chmod 777 trong `web/`

## Cách dùng
### I. Tích hợp vào CKEditor:
1. Bằng JavaScript
```
<textarea name="content" id="content" rows="10" cols="60"></textarea>
<script>
		CKEDITOR.replace('content', {
			filebrowserBrowseUrl : "{{ url('admin_finder_browse') }}"
		});
</script>
```
2. Bằng `config.js` trong `ckeditor/`
```
CKEDITOR.editorConfig = function( config ) {
	// Config Sth
	config.filebrowserBrowseUrl = '/admin/finder/browse';
};
```

### II. File Browser trong form
```
<img id="img" src="/assets/admin/image/no-image.png"/> <!-- để hiển thị image được chọn -->
<input id="imgURL" type="text"/> <!-- để lưu URL của image -->
<button class="btn-browse-img" type="button">Choose</button> <!-- để browse image -->

<script>
	$(document).ready(function() {
        window.admin_finder_browse = "{{ url('admin_finder_browse') }}";
	});
	$(document).ready(function() {
	$('.btn-browse-img').on('click', function() {
	        var txt = $(this);
	        var fileupload = function(file) {
	            $('#img').attr('src', file.url);
	            $('#imgURL').attr('value', file.url)
	        };
	        window.upload_handle = fileupload;
	        var w = window.open(window.admin_finder_browse, "", "width=600, height=400");
	        w.onbeforeunload = function(){
	            window.upload_handle = false;
	        };
	    });
	});
</script>
```

DONE~!!! (╯°□°)╯︵ ┻━┻