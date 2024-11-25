<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Bảng blog_categories
                DB::table('blog_categories')->insert([
                    ['name' => 'Sản phẩm'],
                    ['name' => 'Công ty'],
                    ['name' => 'Chính sách'],
                ]);
        
        
                // Bảng blogs
                DB::table('blogs')->insert([
                    ['title' => 'Khám Phá Bộ Sưu Tập Áo Thun Mùa Hè 2024', 'content' => 'Mùa hè 2024 đang đến gần và chúng tôi mang đến cho bạn bộ sưu tập áo thun mới nhất với thiết kế năng động và trẻ trung. Áo thun từ chất liệu cotton cao cấp, thấm hút mồ hôi tốt, phù hợp cho các hoạt động ngoài trời. Bạn có thể lựa chọn từ nhiều màu sắc tươi sáng và họa tiết độc đáo', 'image_url' => 'https://img.tripi.vn/cdn-cgi/image/width=700,height=700/https://gcs.tripi.vn/public-tripi/tripi-feed/img/477085NsZ/anh-mo-ta.png', 'blog_category_id' => 1],
                    ['title' => 'Tự Tin Với Bộ Sưu Tập Quần Jean Cực Chất Cho Mùa Thu Đông', 'content' => 'Quần jean không bao giờ lỗi mốt! Hãy sẵn sàng cho mùa thu đông 2024 với những mẫu quần jean siêu bền và thời trang. Bộ sưu tập này không chỉ mang đến sự thoải mái mà còn giúp bạn tự tin hơn trong mọi hoàn cảnh. Từ những chiếc quần skinny đến những mẫu jean dáng rộng, chúng tôi có đủ phong cách cho mọi cá tính.', 'image_url' => 'https://leika.vn/wp-content/uploads/2023/12/Me-man-10-cach-phoi-do-voi-quan-jean-ong-suong-mua-dong-sieu-hack-dang-9-800x418.jpg', 'blog_category_id' => 1],
                    ['title' => 'Đón Xuân Với Những Chiếc Áo Khoác Dành Cho Phái Nữ', 'content' => 'Áo khoác mùa xuân 2024 mang đến cho bạn sự ấm áp và phong cách. Được làm từ chất liệu vải chống gió và giữ nhiệt, những chiếc áo khoác này giúp bạn giữ ấm mà vẫn không mất đi vẻ thời trang. Kiểu dáng hiện đại với các chi tiết nhấn nhá, thích hợp cho cả những buổi dạo phố hay đi làm.', 'image_url' => 'https://images2.thanhnien.vn/Uploaded/hainq/2022_03_05/anh-3-204.jpg', 'blog_category_id' => 1],
                    ['title' => 'Những Mẫu Váy Xinh Dành Cho Dịp Lễ Tết', 'content' => 'Lễ Tết là dịp để bạn diện những bộ đồ thật đặc biệt. Với bộ sưu tập váy mới này, chúng tôi mang đến cho bạn những lựa chọn hoàn hảo cho các buổi tiệc, lễ hội. Từ những chiếc váy xòe nhẹ nhàng đến váy ôm sát quyến rũ, tất cả đều được thiết kế để bạn nổi bật trong mọi ánh nhìn.', 'image_url' => 'https://cdn.tgdd.vn/Files/2021/02/01/1324524/nhung-kieu-vay-lien-thoi-trang-cho-ban-gai-tha-ho-dien-don-tet-202201151206392237.jpg', 'blog_category_id' => 1],
                    ['title' => 'MENS STYLE: Cập Nhật Bộ Sưu Tập Quần Áo Mới Nhất Mùa Thu 2024', 'content' => 'MENS STYLE tự hào giới thiệu bộ sưu tập quần áo mới nhất mùa Thu 2024. Chúng tôi mang đến những mẫu quần áo thời trang, từ áo sơ mi thanh lịch, quần jean đến áo khoác sang trọng, phù hợp cho mọi phong cách và dịp. Đến MENS STYLE để khám phá các thiết kế tinh tế, mang đến sự thoải mái và phong cách cho phái mạnh.', 'image_url' => 'https://files.dientuungdung.vn/sites/default/files/1113/bo-suu-tap_.png', 'blog_category_id' => 2],
                    ['title' => 'Phá Cách Với Những Chiếc Áo Sơ Mi Cổ Điển Từ MENS STYLE', 'content' => 'Áo sơ mi là một trong những món đồ không thể thiếu trong tủ đồ của mỗi người đàn ông. Tại MENS STYLE, chúng tôi luôn cập nhật những mẫu áo sơ mi cổ điển nhưng không kém phần hiện đại. Với chất liệu cao cấp và kiểu dáng phù hợp với mọi hoàn cảnh, những chiếc áo sơ mi này sẽ là lựa chọn lý tưởng cho bạn.', 'image_url' => 'https://tqq.com.vn/hm_content/uploads/images-baiviet/pha-cach-voi-ao-so-mi-nam-cham-bi/Ao-so-mi-nam-cham-bi-11.jpg', 'blog_category_id' => 2],
                    ['title' => 'Cách Phối Đồ Cực Chất Với Quần Jean Từ MENS STYLE', 'content' => 'Quần jean là món đồ không thể thiếu trong tủ quần áo của các quý ông. Với bộ sưu tập quần jean mới từ MENS STYLE, bạn có thể thoải mái lựa chọn giữa các kiểu dáng từ slim fit đến straight leg, đảm bảo sự thoải mái và dễ dàng phối đồ. Chất liệu vải bền đẹp và màu sắc đa dạng, quần jean MENS STYLE sẽ giúp bạn luôn tự tin trong mọi hoàn cảnh.', 'image_url' => 'https://file.hstatic.net/200000362771/article/cach_phoi_do_quan_jean_nam_bcc25cc8e26c42d9bb52736529bb61e3_grande.jpg', 'blog_category_id' => 2],
                    ['title' => 'Tạo Dựng Phong Cách Nam Tính Với Áo Khoác MENS STYLE', 'content' => 'Áo khoác là phụ kiện không thể thiếu trong những ngày se lạnh. MENS STYLE mang đến những mẫu áo khoác sang trọng và mạnh mẽ, giúp bạn giữ ấm mà vẫn giữ được vẻ ngoài thời trang. Chất liệu vải chống gió, dễ phối đồ, thích hợp cho mọi hoàn cảnh từ đi làm đến dạo phố.', 'image_url' => 'https://file.hstatic.net/200000887901/file/ao-khoac-du-nam_20_5_.png', 'blog_category_id' => 2],  
                    ['title' => 'Chính Sách Hỗ Trợ Khách Hàng 24/7', 'content' => 'MENS STYLE cam kết mang đến sự hỗ trợ tuyệt vời cho khách hàng với chính sách hỗ trợ 24/7. Nếu bạn gặp bất kỳ vấn đề gì liên quan đến đơn hàng, sản phẩm hoặc dịch vụ, đội ngũ chăm sóc khách hàng của chúng tôi luôn sẵn sàng giải đáp thắc mắc và hỗ trợ bạn qua nhiều kênh như điện thoại, email và các nền tảng mạng xã hội. Chúng tôi đảm bảo rằng mọi câu hỏi của bạn sẽ được trả lời nhanh chóng và đầy đủ.', 'image_url' => 'https://cgvtelecom.vn/wp-content/uploads/2023/03/24-7-la-gi.jpg', 'blog_category_id' => 3],
                    ['title' => 'Chính Sách Cập Nhật Sản Phẩm Mới Liên Tục', 'content' => 'MENS STYLE luôn nỗ lực mang đến cho khách hàng những sản phẩm mới, chất lượng và thời trang nhất. Chính sách cập nhật sản phẩm của chúng tôi đảm bảo rằng những sản phẩm mới sẽ luôn được ra mắt kịp thời và cập nhật nhanh chóng trên website. Bạn có thể theo dõi và đăng ký nhận thông báo để không bỏ lỡ bất kỳ bộ sưu tập hoặc ưu đãi hấp dẫn nào từ MENS STYLE.', 'image_url' => 'https://phuongnamvina.com/img_data/images/san-pham-moi-la-gi-quy-trinh-phat-trien-san-pham-moi834155602072.jpg', 'blog_category_id' => 3],
                    ['title' => 'Chính Sách Bảo Mật Và An Toàn Thông Tin Khách Hàng Tại MENS STYLE', 'content' => 'MENS STYLE luôn bảo vệ quyền lợi và thông tin cá nhân của khách hàng. Chính sách bảo mật của chúng tôi cam kết không chia sẻ bất kỳ thông tin cá nhân nào của bạn với bên thứ ba mà không có sự đồng ý rõ ràng. Tất cả thông tin thanh toán và giao dịch đều được bảo vệ an toàn tuyệt đối qua hệ thống mã hóa SSL. Bạn có thể yên tâm mua sắm trực tuyến tại MENS STYLE mà không lo về vấn đề bảo mật.', 'image_url' => 'https://dodunghocsinh.com/Upload/banner/chinh-sach-bao-mat-1.png', 'blog_category_id' => 3],
                    ['title' => 'Chính Sách Thanh Toán Linh Hoạt Tại MENS STYLE', 'content' => 'Tại MENS STYLE, chúng tôi hiểu rằng việc thanh toán tiện lợi và an toàn là một yếu tố quan trọng khi mua sắm. Chính sách thanh toán của chúng tôi bao gồm nhiều lựa chọn như thanh toán trực tuyến qua thẻ tín dụng, thẻ ghi nợ, ví điện tử, và thanh toán khi nhận hàng (COD). Tất cả các giao dịch đều được mã hóa và bảo mật, giúp bạn yên tâm khi thanh toán mọi lúc, mọi nơi', 'image_url' => 'https://secomm.vn/wp-content/uploads/2021/11/Phuong-thuc-thanh-toan-thuong-mai-dien-tu.png', 'blog_category_id' => 3],
                    ['title' => 'Phong Cách Thời Trang Nam Cổ Điển Với Bộ Sưu Tập Áo Sơ Mi', 'content' => 'MENS STYLE luôn nỗ lực mang đến cho khách hàng những sản phẩm mới, chất lượng và thời trang nhất. Chính sách cập nhật sản phẩm của chúng tôi đảm bảo rằng những sản phẩm mới sẽ luôn được ra mắt kịp thời và cập nhật nhanh chóng trên website. Bạn có thể theo dõi và đăng ký nhận thông báo để không bỏ lỡ bất kỳ bộ sưu tập hoặc ưu đãi hấp dẫn nào từ MENS STYLE.', 'image_url' => 'https://www.acfc.com.vn/acfc_wp/wp-content/uploads/2021/07/phong-cach-vintage-nam-7.png', 'blog_category_id' => 1],
                    ['title' => 'Khám Phá Bộ Sưu Tập Áo Thun Thể Thao MENS STYLE', 'content' => 'MENS STYLE không chỉ chú trọng vào thời trang công sở mà còn mang đến những mẫu áo thun thể thao chất lượng cao. Những chiếc áo thun này không chỉ giúp bạn thoải mái vận động mà còn mang đến vẻ ngoài năng động, trẻ trung. Hãy khám phá bộ sưu tập áo thun thể thao mới nhất của chúng tôi và sẵn sàng cho những hoạt động ngoài trời.', 'image_url' => 'https://media.routine.vn/prod/media/nhung-dieu-ban-can-biet-ve-ao-thun-the-thao-f78i.webp', 'blog_category_id' => 2],
                    ['title' => 'Chính Sách Vận Chuyển MENS STYLE', 'content' => 'MENS STYLE cung cấp dịch vụ vận chuyển nhanh chóng và an toàn đến mọi khu vực. Chúng tôi cam kết giao hàng trong vòng 3-5 ngày làm việc tùy thuộc vào vị trí của khách hàng. Chúng tôi cũng cung cấp theo dõi đơn hàng để khách hàng có thể kiểm tra tình trạng vận chuyển bất cứ lúc nào.','image_url' => 'https://xsdffu.com/wp-content/uploads/2024/09/chinh-sach-van-chuyen.jpg', 'blog_category_id' => 3],
                ]);  
    }
}
