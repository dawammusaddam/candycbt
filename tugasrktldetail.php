<?php
defined('APLIKASI') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<div class='row'>
    <div class='col-md-12'>
        <div class='box box-solid'>
            <div class='box-header with-border'>
                <h3 class='box-title'><i class="fas fa-edit    "></i> Proyek Perubahan</h3>
            </div><!-- /.box-header -->
            <div class='box-body'>
                <?php
                    $id_proyek_perubahan = dekripsi($ac);
                    $proyek_perubahan = mysqli_fetch_array(mysqli_query($koneksi, "SELECT pp.*, ppk.nama as nama_kategori FROM proyek_perubahan pp JOIN proyek_perubahan_kategori ppk ON pp.id_kategori=ppk.id_kategori WHERE pp.id_proyek_perubahan='$id_proyek_perubahan'"));

                    $kelurahan = mysqli_fetch_array(mysqli_query($koneksi, "SELECT w.id, w.nama, w.parent_id FROM wilayahs w WHERE w.id=" . $proyek_perubahan['lokasi']));
                    $kecamatan = mysqli_fetch_array(mysqli_query($koneksi, "SELECT w.id, w.nama, w.parent_id FROM wilayahs w WHERE w.id=" . $kelurahan['parent_id']));
                    $kabupaten = mysqli_fetch_array(mysqli_query($koneksi, "SELECT w.id, w.nama, w.parent_id FROM wilayahs w WHERE w.id=" . $kecamatan['parent_id']));
                    $provinsi = mysqli_fetch_array(mysqli_query($koneksi, "SELECT w.id, w.nama, w.parent_id FROM wilayahs w WHERE w.id=" . $kabupaten['parent_id']));

                ?>
                <div class='row'>
                    <form action='' method='post'>
                        <div class="col-md-6">
                            <div class='box box-solid'>
                                <div class='box-header with-border'>
                                    <h3 class='box-title'> Tulis Proyek Perubahan</h3>
                                </div>
                                <div class="mt-2">
                                    <h5 class="mb-75">Judul :</h5>
                                    <p class="card-text"><?= $proyek_perubahan['judul'] ?></p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-2">
                                            <h5 class="mb-75">Kategori :</h5>
                                            <p class="card-text"><?= $proyek_perubahan['nama_kategori'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-2">
                                            <h5 class="mb-75">Objek Perubahan :</h5>
                                            <p class="card-text"><?= $proyek_perubahan['obyekperubahan'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-2">
                                            <h5 class="mb-75">Lokasi Povinsi :</h5>
                                            <p class="card-text"><?= $provinsi['nama'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-2">
                                            <h5 class="mb-75">Lokasi Kabupaten :</h5>
                                            <p class="card-text"><?= $kabupaten['nama'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-2">
                                            <h5 class="mb-75">Lokasi Kecamatan :</h5>
                                            <p class="card-text"><?= $kecamatan['nama'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-2">
                                            <h5 class="mb-75">Lokasi Desa :</h5>
                                            <p class="card-text"><?= $kelurahan['nama'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-2">
                                            <h5 class="mb-75">Target Waktu :</h5>
                                            <p class="card-text"><?= $proyek_perubahan['targetwaktu'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='box box-solid'>
                                <div class='box-header with-border'>
                                    <div class='box-tools pull-right'>
                                        <a href='<?= $homeurl . '/proyek-perubahan' ?>' class='btn btn-sm bg-maroon'><i class='fa fa-times'></i></a>
                                    </div>
                                </div><!-- /.box-header -->
                                <div class='box-body'>
                                    <div class="mt-2" style="min-height: 380px;">
                                        <h5 class="mb-75">Penjelasan Proyek Perubahan :</h5>
                                        <p class="card-text"><?= $proyek_perubahan['isiproyekperubahan'] ?></p>
                                    </div>
                                    <div class="mt-2">
                                        <h5 class="mb-75">Kesimpulan :</h5>
                                        <p class="card-text"><?= $proyek_perubahan['kesimpulan'] ?></p>
                                    </div>
                                </div><!-- /.box -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
</script>