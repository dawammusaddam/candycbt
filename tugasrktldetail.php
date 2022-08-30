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
                    $id_proyek_perubahan = 1;
                    $proyek_perubahan = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM proyek_perubahan pp JOIN proyek_perubahan_kategori ppk ON pp.id_kategori=ppk.id_kategori WHERE pp.id_proyek_perubahan='$id_proyek_perubahan'"));
                ?>
                <div class='row'>
                    <form action='' method='post'>
                        <div class="col-md-6">
                            <div class='box box-solid'>
                                <div class='box-header with-border'>
                                    <h3 class='box-title'> Tulis Proyek Perubahan</h3>
                                </div>
                                <div class="mt-2">
                                    <h5 class="mb-75">Judul:</h5>
                                    <p class="card-text"><?= $proyek_perubahan['judul'] ?></p>
                                </div>
                                <div class="mt-2">
                                    <h5 class="mb-75">Judul:</h5>
                                    <p class="card-text"><?= $proyek_perubahan['judul'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='box box-solid'>
                                <div class='box-header with-border'>
                                    <div class='box-tools pull-right'>
                                        <button type='submit' name='tambahrktl' class='btn btn-sm btn-flat btn-success'><i class='fa fa-edit'></i> Simpan</button>
                                        <a href='<?= $homeurl . '/proyek-perubahan' ?>' class='btn btn-sm bg-maroon'><i class='fa fa-times'></i></a>
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
                                            <textarea name="kesimpulan" class="form-control" placeholder="Berikan poin-poin dalam Penjelasan yang Anda buat" required></textarea>
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