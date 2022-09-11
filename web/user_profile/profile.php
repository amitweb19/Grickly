
<?php include_once("header.php"); ?>
<div id="header2">
	<div id="wrapheader2">
		<div id="topMenu">
			<div class="header2Box icons icons1" onclick="window.open('home.php', '_self');"></div>
			<div class="header2Box icons icons2" onclick="window.open('student.php', '_self');"></div>
			<div class="header2Box icons icons3" onclick="window.open('teacher.php', '_self');"></div>
			<div class="header2Box icons icons4" onclick="window.open('group.php', '_self');"></div>
			<div class="header2Box icons icons5" onclick="window.open('about.php', '_self');"></div>
		</div>
		<div id="info">
			<div id="name">
				<?php echo $name; ?>
				<div id="Btn">
					<?php echo $button; ?>
				</div>
			</div>
			<div id="scroll">
				<div id="scrollImg" class="icons icon9" onclick="scrollToTop()"></div>
			</div>
		</div>
	</div>
</div>
<div id="container">
	<div id="header3">
	<div id="header3Wrap">
		<div id="peoplesBox">
			<div id="friendArea">
				<div id="friendBox"><?php echo $friendsHTML; ?></div>
				<div id="friendOpt">
					<div id="friendTitle"><?php echo $frndBox; ?></div>
					<div id="friendAll"><?php echo $friends_view_all_link; ?></div>
				</div>
			</div>

			<div id="followArea">
				<div id="followBox"><?php echo $followHTML; ?></div>
				<div id="followOpt">
					<div id="followTitle"><?php echo $boxTitle; ?></div>
					<div id="followAll"><?php echo $follows_view_all_link; ?></div>
				</div>
			</div>
		</div>
		<div id="profileBox">
			<?php echo $profile_pic_btn; ?><?php echo $pic_form; ?><?php echo $profile_pic; ?>
		</div>
	</div>
	</div>
	<div id="mainContainer">
		<div id="mainContainerWrap">
			<div id="mainContainerLeft"></div>
			<div id="mainContainerRight">
				<?php include "status.php" ?>
			</div>
		</div>
	</div>
</div>
<div id="footer"></div>
</body>
</html>