<?php
defined('APLIKASI') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<?php if ($ac == '') : ?>
    <?php

    if (empty($_GET['kelas'])) {
        $id_kelas = "";
        $sqlkelas = "";
    } else {
        $id_kelas = $_GET['kelas'];
        $sqlkelas = " and a.id_kelas ='" . $_GET['kelas'] . "'";
    }
    // $_GET['id'] = 1;
    if (empty($_GET['id'])) {
        $id_mapel = "";
        $qmapel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mapel"));
        // echo json_encode($qmapel);
        $_GET['id'] = $qmapel['id_mapel'];
        $id_mapel = $_GET['id'];

    } else {
        $id_mapel = $_GET['id'];
    }
    $mapel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mapel where id_mapel='$id_mapel' "));
    // $kelas = unserialize($mapel['kelas']);
    // $id_mapel = $mapel['id_mapel'];
    // $kelas = implode("','", $kelas);
    // $sqlkelas = "";
    // if (!$kelas == 'semua') {
    //     $sqlkelas = " and a.id_kelas in ('" . $kelas . "')";
    // }

    // $level = $mapel['level'];
    // $sqllevel = "";
    // if (!$level == 'semua') {
    //     $sqlkelas = "and a.level='" . $level . "'";
    // }

    $sqljabatanregion = '';
    if(!empty($_GET['wilayah_type'])){
        $wilayah_id = $_GET['wilayah_id'];
        $wilayah_type = $_GET['wilayah_type'];
        if($wilayah_type == 'prov'){
            $sqljabatanregion .= ' AND a.provinsi_id=' . $wilayah_id;
            $selected_wilayah = mysqli_fetch_array(mysqli_query($koneksi, "SELECT prov.id AS prov_id FROM wilayahs prov WHERE prov.id=" . $wilayah_id));
        }else if($wilayah_type == 'kab'){
            $sqljabatanregion .= ' AND a.kabupaten_id=' . $wilayah_id;
            $selected_wilayah = mysqli_fetch_array(mysqli_query($koneksi, "SELECT prov.id AS prov_id, kab.id AS kab_id FROM wilayahs prov JOIN wilayahs kab ON prov.id = kab.parent_id WHERE kab.id=" . $wilayah_id));
        }else if($wilayah_type == 'kec'){
            $sqljabatanregion .= ' AND a.kecamatan_id=' . $wilayah_id;
            $selected_wilayah = mysqli_fetch_array(mysqli_query($koneksi, "SELECT prov.id AS prov_id, kab.id AS kab_id, kec.id AS kec_id FROM wilayahs prov JOIN wilayahs kab ON prov.id = kab.parent_id JOIN wilayahs kec ON kab.id = kec.parent_id WHERE kec.id=" . $wilayah_id));
        }else if($wilayah_type == 'kel'){
            $sqljabatanregion .= ' AND a.kelurahan_id=' . $wilayah_id;
            $selected_wilayah = mysqli_fetch_array(mysqli_query($koneksi, "SELECT prov.id AS prov_id, kab.id AS kab_id, kec.id AS kec_id, kel.id AS kel_id FROM wilayahs prov JOIN wilayahs kab ON prov.id = kab.parent_id JOIN wilayahs kec ON kab.id = kec.parent_id JOIN wilayahs kel ON kec.id = kel.parent_id WHERE kel.id=" . $wilayah_id));
        }
    }
    ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class='box box-solid'>
                <div class='box-header with-border'>
                    <h3 class='box-title'> NILAI <?= $mapel['kode'] ?></h3>
                    <div class='box-tools pull-right btn-grou'>
                        <!-- <button class='btn btn-sm btn-primary' onclick="frames['frameresult'].print()"><i class='fa fa-print'></i> Print</button> -->
                        <iframe name='frameresult' src='report.php?m=<?= $id_mapel ?>&k=<?= $id_kelas ?>' style='display:none'></iframe>
                        <a class='btn btn-sm btn-success' href='report_excel.php?m=<?= $id_mapel ?>&wilayah_type=<?= $wilayah_type ?>&wilayah_id=<?= $wilayah_id ?>'><i class='fa fa-download'></i> Download Excel</a>
                        <a class='btn btn-sm btn-danger' href='?pg=jadwal'><i class='fa fa-times'></i></a>
                    </div>
                </div><!-- /.box-header -->
                <div class='box-body'>
                    <div class="row" style="padding-bottom: 10px;">
                        <!-- mryes -->
                        <div class="col-md-3">

                            <select class="form-control select2 ujian">
                                <?php $kelas = mysqli_query($koneksi, "SELECT * FROM mapel"); ?>
                                <option value=''> Pilih Daftar Ujian</option>
                                <?php while ($kls = mysqli_fetch_array($kelas)) : ?>
                                    <option <?php if ($id_mapel == $kls['id_mapel']) {
                                                echo "selected";
                                            } else {
                                            } ?> value="<?= $kls['id_mapel'] ?>"><?= $kls['kode'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- <div class="col-md-3">
                            <select id="select-jabatan" class="form-control select2 ">
                                <option value='' <?php //echo ($jabatan == '' ? 'selected' : '')?>> Pilih Jabatan</option>
                                <option value='PLD' <?php //echo ($jabatan == 'PLD' ? 'selected' : '')?>> Pendamping Lokal Desa</option>
                                <option value='PD' <?php //echo ($jabatan == 'PD' ? 'selected' : '')?>> Pendamping Desa</option>
                                <option value='TAKAB' <?php //echo ($jabatan == 'TAKAB' ? 'selected' : '')?>> TA Kabupaten</option>
                                <option value='TAPROV' <?php //echo ($jabatan == 'TAPROV' ? 'selected' : '')?>> TA Provinsi</option>
                            </select>
                        </div> -->
                        <!-- mryes -->
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <input type="hidden" id="input-prov" value="<?= $selected_wilayah['prov_id']?>">
                            <select id="select-prov" class="form-control select2">
                                <option value=''> Pilih Provinsi</option>
                            </select>
                        </div>
                        <!-- <div class="col-md-2">
                            <input type="hidden" id="input-kab" value="<?//= $selected_wilayah['kab_id']?>">
                            <select id="select-kab" class="form-control select2">
                                <option value=''> Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" id="input-kec" value="<?//= $selected_wilayah['kec_id']?>">
                            <select id="select-kec" class="form-control select2">
                                <option value=''> Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" id="input-kel" value="<?//= $selected_wilayah['kel_id']?>">
                            <select id="select-kel" class="form-control select2 ">
                                <option value=''> Pilih Kelurahan</option>
                            </select>
                        </div> -->
                        <div class="col-md-3">

                            <button id="cari_nilai" class="btn btn-primary">Cari Nilai</button>
                            <script type="text/javascript">
                                $('#cari_nilai').click(function() {
                                    var select_jabatan = $('#select-jabatan').val();
                                    var wilayah_id = '';
                                    var wilayah_type = '';
                                    if($('#select-prov').val() != ''){
                                        wilayah_id = $('#select-prov').val();
                                        wilayah_type = 'prov';
                                    }else if($('#select-kab').val() != ''){
                                        wilayah_id = $('#select-kab').val();
                                        wilayah_type = 'kab';
                                    }else if($('#select-kec').val() != ''){
                                        wilayah_id = $('#select-kec').val();
                                        wilayah_type = 'kec';
                                    }else if($('#select-kel').val() != ''){
                                        wilayah_id = $('#select-kel').val();
                                        wilayah_type = 'kel';
                                    }
                                    var ujian = $('.ujian').val();
                                    location.replace("?pg=nilaiujian&id=" + ujian + "&wilayah_type=" + wilayah_type + "&wilayah_id=" + wilayah_id);
                                }); //ke url
                            </script>

                        </div>
                    </div>
                    <hr>
                    <div id="tablenilai" class='table-responsive'>
                        <table id="tablenilaix" class='table table-bordered table-striped'>
                            <thead>
                                <tr>
                                    <th width='5px'>#</th>
                                    <th>No Peserta</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Kelas</th>
                                    <th>Lama Ujian</th>
                                    <th>Analisis</th>
                                    <th>Nilai PG</th>
                                    <!-- <th>Essai</th> -->
                                    <th>Total</th>
                                    <th>Jawaban</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $siswaQ = mysqli_query($koneksi, "SELECT * FROM siswa a join nilai b on a.id_siswa=b.id_siswa where b.id_mapel='$id_mapel'" . $sqlkelas . $sqljabatanregion); ?>
                                <?php while ($siswa = mysqli_fetch_array($siswaQ)) : ?>
                                    <?php
                                    $no++;
                                    $ket = '';
                                    $esai =  $jawaban = $skor = $total = '--';
                                    $selisih = 0;
                                    //$kelas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas='$id_kelas'"));
                                    $nilaiQ = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_mapel='$id_mapel' AND id_siswa='$siswa[id_siswa]'");
                                    $nilaiC = mysqli_num_rows($nilaiQ);
                                    $nilai = mysqli_fetch_array($nilaiQ);
                                    if ($nilaiC <> 0) :
                                        $selisih = '';
                                        if ($nilai['ujian_mulai'] <> '' and $nilai['ujian_selesai'] <> '') :
                                            $selisih = strtotime($nilai['ujian_selesai']) - strtotime($nilai['ujian_mulai']);

                                            $esai = "$nilai[nilai_esai]";
                                            $jawaban = "<small class='label bg-green'>$nilai[jml_benar] <i class='fa fa-check'></i></small>  <small class='label bg-red'>$nilai[jml_salah] <i class='fa fa-times'></i></small>";
                                            $skor = number_format($nilai['skor'], 2, '.', '');
                                            $total = "<small class='label bg-blue'>" . number_format($nilai['total'], 2, '.', '') . "</small>";
                                            $ket = "";
                                        elseif ($nilai['ujian_mulai'] <> '' and $nilai['ujian_selesai'] == '') :
                                            $selisih = strtotime($nilai['ujian_berlangsung']) - strtotime($nilai['ujian_mulai']);

                                            $ket = "<i class='fa fa-spin fa-spinner' title='Sedang ujian'></i>";
                                            $skor = $total = '--';
                                        endif;
                                    endif;
                                    ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $siswa['username'] ?></td>
                                        <td><?= $siswa['nama'] ?></td>
                                        <td><?= $siswa['jabatan'] ?></td>
                                        <td><?= $siswa['id_kelas'] ?></td>
                                        <td><?= $ket . " " . lamaujian($selisih) ?></td>
                                        <td><?= $jawaban ?></td>
                                        <td><?= $skor ?></td>
                                        <!-- <td><?//= $esai ?></td> -->
                                        <td><?= $total ?></td>
                                        <td>
                                            <?php if ($nilai['skor'] <> "") : ?>
                                                <?php

                                                if ($nilai['jawaban'] <> "") :
                                                    $ket = '';
                                                    $link = "?pg=" . $pg . "&ac=esai&id=" . $_GET['id'] . "&ids=" . $siswa['id_siswa'];
                                                    $link2 = "?pg=" . $pg . "&ac=jawaban&id=" . $_GET['id'] .  "&ids=" . $siswa['id_siswa'];
                                                else :
                                                    $ket = 'style="display:none"';
                                                    $link = '#';
                                                    $link2 = '#';
                                                endif;
                                                ?>
                                                <!-- <a href='<?//= $link ?>' class='btn btn-xs btn-success' <?//= $ket ?>><i class='fa fa-pencil-square-o'></i>input esai</a> -->
                                                <a href='<?= $link2 ?>' class='btn btn-sm btn-success' <?= $ket ?>><i class='fa fa-eye'></i> lihat</a>
                                                <button class='ulangnilai btn btn-sm btn-danger' data-id="<?= $nilai['id_nilai'] ?>" <?= $ket ?>><i class='fa fa-recycle'></i> Ulang</button>
                                                <!-- Button trigger modal -->
                                                <?php if ($nilai['jawaban_esai'] <> null) { ?>
                                                    <button style="display: none;" type="button" class="btn btn-sm btn-primary " data-toggle="modal" data-target="#modelId<?= $nilai['id_nilai'] ?>">
                                                        <i class="fas fa-edit    "></i> Esai
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modelId<?= $nilai['id_nilai'] ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">JAWABAN ESAI</h5>

                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id='formnesai<?= $nilai['id_nilai'] ?>'>
                                                                        <table class='table table-bordered table-striped'>

                                                                            <tbody>
                                                                                <?php $noX = 0;
                                                                                $jawabanesai = unserialize($nilai['jawaban_esai']);
                                                                                $nesai = unserialize($nilai['nilai_esai2']); ?>
                                                                                <?php foreach ($jawabanesai as $key2 => $value2) : ?>
                                                                                    <?php
                                                                                    $noX++;
                                                                                    $soal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal='$key2'"));

                                                                                    ?>
                                                                                    <tr>
                                                                                        <td><?= $noX ?></td>
                                                                                        <td><?= $soal['soal'] ?>
                                                                                            <p><b>JAWAB :</b> <?= $value2 ?></p>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="hidden" class="form-control" name="id" value="<?= $nilai['id_nilai'] ?>">
                                                                                            <input style="width: 50px" type="text" class="form-control" name="nesai<?= $nilai['id_nilai'] ?>[<?= $key2 ?>]" value="<?= $nesai[$key2] ?>">
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>


                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" id="simpanesai<?= $nilai['id_nilai'] ?>" class="btn btn-primary">Save</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        $("#formnesai<?= $nilai['id_nilai'] ?>").submit(function(e) {
                                                            e.preventDefault();
                                                            var id = '<?= $nilai['id_nilai'] ?>';
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "simpanesai.php",
                                                                data: $(this).serialize(),
                                                                success: function(result) {
                                                                    toastr.success(result);
                                                                    setTimeout(function() {
																		location.reload();
                                                                    }, 2000);
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                <?php } ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

<?php elseif ($ac == 'jawaban') : ?>
    <?php
    $idmapel = $_GET['id'];

    $id_siswa = $_GET['ids'];
    $nilai = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='$id_siswa' and id_mapel='$idmapel'"));
    $mapel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mapel where id_mapel='$idmapel'"));
    $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'"));
    ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class='box box-solid'>
                <div class='box-header with-border '>
                    <h3 class='box-title'>Data Hasil Ujian</h3>
                    <div class='box-tools pull-right btn-group'>
                        <!-- <button class='btn btn-sm btn-primary' onclick="frames['framejawab'].print()"><i class='fa fa-print'></i> Print</button> -->
                        <!-- <i class='btn btn-sm btn-danger' href='?pg=nilai&ac=lihat&id=<?= $idmapel ?>'><i class='fa fa-times'></i></a> -->
                        <!-- <iframe name='framejawab' src='printjawab.php?m=<?= $idmapel ?>&s=<?= $id_siswa ?>' style='display:none;'></iframe> -->
                    </div>
                </div><!-- /.box-header -->
                <div class='box-body'>
                    <table class='table table-bordered table-striped'>
                        <tr>
                            <th width='150'>No Induk</th>
                            <td width='10'>:</td>
                            <td><?= $siswa['nis'] ?></td>
                            <td style="text-align:center; width:150">Nilai PG</td>
                            <td style="text-align:center; width:150">Nilai Esai</td>
                            <td style="text-align:center; width:150">Total Nilai</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td width='10'>:</td>
                            <td><?= $siswa['nama'] ?></td>
                            <td rowspan='3' style='font-size:30px; text-align:center; width:150'><?= $nilai['skor'] ?></td>
                            <td rowspan='3' style='font-size:30px; text-align:center; width:150'><?= $nilai['nilai_esai'] ?></td>
                            <td rowspan='3' style='font-size:30px; color:blue; text-align:center; width:150'><?= $nilai['total'] ?></td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td width='10'>:</td>
                            <td><?= $siswa['id_kelas'] ?></td>
                        </tr>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <td width='10'>:</td>
                            <td><?= $mapel['kode'] ?></td>
                        </tr>
                    </table>
                    <br>
                    <div class='table-responsive'>
                        <table class='table table-bordered table-striped'>
                            <thead>
                                <tr>
                                    <th width='5px'>#</th>
                                    <th>Soal PG</th>
                                    <th style='text-align:center'>Jawab</th>
                                    <th style='text-align:center'>Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $jawaban = unserialize($nilai['jawaban']); ?>
                                <?php foreach ($jawaban as $key => $value) : ?>
                                    <?php
                                    $no++;
                                    $soal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal='$key'"));
                                    if ($value == $soal['jawaban']) :
                                        $status = "<span class='text-green'><i class='fa fa-check'></i></span>";
                                    else :
                                        $status = "<span class='text-red'><i class='fa fa-times'></i></span>";
                                    endif;
                                    ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $soal['soal'] ?></td>
                                        <td style='text-align:center'><?= $value ?></td>
                                        <td style='text-align:center'><?= $status ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if ($nilai['jawaban_esai'] <> null) { ?>
                            <table class='table table-bordered table-striped'>
                                <thead>
                                    <tr>
                                        <th width='5px'>#</th>
                                        <th>Soal ESAI</th>
                                        <th style='text-align:center'>Jawab</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $noX = 0;
                                    $jawabanesai = unserialize($nilai['jawaban_esai']); ?>

                                    <?php foreach ($jawabanesai as $key2 => $value2) : ?>
                                        <?php
                                        $noX++;
                                        $soal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal='$key2'"));

                                        ?>
                                        <tr>
                                            <td><?= $noX ?></td>
                                            <td><?= $soal['soal'] ?></td>
                                            <td><?= $value2 ?></td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php } ?>
                        <br>
                        <!-- <table class='table table-bordered table-striped'>
							<thead>
								<tr>
									<th width='5px'>#</th>
									<th>Soal Esai</th>
									<th style='text-align:center'>Hasil</th>
								</tr>
							</thead>
							<tbody>
								<?php $nilaiex = mysqli_query($koneksi, "SELECT * FROM hasil_jawaban WHERE id_siswa='$id_siswa' and id_mapel='$idmapel' and jenis='2' and id_ujian='$nilai[id_ujian]' ");
                                $nox = 0; ?>
								<?php while ($jawabane = mysqli_fetch_array($nilaiex)) : ?>
									<?php
                                    $soal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal='$jawabane[id_soal]'"));
                                    $nox++;
                                    ?>
									<tr>
										<td><?= $nox ?></td>
										<td><?= $soal['soal'] ?>
											<p><b>jawab : </b><?= $jawabane['esai'] ?></p>
										</td>
										<td style='text-align:center'><?= $jawabane['nilai_esai'] ?></td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
    $('#tablenilaix').dataTable();
    $(document).on('click', '.ulangnilai', function() {
        var id = $(this).data('id');
        console.log(id);
        swal({
            title: 'Apa anda yakin?',
            text: " Akan Mengulang Ujian Ini ??",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'ulangujian.php',
                    method: "POST",
                    data: 'id=' + id,
                    success: function(data) {
                        toastr.success("berhasil diulang");
                        $('#tablenilai').load(location.href + ' #tablenilai');
                    }
                });
            }
        })
    });

    $(document).ready(function(){

        var prov_id = $('#input-prov').val();
        var kab_id = $('#input-kab').val();
        var kec_id = $('#input-kec').val();
        var kel_id = $('#input-kel').val();

        function optionBuilder(label, data){
            var html = '<option value=""> ' + label + ' </option>';
            $.each(data, function( index, value ) {
                html += '<option value="' + value.id + '"> ' + value.nama + ' </option>';
            });
            return html;
        }
        //
        $.ajax({
            url: 'wilayah.php?level=provinsi',
            method: 'POST',
            contentType: "application/json",
            dataType: 'json',
            success: function(res){
                var html = optionBuilder('Pilih Provinsi', res);
                $('#select-prov').html(html);
                $("#select-prov").val(prov_id).change();

                html = '<option value=""> Pilih Kabupaten </option>';$('#select-kab').html(html);
                html = '<option value=""> Pilih Kecamatan </option>';$('#select-kec').html(html);
                html = '<option value=""> Pilih Kelurahan </option>';$('#select-kel').html(html);
            }
        });
        
        $('#select-prov').on('change', function(e){
            var val = e.target.value;
            $.ajax({
                url: 'wilayah.php?level=kabupaten&parent_id=' + val,
                method: 'POST',
                contentType: "application/json",
                dataType: 'json',
                success: function(res){
                    var html = optionBuilder('Pilih Kabupaten', res);
                    $('#select-kab').html(html);
                    $("#select-kab").val(kab_id).change();

                    html = '<option value=""> Pilih Kecamatan </option>';$('#select-kec').html(html);
                    html = '<option value=""> Pilih Kelurahan </option>';$('#select-kel').html(html);
                }
            });
        });
        
        $('#select-kab').on('change', function(e){
            var val = e.target.value;
            $.ajax({
                url: 'wilayah.php?level=kecamatan&parent_id=' + val,
                method: 'POST',
                contentType: "application/json",
                dataType: 'json',
                success: function(res){
                    var html = optionBuilder('Pilih Kecamatan', res);
                    $('#select-kec').html(html);
                    $("#select-kec").val(kec_id).change();

                    html = '<option value=""> Pilih Kelurahan </option>';$('#select-kel').html(html);
                }
            });
        });

        $('#select-kec').on('change', function(e){
            var val = e.target.value;
            $.ajax({
                url: 'wilayah.php?level=kelurahan&parent_id=' + val,
                method: 'POST',
                contentType: "application/json",
                dataType: 'json',
                success: function(res){
                    var html = optionBuilder('Pilih Kelurahan', res);
                    $('#select-kel').html(html);
                    $("#select-kel").val(kel_id).change();
                }
            });
        });


    });
</script>