<?
	session_start();
	include "../lib/dbconn.php";

	$sql = "select * from $table where num=$num";
	$result = mysql_query($sql, $connect);

    $row = mysql_fetch_array($result);
      // 하나의 레코드 가져오기

	$item_num     = $row[num];
	$item_id      = $row[id];
	$item_name    = $row[name];
  	$item_nick    = $row[nick];
	$item_hit     = $row[hit];

	$image_name[0]   = $row[file_name_0];
	$image_name[1]   = $row[file_name_1];
	$image_name[2]   = $row[file_name_2];


	$image_copied[0] = $row[file_copied_0];
	$image_copied[1] = $row[file_copied_1];
	$image_copied[2] = $row[file_copied_2];

    $item_date    = $row[regist_day];
	$item_subject = str_replace(" ", "&nbsp;", $row[subject]);

	$item_content = $row[content];
	$is_html      = $row[is_html];

	if ($is_html!="y")
	{
		$item_content = str_replace(" ", "&nbsp;", $item_content);
		$item_content = str_replace("\n", "<br>", $item_content);
	}

	for ($i=0; $i<3; $i++)
	{
		if ($image_copied[$i])
		{
			$imageinfo = GetImageSize("./data/".$image_copied[$i]);

			$image_width[$i] = $imageinfo[0];
			$image_height[$i] = $imageinfo[1];
			$image_type[$i]  = $imageinfo[2];

			if ($image_width[$i] > 785)
				$image_width[$i] = 785;
		}
		else
		{
			$image_width[$i] = "";
			$image_height[$i] = "";
			$image_type[$i]  = "";
		}
	}

	$new_hit = $item_hit + 1;

	$sql = "update $table set hit=$new_hit where num=$num";   // 글 조회수 증가시킴
	mysql_query($sql, $connect);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../assets/css/main.css" />
<link href="../css/free.css" rel="stylesheet" type="text/css" media="all">
<script>
    function del(href)
    {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
                document.location.href = href;
        }
    }
</script>
</head>

<body>
<div class="page-wrap">

	<!-- nav -->
	<nav id="nav">
	<style media="screen">
	p {
		font-size: medium;
	}
	</style>
	<?
		if(!$userid)
	{
	?>

			<ul style="width=54px; ">
				<li><a href="../index3.php"><!--홈--><p>HOME</p></a></li>
				<li><a href="../login/login_form.php"><!--로그인--><p>Login</p></a></li>
				<li><a href="../member/member_form.php"><!--회원가입--><p>Join</p></a></li>
				<li><a href="../memo/memo.php"><p>1-Line</p></a></li>
				<li><a href="../good/list.php"><p>SALE</p></a></li>
				<li><a href="../free/list.php"><p>Board</p></a></li>
				<li><a href="../download/list.php"><p>FILE</p></a></li>
				<li><a href="../anony/list.php" class="active"><p>Anony</p></a></li>
				<li><a href="../qna/list.php"><p>QNA</p></a></li>
	<?
	}
	else
	{

		?>
			<ul style="width=54px; ">
				<li><a href="../index3.php"><p>HOME</p></a></li>
				<li><a href="../login/logout.php"><p>LogOut</p></span></a></li>
				<li><a href="../login/member_form_modify.php"> <p>My</p></a></li>
				<li><a href="../memo/memo.php"> <p>1-Line</p> </span></a></li>
				<li><a href="../good/list.php"><p>SALE</p></a></li>
				<li><a href="../free/list.php"><p>Board</p></span></a></li>
				<li><a href="../download/list.php"><p>FILE</p></a></li>
				<li><a href="../anony/list.php" class="active"><p>Anony</p></a></li>
				<li><a href="../qna/list.php"><p>QNA</p></span></a></li>
		<?
	}
	?>
	</ul>

	</nav>

	<section id="main">
		<!-- Header -->
			<header id="header">
				<div>Snapshot <span>by TEMPLATED</span></div>
			</header>
				<section style="background: #f2f2f2">
				<div class="inner">
					<div id="content">
								<div id="col2" style="float: left; width: 100%;">

						<div id="title">
								<h2>익명게시판</h2>
						</div>

						<div id="view_comment"> &nbsp;</div>

						<div id="view_title">
							<div id="view_title1"><h4>제목 : <?= $item_subject ?></h4></div><div id="view_title2">익명 | 조회 : <?= $item_hit ?>
																		| <?= $item_date ?> </div>
						</div>

						<div id="view_content">
				<?
					for ($i=0; $i<3; $i++)
					{
						if ($image_copied[$i])
						{
							$img_name = $image_copied[$i];
							$img_name = "./data/".$img_name;
							$img_width = $image_width[$i];

							echo "<img src='$img_name' width='$img_width'>"."<br><br>";
						}
					}
				?>
							<?= $item_content ?>
						</div>

						<div id="view_button">
								<a href="list.php?table=<?=$table?>&page=<?=$page?>"><img src="../img/list.png"></a>&nbsp;
				<?
					if($userid==$item_id || $userid="admin" || $userlevel==1 )
					{
				?>
								<a href="write_form.php?table=<?=$table?>&mode=modify&num=<?=$num?>&page=<?=$page?>"><img src="../img/modify.png"></a>&nbsp;
								<a href="javascript:del('delete.php?table=<?=$table?>&num=<?=$num?>')"><img src="../img/delete.png"></a>&nbsp;
				<?
					}
				?>
				<?
					if($userid)
					{
				?>
								<a href="write_form.php?table=<?=$table?>"><img src="../img/write.png"></a>
				<?
					}
				?>
						</div>

						<div class="clear"></div>

					</div> <!-- end of col2 -->
					</div> <!-- end of content -->
				</div>
				</section>

			<footer id="footer">
				<div class="copyright">
					&copy; Untitled Design: <a href="https://templated.co/">TEMPLATED</a>. Images: <a href="https://unsplash.com/">Unsplash</a>.
				</div>
			</footer>
	</section>

</div> <!-- end of wrap -->

</body>
</html>
