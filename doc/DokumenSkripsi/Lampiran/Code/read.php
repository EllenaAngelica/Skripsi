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
                <div class="callout">
					<h3><?=$pengumuman['subjek']?></h3>
					<p>
						by : <?= $pengumuman['namaPengirim']?> (<?= $pengumuman['emailPengirim']?>) on <?=date("D,d M Y H:i:s", strtotime($pengumuman['waktuTerkirim']))?>
					</p>
					<br><br>
					<?php 
						if(!strpos($pengumuman['isi'], "<div")){
					?>
							<?= nl2br(nl2br($pengumuman['isi'])); ?>
					<?php
						}
						else{
					?>
							<?= $pengumuman['isi']; ?>
					<?php
						}
					?>
					<br><br>
					<?php if($pengumuman['ketersediaanLampiran'] == 'Y'):?>
							<p>
								*) Pengumuman ini memiliki lampiran, silahkan memeriksa langsung email student Anda untuk mengunduhnya.
							</p>
					<?php
						endif;
					?>
				</div>
			</div>
		</div>
    </body>
</html>