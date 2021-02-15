<?php
session_start();

include 'dbconnect.php';
include 'Function.php';

$db = new DBconnnectCLASS();

session_regenerate_id(true);

//仮設ログアウト
if(isset($_POST['logout']) ) {
	unset($_SESSION['title']);
	unset($_SESSION['text']);
	unset($_SESSION['color']);
	unset($_SESSION['userId']);
}

if (!isset($_SESSION['userId'])) {
	header('Location: ./index.php');
	exit();
}

if (isset($_POST['clear'])) {
	if ($_POST['clear'] == "クリア") {
		$_SESSION['username'] = "";
		$_SESSION['email'] = "";
		$_SESSION['title'] = "";
		$_SESSION['text'] = "";
		$_SESSION['color'] = "";

		$_SESSION['actionName'] = "input_clear";
	}

}elseif (isset($_POST['Submit'])) {
	if ($_POST['Submit'] == "確認") {

		if (isset($_POST['token'])) {
			if ($_SESSION['token'] !== $_POST['token']) {
				$_SESSION = array();
				session_destroy();

				header('Location: ./index.php');
				exit();
			}
		}

		$_SESSION['username'] = $_POST['name'];
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['title'] = $_POST['title'];
		$_SESSION['text'] = $_POST['text'];
		$_SESSION['color'] = $_POST['color'];

		$err = "";

		if (!checkEmail($_POST['email'])) {
			$err = "E-mailは未記入、または半角英数字@test.co.jpを入力してください。 <br>";
		}

		if (!checkLen($_POST['title'], 50)) {
			$err = "タイトルは50文字以内におさめてください。<br>";
		}

		if (isBlank($_POST['text'])) {
			$err = "本文を入力してください。";
		}

		if (isBlank($err)) {
			$_SESSION['actionName'] = "input_check";

			header('Location: ./confirm.php');  // 確認画面へ遷移
			exit();  // 処理終了
		}
	}
}else{
	$_SESSION['actionName'] = "input_display";
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/master.css" type="text/css">
	<title>掲示板</title>
</head>
<body>
<header>
	掲示板
	<p><?php echo '<FONT COLOR="RED">'.$err.'</FONT>'; ?></p>
</header>
<main>
	<div>
		<form action="" method="POST">
			<div>
				<table class="inputArticle">
					<tr>
						<td class="itemName" id="i_name" ><div>名前</div></td>
						<td><div>
							<input type="text" name="name"  value="<?php echo htmlspecialchars($_SESSION['username'],ENT_QUOTES,"UTF-8"); ?>" >
						</div></td>
					</tr>
					<tr>
						<td class="itemName" id="i_mail" ><div>E-mail</div></td>
						<td><div>
							<input type="text" name="email" value="<?php echo htmlspecialchars($_SESSION['email'],ENT_QUOTES,"UTF-8"); ?>" >
						</div></td>
					</tr>
					<tr>
						<td class="itemName" id="i_title"><div>タイトル</div></td>
						<td><div>
							<input type="text" name="title" value="<?php echo htmlspecialchars($_SESSION['title'],ENT_QUOTES,"UTF-8"); ?>" >
						</div></td>
					</tr>
					<tr>
						<td class="itemName" id="i_text" ><div>本文</div></td>
						<td><div>
							<textarea name="text" cols="35" rows="5" ><?php echo htmlspecialchars($_SESSION['text'],ENT_QUOTES,"UTF-8"); ?></textarea>
						</div></td>
					</tr>
					<tr>
						<td	class="itemName" id="moji" ><div>文字色</div></td>
						<td><div>
							<?php
							$stmt = $db->getconnnect()->prepare("select COLOR_ID, COLOR_CODE, COLOR_NAME from COLOR_MASTER;");
							$stmt->execute();

								while ($row = $stmt->fetch()) {

								?>
								<input class="radio" type="radio" name="color" value="<?php echo $row['COLOR_ID'];?>" id=<?php echo $row['COLOR_ID'];?>

								<?php
								if (isBlank($_SESSION['color'])) {
									$_SESSION['color'] = 3;
								};

								if ($_SESSION['color'] == $row['COLOR_ID']) {
									echo checked;
								}

								?>>

								<label for=<?php echo $row['COLOR_ID']; ?> style="color:#<?php echo $row['COLOR_CODE'];?>" ><?php echo $row['COLOR_NAME']; ?></label>
							<?php } ?>

						</div></td>
					</tr>
				</table>
			</div>
			<div>
				<input class="button" type="submit" name="clear" value="クリア">
				<input class="button" type="submit" name="Submit" value="確認">
				<input class="button" type="submit" name="logout" value="ログアウト">
				<?php
					$token = hash(sha256, session_id());
					$_SESSION['token'] = $token;
				?>
					<input type="hidden" name="token" value="<?=$token?>">
			</div>
		</form>
		<hr>
		<?php
			$stmt2 = $db->getconnnect()->prepare("select ARTICLE_ID, CREATE_DATE, NAME, EMAIL, TITLE, TEXT, COLOR_CODE from ARTICLE A inner JOIN COLOR_MASTER B on A.COLOR_ID = B.COLOR_ID order by CREATE_DATE DESC;");
			$stmt2->execute();
			while ($row2 = $stmt2->fetch()) {
		?>
		<div>
			<table class="postedArticle" style="color:#<?php echo $row2['COLOR_CODE']; ?>">
				<tr>
					<td class="articleId"><div><?php echo $row2['ARTICLE_ID']; ?></div></td>
					<td class="articleTitle"><div>
						<?php if (isBlank($row2['TITLE'])) {
							echo "(no title)";
						}else{
							echo htmlspecialchars($row2['TITLE'], ENT_QUOTES, "UTF-8");
						} ?>
					</div></td>
				</tr>
				<tr>
					<td class="articleText" colspan="2"><div>
						<?php echo nl2br(htmlspecialchars($row2['TEXT'], ENT_QUOTES, "UTF-8")); ?>
					</div></td>
				</tr>
				<tr>
					<td class="articleDate" colspan="2"><div>
						<?php echo date("Y年m月d日 H時i分", strtotime($row2['CREATE_DATE'])); ?>
						<?php
						if (isBlank($row2['NAME'])) {
							$name = "nobody";
						}else{
							$name = $row2['NAME'];
						}

						if (isBlank($row2['EMAIL'])) {
							echo htmlspecialchars($name, ENT_QUOTES, "UTF-8");
						}else{ ?>
							<a href="mailto:<?php echo $row2['EMAIL'];?>"><?php echo htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?></a>
						<?php }?>
					</div></td>
				</tr>
			</table>
			</div>
		</div>
		<?php } ?>
</main>
</body>
</html>