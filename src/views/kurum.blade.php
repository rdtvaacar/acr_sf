@extends('acr_sf.index')
@section('header')
    @include('includes.data_table_css')
    {!! Acr_fl::galery_css() !!}
@stop
@section('acr_sf')
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 style="float: left;">Duyurular</h1>
                <a class="btn btn-sm btn-success" style="float: right;" href="/acr/sf/kurum/ekle">Duyuru ve Dosya Ekleyin</a>
            </div>
            <div class="box-body">
                <table class="table" id="data_table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>#ID</th>
                        <th>Okundu</th>
                        <th>Başlık</th>
                        <th>İncele</th>

                        <th>Eklenme Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($duyurular as $data)
                        <tr id="{{$data->id}}">
                            <td width="1%" style=" {{!empty($data->duyuru_user->okundu)&&$data->duyuru_user->okundu==1 ? 'background-color:#00e600
':'background-color:#ff3333'}}"></td>
                            <td width="2%" align="center">
                                {{$data->id}}
                            </td>
                            <td  align="center" width="2%" onclick="duyuru_oku({{$data->id}},0)" style="text-align: center; cursor:pointer;">
                                <input data-toggle="tooltip" data-placement="right" title="Okundu/Okunmadı olarak işaretleyin" {{!empty($data->duyuru_user->okundu)&&$data->duyuru_user->okundu==1 ? 'checked':''}} id="oku_{{$data->id}}" type="checkbox"
                                       style="height: 22px; width: 22px; cursor:pointer;"/>
                            </td>
                            <td>{{$data->name}}</td>
                            <td>
                                <div onclick="duyuru_incele({{$data->id}})" class="btn btn-info btn-xs btn-block">İncele</div>
                            </td>
                            <td>{{$data->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(count($files)>0)
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1>Dosyalar</h1>
                </div>
                <div class="box-body">
                    {!! Acr_fl::dont_img_list($acr_file_id)!!}
                </div>
            </div>
        </div>
    @endif
    @if(count($imgs)>0)
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1>Görseller</h1>
                </div>
                <div class="box-body">
                    {!! Acr_fl::views_galery($acr_file_id,null,60,1)!!}
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
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    {!! Acr_fl::galery_js() !!}
    <script>
        $('#data_table').DataTable({
            "order": [[1, "desc"]],
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
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        function duyuru_oku(id, oku) {
            $.ajax({
                type: 'post',
                url: '/acr/sf/kurum/duyuru/oku',
                data: 'id=' + id + '&oku=' + oku,
                success: function (okundu) {
                    if (okundu == 1) {
                        $('#oku_' + id).prop("checked", true)

                    } else {
                        $('#oku_' + id).prop("checked", false)
                    }
                }
            });
        }

        function duyuru_incele(id) {
            $.ajax({
                type: 'post',
                url: '/acr/sf/kurum/duyuru/incele',
                data: 'id=' + id,
                success: function (msg) {
                    $('#acr_sf_modal').modal('show')
                    $('#acr_sf_modal_body').html(msg)
                    duyuru_oku(id, 1)
                }
            });
        }
    </script>
@stop