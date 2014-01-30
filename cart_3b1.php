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
					if(!($result = mysqli_query($connect,
						'SELECT count(*) FROM members WHERE member_id = "'
						. mysqli_real_escape_string($connect, $_POST['member_id'])
						. '" and member_password = "'
						. mysqli_real_escape_string($connect, sha1($_POST['member_password'])) . '"')))
					{
						exit;
					}
					$row = mysqli_fetch_array($result);
					if($row['count(*)'] == 1)
					{					
						$_SESSION['member_id'] = $_POST['member_id'];
						$cart = explode('.', $_COOKIE['cart']);
						for($i = 0; $i < count($cart); $i += 2)
						{
							if(!($result = mysqli_query($connect,
								'SELECT product_title, product_price FROM products WHERE product_id = '
								. mysqli_real_escape_string($connect, $cart[$i]))))
							{
								exit;
							}
							$row = mysqli_fetch_array($result);
							echo '<div>';
							echo '<p>' . htmlentities($row['product_title'], ENT_QUOTES, 'UTF-8') . '</p>';
							echo '<p>' . htmlentities($row['product_price'], ENT_QUOTES, 'UTF-8') . '원</p>';
							echo '<p>' . htmlentities($cart[$i + 1], ENT_QUOTES, 'UTF-8') . '개</p>';
							echo '</div>';
						}
						if(!($result = mysqli_query($connect,
							'SELECT member_person, member_zipcode, member_address, member_phone, member_email FROM members WHERE member_id = "'
							. mysqli_real_escape_string($connect, $_SESSION['member_id']) . '"')))
						{
							exit;
						}
						$row = mysqli_fetch_array($result);
						echo '<form action="http://localhost/cart_3a1.php" method="post">';
						echo '<div>';
						echo '<label>주문하시는 분</label>';
						echo '<input name="request_person" type="text" value="' . htmlentities($row['member_person'], ENT_QUOTES, 'UTF-8') . '" />';
						echo '<label>휴대전화</label>';
						echo '<input name="request_phone" type="text" value="' . htmlentities($row['member_phone'], ENT_QUOTES, 'UTF-8') . '" />';
						echo '<label>이메일</label>';
						echo '<input name="request_email" type="text" value="' . htmlentities($row['member_email'], ENT_QUOTES, 'UTF-8') . '" />';
						echo '</div>';
						echo '<div>';
						echo '<label>받으실 분</label>';
						echo '<input name="delivery_person" type="text" value="' . htmlentities($row['member_person'], ENT_QUOTES, 'UTF-8') . '" />';
						echo '<label>주소</label>';
						echo '<input name="delivery_zipcode" type="text" value="' . htmlentities($row['member_zipcode'], ENT_QUOTES, 'UTF-8') . '" />';
						echo '<input name="delivery_address" type="text" value="' . htmlentities($row['member_address'], ENT_QUOTES, 'UTF-8') . '" />';
						echo '<label>휴대전화</label>';
						echo '<input name="delivery_phone" type="text" value="' . htmlentities($row['member_phone'], ENT_QUOTES, 'UTF-8') . '" />';
						echo '</div>';
						echo '<div>';
						echo '<label>입금하시는 분</label>';
						echo '<input name="payment_person" type="text" value="' . htmlentities($row['member_person'], ENT_QUOTES, 'UTF-8') . '" />';
						echo '</div>';
						echo '<div>';
						echo '<input type="submit" value="주문" />';
						echo '</div>';
						echo '</form>';
					}
					else
					{
						echo '<form action="http://localhost/cart_3b1.php" method="post">';
						echo '<div>';
						echo '<label>아이디</label>';
						echo '<input name="member_id" type="text" />';
						echo '<label>비밀번호</label>';
						echo '<input name="member_password" type="password" />';
						echo '<input type="submit" value="로그인" />';
						echo '</div>';
						echo '</form>';
						echo '<a href="http://localhost/cart_3c1.php">비회원주문</a>';
					}
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