<?php
defined('APLIKASI') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<?php

?>
<div class='row'>
    <div class='col-md-12'>
        <div class='box box-solid'>
            <div class='box-header with-border'>
                <h3 class='box-title'><i class="fas fa-file-signature    "></i> Data Proyek Perubahan</h3>
                <div class='box-tools pull-right'>
                    <a href="<?= $homeurl . '/proyek-perubahan-buat' ?>" class='btn btn-sm btn-primary'><i class='glyphicon glyphicon-plus'></i> <span class='hidden-xs'>Buat Baru</span></a>
                </div>
            </div><!-- /.box-header -->
            <div class='box-body'>

            <table id='example1' class='table table-bordered table-striped'>
                <thead>
                    <tr>
                        <th width='5px'>#</th>
                        <th>Judul</th>
                        <th class='hidden-xs'>Kategori</th>
                        <th class='hidden-xs'>Objek Perubahan</th>
                        <th class='hidden-xs'>Target Waktu</th>
                        <th width='250px'></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$_SESSION[id_siswa]'")); 
                        $id_siswa = $siswa['id_siswa'];
                        $proyek_perubahan = mysqli_query($koneksi, "SELECT pp.*, ppk.nama as nama_kategori FROM proyek_perubahan pp JOIN proyek_perubahan_kategori ppk ON pp.id_kategori=ppk.id_kategori WHERE pp.id_siswa='$id_siswa' "); 
                    ?>
                    <?php while ($pry = mysqli_fetch_array($proyek_perubahan)) : ?>
                        <?php
                        $no++;
                        ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $pry['judul'] ?></td>
                            <td class='hidden-xs'><?= $pry['nama_kategori'] ?></td>
                            <td class='hidden-xs'><?= $pry['obyekperubahan'] ?></td>
                            <td class='hidden-xs'><?= $pry['targetwaktu'] ?></td>
                            <!-- <td class='hidden-xs'><label class='label label-primary'>Selesai</label></td> -->
                            <td class="text-center">
                                <button class='btn btn-sm btn-danger'><i class='fa fa-desktop'></i> Monitoring</button>&nbsp;|&nbsp;
                                <a href="<?= $homeurl . '/proyek-perubahan-detail/' . enkripsi($pry['id_proyek_perubahan']) ?>"><button class='btn btn-sm btn-success'><i class='fa fa-search'></i> Lihat Detail</button></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>


<script>
    
</script>