-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 13, 2019 lúc 05:58 AM
-- Phiên bản máy phục vụ: 10.1.37-MariaDB
-- Phiên bản PHP: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cnstudios`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bills`
--

CREATE TABLE `bills` (
  `id_bill` int(11) NOT NULL,
  `id_members` int(15) NOT NULL,
  `ten_members` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `oauth_uid` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ngay_bd` date NOT NULL,
  `ngay_kt` date NOT NULL,
  `gia_phong` float NOT NULL,
  `so_dien` float NOT NULL,
  `so_nuoc` float NOT NULL,
  `phu_thu` float NOT NULL,
  `ghi_chu` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tong_tien` float NOT NULL,
  `id_home` int(11) NOT NULL,
  `thanh_toan` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Chưa',
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `bills`
--

INSERT INTO `bills` (`id_bill`, `id_members`, `ten_members`, `oauth_uid`, `ngay_bd`, `ngay_kt`, `gia_phong`, `so_dien`, `so_nuoc`, `phu_thu`, `ghi_chu`, `tong_tien`, `id_home`, `thanh_toan`, `date_modified`) VALUES
(3, 7, 'Chí Nguyễn', '112670847863514524109', '2019-03-01', '2019-04-01', 750000, 13, 5, 0, 'hi', 818000, 6, 'Xong', '2019-04-25 18:02:37'),
(4, 8, 'Lâm Đình Khoa', '112670847863514524109', '2019-03-01', '2019-04-01', 800000, 20, 6, 30000, 'Đóng tiền sớm nghe em :)', 924000, 7, 'Xong', '2019-04-25 18:25:48'),
(5, 7, 'Chí Nguyễn', '112670847863514524109', '2019-04-03', '2019-05-03', 750000, 10, 10, 0, 'hello', 830000, 6, 'Xong', '2019-04-25 21:45:52'),
(9, 8, 'Lâm Đình Khoa', '112670847863514524109', '2019-04-01', '2019-05-01', 800000, 20, 5, 30000, 'hi', 920000, 7, 'Chưa', '2019-05-02 13:31:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `homes`
--

CREATE TABLE `homes` (
  `id_home` int(11) NOT NULL,
  `oauth_uid` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tennhatro` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vi_tri` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `coordinates` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gia_phong` int(11) NOT NULL,
  `gia_dien` int(11) NOT NULL,
  `gia_nuoc` int(11) NOT NULL,
  `phu_thu` int(11) DEFAULT NULL,
  `mo_ta` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `loai_nha_tro` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `khu_vuc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `anh_bia` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `homes`
--

INSERT INTO `homes` (`id_home`, `oauth_uid`, `status`, `tennhatro`, `vi_tri`, `coordinates`, `gia_phong`, `gia_dien`, `gia_nuoc`, `phu_thu`, `mo_ta`, `loai_nha_tro`, `khu_vuc`, `anh_bia`, `date_modified`) VALUES
(6, '112670847863514524109', 'Pending', 'Nhà trọ Thành Trung', '124/22, Đường 3/2, Xuân Khánh, Ninh Kiều, Cần Thơ', '10.00097, 105.77053', 750000, 3500, 4500, 10000, '<p>Nhà trọ sinh viên Thành Trung</p>', 'Bình thường', 'Cần Thơ', 'https://cdn.vietnammoi.vn/stores/news_dataimages/thuandx/112016/28/10/1048_nha-tro.jpg', '2019-04-25 01:01:53'),
(7, '112670847863514524109', 'Verified', 'Nhà trọ Minh Anh 1', 'Đường 3/2, Xuân Khánh, Ninh Kiều, Cần Thơ', '10.04053, 105.74650', 800000, 3500, 4000, 30000, '<p>Nhà trọ Minh Anh</p><p><img src=\"https://raovat.sgp1.digitaloceanspaces.com/2018/07/11/6916599_fe23cc04edb25b014d7e98a91faeee33.jpeg\" style=\"width: 25%;\"><br></p>', 'Nhà trọ nữ', 'Cần Thơ', 'https://raovat.sgp1.digitaloceanspaces.com/2018/07/11/6916599_fe23cc04edb25b014d7e98a91faeee33.jpeg', '2019-04-25 01:04:35'),
(8, '112670847863514524109', 'Verified', 'Nhà trọ Thành Hưng', '202/22, Đường Trần Hưng Đạo, Xuân Khánh, Ninh Kiều, Cần Thơ', '10.00978, 105.75139', 800000, 3500, 4000, 30000, '<p style=\"text-align: center; \"><b style=\"color: rgb(255, 0, 0);\">Không gian thoáng mát thoải mái</b></p><p style=\"text-align: center; \"><img style=\"width: 600px;\" src=\"https://news.landber.com/uploads/images/tim-thue-nha-tro-duoi-1-trieu-tai-ha-noi-danh-cho-sinh-vien-1.jpg\"></p><p style=\"text-align: center; \"><span style=\"color: rgb(0, 0, 255);\">Phòng sạch sẽ</span></p><p style=\"text-align: center; \"><img style=\"width: 700px;\" src=\"https://news.mogi.vn/wp-content/uploads/2017/07/sinh-vien-tim-nha-tro-tren-duong-nao-tphcm1.jpg\"><span style=\"color: rgb(0, 0, 255);\"><br></span></p>', 'Bình thường', 'Cần Thơ', 'https://static2.cafeland.vn/static01/sgd/images/member/realestate_new/5fbda49f87deffafc30bb24502856c21/21012016/1453388218/12468062-809448795826513-1655436376-n-1453363104.jpg', '2019-04-26 17:34:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `members`
--

CREATE TABLE `members` (
  `id_members` int(11) NOT NULL,
  `oauth_uid` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_home` int(11) NOT NULL,
  `tennhatro` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ten_members` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `que_quan` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sdt` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phong` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ngaybd` date NOT NULL,
  `ngaythemuser` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `members`
--

INSERT INTO `members` (`id_members`, `oauth_uid`, `id_home`, `tennhatro`, `ten_members`, `que_quan`, `sdt`, `phong`, `ngaybd`, `ngaythemuser`) VALUES
(7, '112670847863514524109', 6, 'Nhà trọ Thành Trung', 'Chí Nguyễn', 'Cà Mau', '0962959848', '07', '2019-04-24', '2019-04-25 01:05:42'),
(8, '112670847863514524109', 7, 'Nhà trọ Minh Anh 1', 'Lâm Đình Khoa', 'Đồng Tháp', '0945654456', '07', '2019-04-25', '2019-04-25 01:06:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `polylines`
--

CREATE TABLE `polylines` (
  `id_polyline` int(11) NOT NULL,
  `name_polyline` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_coord` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `end_coord` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `oauth_uid` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Verified',
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `polylines`
--

INSERT INTO `polylines` (`id_polyline`, `name_polyline`, `start_coord`, `end_coord`, `oauth_uid`, `status`, `date_modified`) VALUES
(1, 'Hẻm mới', '10.02813, 105.77272', '10.02675, 105.77305', '112219725808585896549', 'Verified', '0000-00-00 00:00:00'),
(2, 'Đường D1', '10.02984, 105.77225', '10.02958, 105.77417', '112219725808585896549', 'Verified', '0000-00-00 00:00:00'),
(4, 'Đường 3', '10.03440, 105.75605', '10.03341, 105.75744', '112219725808585896549', 'Verified', '2019-05-05 16:17:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `oauth_provider` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `oauth_uid` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `thanhpho_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sdt_user` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `diachi_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mota_user` text COLLATE utf8_unicode_ci,
  `rank` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `oauth_provider`, `oauth_uid`, `first_name`, `last_name`, `thanhpho_user`, `sdt_user`, `diachi_user`, `mota_user`, `rank`, `email`, `gender`, `locale`, `picture`, `link`, `created`, `modified`) VALUES
(6, 'google', '112670847863514524109', 'Chí', 'Nguyễn', 'Cần Thơ', '0968888999', '124/22, Đường 3/2, Xuân Khánh, Ninh Kiều, Cần Thơ', ' Xin chào tất cả mọi người!', 'Member', 'nguyenb1507385@student.ctu.edu.vn', 'male', 'vi', 'https://lh4.googleusercontent.com/-vsEJBu_XGQ4/AAAAAAAAAAI/AAAAAAAAAT4/oC_CT8LeROc/photo.jpg', 'https://plus.google.com/112670847863514524109', '2019-03-01 20:52:42', '2019-05-12 14:15:34'),
(7, 'google', '112219725808585896549', 'ANCM', 'Records', NULL, NULL, NULL, NULL, 'Admin', 'mrnpro321@gmail.com', '', 'vi', 'https://lh6.googleusercontent.com/-Jr2M_Dj5Eb0/AAAAAAAAAAI/AAAAAAAAE8g/wsLDF2XySt0/photo.jpg', 'https://plus.google.com/112219725808585896549', '2019-03-01 20:57:22', '2019-05-12 14:38:21'),
(8, 'google', '101758663347938615522', 'NCN', 'Entertainment', NULL, NULL, NULL, NULL, 'User', 'chinguyen5161@gmail.com', '', 'vi', 'https://lh4.googleusercontent.com/-QDiX_35uaJY/AAAAAAAAAAI/AAAAAAAAANY/JhrRDam-sQ4/photo.jpg', '', '2019-05-02 13:02:15', '2019-05-11 16:04:04');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id_bill`);

--
-- Chỉ mục cho bảng `homes`
--
ALTER TABLE `homes`
  ADD PRIMARY KEY (`id_home`);

--
-- Chỉ mục cho bảng `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id_members`);

--
-- Chỉ mục cho bảng `polylines`
--
ALTER TABLE `polylines`
  ADD PRIMARY KEY (`id_polyline`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bills`
--
ALTER TABLE `bills`
  MODIFY `id_bill` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `homes`
--
ALTER TABLE `homes`
  MODIFY `id_home` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `members`
--
ALTER TABLE `members`
  MODIFY `id_members` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `polylines`
--
ALTER TABLE `polylines`
  MODIFY `id_polyline` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
