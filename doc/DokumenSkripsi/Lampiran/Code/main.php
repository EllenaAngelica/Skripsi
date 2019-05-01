<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>
        <?php $this->load->view('templates/script_foundation'); ?>
		<div class="row">
            <div class="medium-12 column">
				<?php
					if($jumlahPengumuman == 0){
						echo "Tidak ada pengumuman";
					}
					else{
						foreach($pengumumans as $pengumuman):
				?>
							<div class="callout">
								<h3><a href=<?="/pengumuman/read/" . $pengumuman['id']?> ><?=$pengumuman['subjek']?></a></h3>
								<p>
									by : <?= $pengumuman['namaPengirim']?> (<?= $pengumuman['emailPengirim']?>) on <?=date("D,d M Y H:i:s", strtotime($pengumuman['waktuTerkirim']))?>
								</p>
							</div>
				<?php
						endforeach;
						if($currentPage>1){
							$previousPage = $currentPage-1;
							echo "<a href='/pengumuman/page-". $previousPage . "'>Previous</a>";
						}
						if($jumlahPengumuman > $currentPage*$pengumumanPerPage){
							$nextPage = $currentPage+1;
							echo "<a href='/pengumuman/page-". $nextPage . "'>Next</a>";
						}
					}
				?>
			</div>
		</div>
    </body>
</html>