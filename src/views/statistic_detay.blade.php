<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img src="/icon/kapat.png"></span></button>
    <h4 class="modal-title">{{$duyuru->name}}</h4>
</div>
<div class="modal-body">
    <table class="table table-striped">
        <tr>
            <th>İsim</th>
            <th>Okuma</th>
        </tr>
        @foreach($duyuru->duyuru_users as $duyuru_user)
            <tr>
                <td>{{$duyuru_user->user->name}}</td>
                <td>{!! $duyuru_user->okundu==1?'<span class="text-success">Okudu</span>':'<span class="text-red">Okumadı</span>' !!}</td>
            </tr>
        @endforeach
    </table>
</div>