<?php require("md5base.php"); $page = new MainPage(); $hof=$page->getHallOfFame(10); $recent=$page->getMostRecent(10); require("head.php"); ?>	<div id="contentdiv">		<div id="halloffame">			<h1>Hall of fame</h1>			<table>			<tr class="table_heading"><td><b>#</b></td><td><b>Username</b></td><td><b>Hashes</b></td></tr>			<?php for($i=0;$i<count($hof);$i++) {			if($i%2==0) {$class=' class="altrow"';} else {$class="";}			echo '<tr><td'.$class.'>'.($i+1).'.</td><td'.$class.'>'.$hof[$i]->getName().'</td><td'.$class.'>'.$hof[$i]->getNHashes().'</td></tr>' . "\n";			}			?>			</table>		</div>		<div id="recentcontrib">			<h1>Recently added</h1>			<table>			<?php			$datearray = array();			for($i=0;$i<count($recent);$i++) {			if($i%2==0) {$class=' class="altrow"';$classb=' class="altrow_rec_border"';} else {$class="";$classb=' class="rec_border"';}			if(strlen($recent[$i]->getUser()) == 0) {$user = 'Anonymous';} else {$user = $recent[$i]->getUser();}			$datetmp = explode('-',$recent[$i]->getDate());$date = $datetmp[2].'.'.$datetmp[1].'.'.$datetmp[0];				if(in_array($date,$datearray)) {				echo '<tr><td'.$classb.'>'.$recent[$i]->getTime().'</td><td'.$class.'><b>'.$user.'</b> added hash for<br /> <a href="#'.$recent[$i]->getValue().'">'.$recent[$i]->getShortFilename(60).'</a></td></tr>' . "\n";								}				else {				echo '<tr class="table_heading"><td colspan="2"><b>'.$date.'</b></td></tr>' . "\n";				echo '<tr><td'.$classb.'>'.$recent[$i]->getTime().'</td><td'.$class.'><b>'.$user.'</b> added hash for<br /> <a href="#'.$recent[$i]->getValue().'">'.$recent[$i]->getShortFilename(60).'</a></td></tr>' . "\n";				array_push($datearray,$date);				}			}			?>			</table>		</div>		<div class="clearboth"></div>	</div><?php require("foot.php"); ?>