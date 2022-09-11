<div class="postbox">
<div class="'.$opt1.'speak"></div>
<div class="post'.$opt1.'">
<img class="userpic" src="../../users/'.$author.'/'.$authorpic.'" alt="'.$author.'" onclick="window.location.href=\'profile.php?u='.$author.'\'">
<a href="profile.php?u='.$author.'">'.$name.'</a><br>'.$postdate.'
</div>
<div class="postdata"><div class="usrdata">'.$data.'</div></div>
</div>
<span class="seprater'.$opt2.'"></span>





<div id="status_'+sid+'" class="status_boxes">
<div>
<b>Posted by you just now:</b> 
<span id="sdb_'+sid+'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''+sid+'\',\'status_'+sid+'\');" title="DELETE THIS STATUS AND ITS REPLIES">delete status</a></span>
<br />'+data+'
</div>
</div>
<textarea id="replytext_'+sid+'" class="replytext" onkeyup="statusMax(this,250)" placeholder="write a comment here"></textarea>
<button id="replyBtn_'+sid+'" onclick="replyToStatus('+sid+',\'<?php echo $u; ?>\',\'replytext_'+sid+'\',this)">Reply</button>'+currentHTML;