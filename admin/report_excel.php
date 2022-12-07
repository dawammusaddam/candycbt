<?php
require("../config/config.default.php");
require("../config/config.function.php");
require("../config/functions.crud.php");
require("../config/dis.php");
(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
($id_pengawas == 0) ? header('location:login.php') : null;
echo "<style> .str{ mso-number-format:\@; } </style>";
$id_ujian = $_GET['m'];
$pengawas = fetch($koneksi, 'pengawas', array('id_pengawas' => $id_pengawas));
$mapel = fetch($koneksi, 'mapel', array('id_mapel' => $id_ujian));
$id_mapel = $mapel['id_mapel'];
if (date('m') >= 7 and date('m') <= 12) {
	$ajaran = date('Y') . "/" . (date('Y') + 1);
} elseif (date('m') >= 1 and date('m') <= 6) {
	$ajaran = (date('Y') - 1) . "/" . date('Y');
}

$sqljabatanregion = "";
if(!empty($_GET['wilayah_type'])){
	$wilayah_id = $_GET['wilayah_id'];
	$wilayah_type = $_GET['wilayah_type'];
	if($wilayah_type == 'prov'){
		$sqljabatanregion .= " AND a.provinsi_id=" . $wilayah_id;
	}else if($wilayah_type == 'kab'){
		$sqljabatanregion .= " AND a.kabupaten_id=" . $wilayah_id;
	}else if($wilayah_type == 'kec'){
		$sqljabatanregion .= " AND a.kecamatan_id=" . $wilayah_id;
	}else if($wilayah_type == 'kel'){
		$sqljabatanregion .= " AND a.kelurahan_id=" . $wilayah_id;
	}
	$selected_wilayah = mysqli_fetch_array(mysqli_query($koneksi, "SELECT prov.nama AS nama FROM wilayahs prov WHERE prov.id=" . $wilayah_id));
}
$file = "NILAI_" . $selected_wilayah['nama'] . "_" . $mapel['kode'];
$file = str_replace(" ", "-", $file);
$file = str_replace(":", "", $file);
header("Content-type: application/octet-stream");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Disposition: attachment; filename=" . $file . ".xls"); ?>

Wilayah: <?= $selected_wilayah['nama'] ?><br />
Kode Mapel: <?= $mapel['kode'] ?><br />
Tanggal Ujian: <?= buat_tanggal('D, d M Y - H:i', $mapel['tgl_ujian']) ?><br />
Jumlah Soal: <?= $mapel['jml_soal'] ?> PG<br />

<table border='1'>
	<tr>
		<td rowspan='2'>No.</td>
		<td rowspan='2'>No. Peserta</td>
		<td rowspan='2'>Nama</td>
		<td rowspan='2'>Kabupaten</td>
		<td rowspan='2'>Kecamatan</td>
		<td rowspan='2'>Lama Ujian</td>
		<td rowspan='2'>Benar</td>
		<td rowspan='2'>Salah</td>
		<td rowspan='2'>Nilai PG</td>
		<td rowspan='2'>Nilai / Skor</td>
		<td colspan='<?= $mapel['jml_soal'] ?>'>Jawaban</td>

	</tr>
	<tr><?php
		for ($num = 1; $num <= $mapel['jml_soal']; $num++) {
			$soal = fetch($koneksi, 'soal', array('id_mapel' => $id_mapel, 'nomor' => $num));
		?>
			<td><?= $num ?></td>
		<?php } ?>
		<?php
		for ($num = 1; $num <= $mapel['jml_esai']; $num++) {
			$soal = fetch($koneksi, 'soal', array('id_mapel' => $id_mapel, 'nomor' => $num));
		?>
			<td><?= $num ?></td>
		<?php } ?>

	</tr>

	<?php
	$siswaQ = mysqli_query($koneksi, "SELECT * FROM siswa a join nilai b ON a.id_siswa=b.id_siswa where b.id_mapel='$id_ujian'" . $sqljabatanregion . " ORDER BY id_kelas ASC");
	$betul = array();
	$salah = array();
	while ($siswa = mysqli_fetch_array($siswaQ)) {
		$no++;
		$benar = $salah = 0;
		$skor = $lama = '-';
		$selisih = 0;
		$nilai = fetch($koneksi, 'nilai', array('id_mapel' => $id_mapel, 'id_siswa' => $siswa['id_siswa']));
		if ($nilai['ujian_mulai'] <> '' and $nilai['ujian_selesai'] <> '') {
			$selisih = strtotime($nilai['ujian_selesai']) - strtotime($nilai['ujian_mulai']);
		}
	?>
		<tr>
			<td><?= $no ?></td>
			<td><?= $siswa['username'] ?></td>
			<td><?= $siswa['nama'] ?></td>
			<td><?= $siswa['kabupaten'] ?></td>
			<td><?= $siswa['kecamatan'] ?></td>
			<td><?= lamaujian($selisih) ?></td>
			<td><?= $nilai['jml_benar'] ?></td>
			<td><?= $nilai['jml_salah'] ?></td>
			<td class='str'><?= $nilai['skor'] ?></td>
			<td class='str'><?= $nilai['total'] ?></td>
			<?php

			$jawaban = unserialize($nilai['jawaban']);
			foreach ($jawaban as $key => $value) {

				$soal = mysqli_fetch_array(mysqli_query($koneksi, "select * from soal where id_soal='$key' order by nomor ASC"));
				$nomor = $soal['nomor'];
				if ($soal) {
					if ($value == $soal['jawaban']) { ?>

						<td style='background:#00FF00;'><?= $value ?></td>
					<?php } else { ?>
						<?php if ($value == 'X') { ?>
							<td style='background:#bbd1de;'><?= $value ?></td>
						<?php } else { ?>
							<td style='background:#FF0000;'><?= $value ?></td>
						<?php } ?>
					<?php }
				} else { ?>
					<td>-</td>
			<?php }
			}
			?>
		</tr>

	<?php } ?>

</table>

<!-- <br>
<table border='1'>
	<tr>
		<th>No.</th>
		<th>Soal</th>
		<th>Menjawab Benar</th>
		<th>Menjawab Salah</th>
		<th>Kategori</th>
	</tr>
	<?php

	$soalq = mysqli_query($koneksi, "SELECT * FROM soal a join mapel b ON a.id_mapel=b.id_mapel  ORDER BY nomor ASC");

	while ($soal = mysqli_fetch_array($soalq)) {
		$no++;
		$nomor = $soal['nomor'];
	?>
		<tr>
			<td><?= $soal['nomor'] ?></td>
			<td><?= $soal['soal'] ?></td>
			
		</tr>

	<?php } ?>

</table> -->