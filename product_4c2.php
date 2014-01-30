<?php
	session_start();
	if(!($connect = mysqli_connect('localhost', 'shop', 'shop', 'shop')))
	{
		exit;
	}
	if(!mysqli_query($connect,
		'SET NAMES utf8'))
	{
		exit;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Sample Shop</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="http://localhost/index.css" />
		<script src="http://localhost/index.js"></script>
	</head>
	<body>
		<div id="page">
			<div id="head">
				<div id="notice">
					<a href="">
						이 사이트는 포트폴리오 목적으로 만들어진 e-shop 입니다.
					</a>
				</div>
				<div id="navigation">
					<?php
						if(!isset($_SESSION['member_id']))
						{
							echo '<a href="http://localhost/login_1.php">로그인</a>';
							echo '<a href="http://localhost/join_1.php">회원가입</a>';
						}
						else
						{
							echo '<a href="http://localhost/logout.php">로그아웃</a>';
							echo '<a href="http://localhost/change_a1.php">회원정보</a>';
						}
					?>
					<a href="http://localhost/order_1.php">주문배송</a>
					<a href="http://localhost/cart_1.php">장바구니</a>
				</div>
				<div id="title">
					<a href="http://localhost/">Sample Shop</a>
				</div>
				<div id="category">
					<?php
						if(!($result = mysqli_query($connect,
							'SELECT * FROM categorys')))
						{
							exit;
						}
						while($row = mysqli_fetch_array($result))
						{
							echo '<a href="http://localhost/product_1.php?category_id=' . urlencode($row['category_id']) . '">'
								. htmlentities($row['category_title'], ENT_QUOTES, 'UTF-8') . '</a>';
						} 
					?>
				</div>
			</div>
			<div id="body">
				<?php
					$order_id = date('YmdHis');
					if(!($result = mysqli_query($connect,
						'SELECT product_title, product_price FROM products WHERE product_id = '
						. mysqli_real_escape_string($connect, urldecode($_GET['product_id'])))))
					{
						exit;
					}
					$row = mysqli_fetch_array($result);
					$order_title = $row['product_title'];
					$payment_price = $row['product_price'] * urldecode($_GET['item_number']);
					if($payment_price < 50000)
					{
						$payment_price += 5000;
					}
					if(!($result = mysqli_query($connect,
						'INSERT INTO orders VALUES ('
						. mysqli_real_escape_string($connect, $order_id) . ', "'
						. mysqli_real_escape_string($connect, date('Y-m-d')) . '", "'
						. mysqli_real_escape_string($connect, $order_title) . '", "'
						. mysqli_real_escape_string($connect, '주문접수') . '", "'
						. mysqli_real_escape_string($connect, $_POST['request_person']) . '", "'
						. mysqli_real_escape_string($connect, $_POST['request_phone']) . '", "'
						. mysqli_real_escape_string($connect, $_POST['request_email']) . '", "'
						. mysqli_real_escape_string($connect, '배송 전') . '", "'
						. mysqli_real_escape_string($connect, $_POST['delivery_person']) . '", "'
						. mysqli_real_escape_string($connect, $_POST['delivery_zipcode']) . '", "'
						. mysqli_real_escape_string($connect, $_POST['delivery_address']) . '", "'
						. mysqli_real_escape_string($connect, $_POST['delivery_phone']) . '", "'
						. mysqli_real_escape_string($connect, '입금 전') . '", "'
						. mysqli_real_escape_string($connect, $_POST['payment_person']) . '", '
						. mysqli_real_escape_string($connect, $payment_price) . ', NULL, "'
						. mysqli_real_escape_string($connect, sha1($_POST['nonmember_password'])) . '")')))
					{
						exit;
					}
					if(!($result = mysqli_query($connect,
						'INSERT INTO items VALUES ('
						. mysqli_real_escape_string($connect, urldecode($_GET['item_number'])) . ', '
						. mysqli_real_escape_string($connect, urldecode($_GET['product_id'])) . ', '
						. mysqli_real_escape_string($connect, $order_id) . ')')))
					{
						exit;
					}
					echo '<p>주문되었습니다.</p>';
				?>
			</div>
			<div id="foot">
				<div id="info1">
					<a href="">이용약관</a>
					<a href="">개인정보취급방침</a>
					<a href="">청소년보호정책</a>
					<a href="">사업자정보확인</a>
				</div>
				<div id="info2"> 
					<a href="http://localhost/intro.php">
						<span>(주)Simple Shop</span><br />
						<span>주소 : 서울시 강남구 테헤란로 152</span><br />
						<span>대표이사 : 임재봉</span><br />
						<span>사업자등록번호 : 220-81-83676</span><br />
						<span>통신판매업신고 : 강남 10630호</span>
					</a>
					<a href="">
						<span>고객센터</span><br />
						<span>상담시간 : 평일 오전 9시 ~ 오후 6시</span><br />
						<span>Tel : 02-635-4927</span><br />
						<span>Fax : 02-635-4927</span><br />
						<span>simpleshop@simpleshop.com</span>
					</a>
					<a href="">
						<span>무통장입금</span><br />
						<span>평일 오후 5시 일괄 확인 후 익일 배송</span><br />
						<span>예금주 : 임재봉</span><br />
						<span>농협 : 679-12-340703</span><br />
						<span>국민은행 : 080-21-0768-143</span>
					</a>
					<a href="">
						<span>우체국택배</span><br />
						<span>본 사이트는 우체국택배를 통하여 상품을 배송하고 있습니다.</span><br />
						<span>Website : http://parcel.epost.go.kr</span><br />
						<span>Tel : 1588-1300</span>
					</a>
				</div>
				<div id="copyright">
					<a href="">
						<span>Copyright (C) Lim JaeBong. All Rights Reserved.</span>
					</a>
				</div>
            </div>
        </div>
    </body>
</html>