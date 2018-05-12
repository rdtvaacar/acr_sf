@extends('acr_sf.index')
@section('header')
    @include('includes.data_table_css')
    <link rel="stylesheet" href="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    {!! Acr_fl::css() !!}
@stop
@section('acr_sf')
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 style="float: left;">Duyurular</h1>
                <a class="btn btn-sm btn-warning" style="float: right;" href="/acr/sf/kurum"><<< Duyurular ve Dosyalar</a>
            </div>
            <div class="box-body">
                <table class="table" id="data_table">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>İsim</th>
                        <th>İstatistik</th>
                        <th>Eklenme Tarihi</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($duyurular as $data)
                        <tr id="{{$data->id}}">
                            <td>{{$data->id}}</td>
                            <td>{{$data->name}}</td>
                            <td>
                                <div class="btn btn-xs btn-info" onclick="statistic({{$data->id}})">Kullanıcı İstatistik</div>
                            </td>
                            <td>{{$data->created_at}}</td>
                            <td>
                                <a class="btn btn-warning btn-sm" href="/acr/sf/kurum/ekle?duyuru_id={{$data->id}}">DÜZENLE</a>
                            </td>
                            <td>
                                <div class="btn btn-danger btn-sm" onclick="sil({{$data->id}})">SİL</div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if(in_array(5,$roles))
        <div class="col-md-5">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1>Duyuru Ekleyin</h1>
                    <a class="btn btn-sm btn-success" style="float: right;" href="/acr/sf/kurum/ekle">Yeni</a>
                </div>

                <div class="box-body">
                    <form method="post" action="/acr/sf/kurum/duyuru/kaydet">
                        {{csrf_field()}}
                        <label>Başlık</label>
                        <input name="name" class="form-control" value="{{@$duyuru->name}}"/>
                        <label>İçerik</label>
                        <textarea name="description" id="description" class="form-control">{{@$duyuru->description}}</textarea>
                        <input name="duyuru_id" type="hidden" value="{{@$duyuru->id}}"/>
                        <button class="btn btn-block btn-primary">DUYURUYU KAYDET</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1>Dosya Yükleyin</h1>
                </div>
                <div class="box-body">
                    @if($file_sf->disk>$disk_use)
                        {!! Acr_fl::form() !!}
                    @else
                        <div class="alert alert-danger">Disk alanınız doldu lütfen dosyalardan bazılarını silin yada alan satın alın.</div>
                    @endif
                    {!! Acr_fl::files_list($acr_file_id) !!}
                </div>
                <div class="box-footer">
                    <div style="float: left;">Disk Kullanımınız {{$disk_use}}MB/500MB</div>
                    <a class="btn btn-xs btn-primary" href="/" style="float: right;">Disk Alanı Satın Al</a>
                    <div style="float: right;">%{{round($disk_use/500 * 100,2)}} kullanıldı</div>
                </div>
            </div>
        </div>
    @endif
    <div class="modal fade" id="acr_sf_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="acr_sf_modal_body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kapat</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@stop
@section('footer')
    {!! Acr_fl::js($acr_fl_data) !!}
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#data_table').DataTable({
            "order": [[0, "desc"]],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "language": {
                "sProcessing": "İşleniyor...",
                "lengthMenu": "Sayfada _MENU_ satır gösteriliyor",
                "zeroRecords": "Gösterilecek sonuç yok.",
                "info": "Toplam _PAGES_ sayfadan _PAGE_. sayfa gösteriliyor",
                "infoEmpty": "Gösterilecek öğe yok",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "search": "Arama yap",
                "oPaginate": {
                    "sFirst": "İlk",
                    "sPrevious": "Önceki",
                    "sNext": "Sonraki",
                    "sLast": "Son"
                }

            }
        });
    </script>
    <script src="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script>
        $(function () {
            //Add text editor
            $("#description").wysihtml5();
        });
    </script>
    <script>
        function sil(id) {
            if (confirm('Silmek istediğinizden eminmisiniz?')) {
                $.ajax({
                    type: 'post',
                    url: '/acr/sf/kurum/duyuru/delete',
                    data: 'id=' + id,
                    success: function () {
                        $('#' + id).hide(400);
                    }
                });
            }
        }

        function statistic(id) {
            $.ajax({
                type: 'post',
                url: '/acr/sf/kurum/duyuru/statistic',
                data: 'id=' + id,
                success: function (msg) {
                    $('#acr_sf_modal').modal('show')
                    $('#acr_sf_modal_body').html(msg)
                }
            });
        }
    </script>
@stop