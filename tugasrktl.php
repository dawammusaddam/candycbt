<?php
defined('APLIKASI') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<?php
    if(isset($_POST['tambahrktl'])){
        $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$_SESSION[id_siswa]'"));

        $id_siswa = $siswa['id_siswa'];
        $judul = $_POST['judul'];
        $id_kategori = $_POST['id_kategori'];
        $obyekperubahan = $_POST['obyekperubahan'];
        $lokasi = $_POST['lokasi'];
        $targetwaktu = $_POST['targetwaktu'];
        $isiproyekperubahan = $_POST['isiproyekperubahan'];
        $kesimpulan = $_POST['kesimpulan'];
        
        $exec = mysqli_query($koneksi, "INSERT INTO proyek_perubahan (id_siswa, judul, id_kategori, obyekperubahan, lokasi, targetwaktu, isiproyekperubahan, kesimpulan) VALUES ('$id_siswa', '$judul', '$id_kategori', '$obyekperubahan', '$lokasi', '$targetwaktu', '$isiproyekperubahan', '$kesimpulan')");

        if(!$exec){
            echo mysqli_error($koneksi);
       }
        (!$exec) ? $info = info("Gagal menyimpan!", "NO") : jump("");
    }
?>
<div class='row'>
    <div class='col-md-12'>
        <div class='box box-solid'>
            <div class='box-header with-border'>
                <h3 class='box-title'><i class="fas fa-edit    "></i> Proyek Perubahan</h3>
            </div><!-- /.box-header -->
            <div class='box-body'>

                <?php
                $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$_SESSION[id_siswa]'"));
                $mapelQ = mysqli_query($koneksi, "SELECT * FROM tugas");

                ?>
                <div class='row'>
                    <form action='' method='post'>
                        <div class="col-md-6">
                            <div class='box box-solid'>
                                <div class='box-header with-border'>
                                    <h3 class='box-title'> Tulis Proyek Perubahan</h3>
                                </div>
                                <div class='col-sm-12'>
                                    <div class='form-group'>
                                        <label>Judul </label>
                                        <input type='text' class='form-control' name='judul' placeholder='Judul' required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select class="form-control" name="id_kategori">
                                            <?php $pry_kat = mysqli_query($koneksi, "SELECT * FROM proyek_perubahan_kategori"); ?>
                                            <option value="">--</option>
                                            <?php while ($pry = mysqli_fetch_array($pry_kat)) : ?>
                                                <option value="<?= $pry['id_kategori'] ?>"><?= $pry['nama'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Obyek Perubahan</label>
                                        <input type="text" class="form-control" name="obyekperubahan">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Lokasi Provinsi</label>
                                        <select id="select-prov" class="form-control select2">
                                            <option value=''> Pilih Provinsi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Lokasi Kabupaten</label>
                                        <select id="select-kab" class="form-control select2">
                                            <option value=''> Pilih Kabupaten</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Lokasi Kecamatan</label>
                                        <select id="select-kec" class="form-control select2">
                                            <option value=''> Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Lokasi Kelurahan</label>
                                        <select name="lokasi" id="select-kel" class="form-control select2">
                                            <option value=''> Pilih Kelurahan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Target Waktu</label>
                                        <input name="targetwaktu" type="date" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='box box-solid'>
                                <div class='box-header with-border'>
                                    <div class='box-tools pull-right'>
                                        <button type='submit' name='tambahrktl' class='btn btn-sm btn-flat btn-success'><i class='fa fa-edit'></i> Simpan</button>
                                    </div>
                                </div><!-- /.box-header -->
                                <div class='box-body'>
                                    <div class='col-sm-12'>
                                        <div class='form-group'>
                                            <label>Penjelasan Proyek Perubahan </label>
                                            <textarea id='txtisiproyekperubahan' name='isiproyekperubahan' class='form-control'></textarea>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Kesimpulan</label>
                                            <textarea name="kesimpulan" class="form-control"></textarea>
                                        </div>
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
    tinymce.init({
        selector: '#txtisiproyekperubahan',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools uploadimage paste'
        ],

        toolbar: 'bold italic fontselect fontsizeselect | alignleft aligncenter alignright bullist numlist  backcolor forecolor | emoticons code | imagetools link image paste ',
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        paste_data_images: true,
        paste_as_text: true,
        images_upload_handler: function(blobInfo, success, failure) {
            success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
        },
        image_class_list: [{
            title: 'Responsive',
            value: 'img-responsive'
        }],
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
        $.ajax({
            url: 'admin/wilayah.php?level=provinsi',
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
                url: 'admin/wilayah.php?level=kabupaten&parent_id=' + val,
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
                url: 'admin/wilayah.php?level=kecamatan&parent_id=' + val,
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
                url: 'admin/wilayah.php?level=kelurahan&parent_id=' + val,
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