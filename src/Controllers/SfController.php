<?php

namespace Acr\sf\Controllers;

use Acr\sf\Models\Acr_files_childs;
use Acr\sf\Models\Duyuru;
use Acr\sf\Models\Duyuru_user;
use Acr\sf\Models\Files;
use DB, Input, Validator, Auth, Redirect, Session;
use Illuminate\Http\Request;
use Acr_fl;
use AcrMenu;
use Image;
use App\Http\Controllers\Controller;
use Illuminate\Config\Repository;

class SfController extends Controller
{

    protected $config;
    protected $system_type;

    function duyuru_statistic(Request $request)
    {
        $duyuru_model = new Duyuru();
        $duyuru_id    = $request->id;
        $duyuru       = $duyuru_model->where('id', $duyuru_id)->with('duyuru_users')->first();
        return view('Acr_sfv::statistic_detay', compact('duyuru'))->render();
    }

    function duyuru_oku(Request $request)
    {
        $duyuru_model = new Duyuru_user();
        $duyuru_id    = $request->id;
        $oku          = $request->oku;
        $sayi         = $duyuru_model->where('duyuru_id', $duyuru_id)->where('user_id', Auth()->id())->count();
        if ($sayi < 1) {
            $data = [
                'okundu'    => 1,
                'user_id'   => Auth()->id(),
                'duyuru_id' => $duyuru_id
            ];
            $duyuru_model->insert($data);
            return 1;
        }
        if ($oku == 1) {
            $duyuru_model->where('duyuru_id', $duyuru_id)->where('user_id', Auth()->id())->update(['okundu' => 1]);
            return 1;
        }
        $duyuru = $duyuru_model->where('duyuru_id', $duyuru_id)->where('user_id', Auth()->id())->first();
        if ($duyuru->okundu == 1) {
            $okundu = 0;
            $duyuru_model->where('duyuru_id', $duyuru_id)->where('user_id', Auth()->id())->update(['okundu' => $okundu]);
        } else {
            $okundu = 1;
            $duyuru_model->where('duyuru_id', $duyuru_id)->where('user_id', Auth()->id())->update(['okundu' => $okundu]);
        }
        return $okundu;
    }

    function duyuru_incele(Request $request)
    {
        $duyuru_model = new Duyuru();
        $duyuru_id    = $request->id;
        $duyuru       = $duyuru_model->where('id', $duyuru_id)->first();
        return view('Acr_sfv::modal_detay', compact('duyuru'))->render();
    }

    function wh()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.whatsapp.com/send");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "phone905434259887&text=delidelikubei");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 25);

        $response = curl_exec($ch);
        $info     = curl_getinfo($ch);
        curl_close($ch);
    }

    function duyuru_delete(Request $request)
    {
        $duyuru_model = new Duyuru();
        $duyuru_id    = $request->id;
        $duyuru_model->where('id', $duyuru_id)->where('user_id', Auth::user()->id)->delete();
    }

    function duyuru_kaydet(Request $request)
    {
        $duyuru_model = new Duyuru();
        $duyuru_id    = $request->duyuru_id;
        $data         = [
            'name'        => $request->name,
            'description' => $request->description,
            'user_id'     => Auth::user()->id,
        ];


        if (empty($duyuru_id)) {
            $duyuru_id = $duyuru_model->insertGetId($data);
        } else {
            $duyuru_model->where('id', $duyuru_id)->where('user_id', Auth::user()->id)->update($data);
        }
        $duyuru_user_model = new Duyuru_user();
        $user_ids          = $this->user_ids(6);
        foreach ($user_ids as $user_id) {
            $data_user[] = [
                'user_id'   => $user_id,
                'duyuru_id' => $duyuru_id
            ];
        }

        $duyuru_user_model->insert($data_user);
        return redirect()->back()->with('msg', $this->basarili());
    }

    function kurum_ekle(Request $request)
    { // kuruma dosya ve duyuru ekleme alanı
        $file_model   = new Files();
        $afc_model    = new Acr_files_childs();
        $duyuru_model = new Duyuru();
        $roles        = AcrMenu::roles();
        $yonetici_id  = $this->sf_yonetici_id(5);
        $file_sf      = $file_model->where('user_id', $yonetici_id)->with([
            'files'
        ])->first();
        $acr_file_id  = $file_sf->acr_file_id;
        $acr_fl_data  = [
            'acr_file_id' => $acr_file_id
        ];
        $disk         = $afc_model->where('acr_file_id', $acr_file_id)->sum('file_size');
        $duyurular    = $duyuru_model->where('user_id', $yonetici_id)->get();
        $duyuru_id    = $request->duyuru_id;
        $duyuru       = $duyuru_model->where('id', $duyuru_id)->where('user_id', $yonetici_id)->first();
        $disk_use     = round($disk / 1000000, 2);
        return view('Acr_sfv::kurum_ekle', compact('file_sf', 'acr_fl_data', 'files', 'imgs', 'acr_file_id', 'roles', 'duyurular', 'disk_use', 'duyuru'));

    }

    public function __construct(Repository $repository)
    {
        $this->config      = $repository;
        $this->system_type = config('acr_sf.system_type');
    }

    // user_id yöneticilerdir, dosyalar yöneticelerin üyeliklerine tanımlıdır. Alt kullanıcılar yöneticlerin eklediği dosyaları görebilirler. Mantık budur.
    function kurum()
    {

        $file_model  = new Files();
        $yonetici_id = $this->sf_yonetici_id(5);
        $sayi        = $file_model->where('user_id', $yonetici_id)->with('files')->count();
        if ($sayi < 1) {
            $data = [
                'user_id'     => $yonetici_id,
                'acr_file_id' => Acr_fl::acr_file_id()
            ];
            $file_model->where('user_id', $yonetici_id)->insert($data);
        }
        $file_sf     = $file_model->where('user_id', $yonetici_id)->with('files')->first();
        $acr_file_id = $file_sf->acr_file_id;
        $imgs        = [];
        $files       = [];
        if (!empty($file_sf->files)) {
            foreach ($file_sf->files as $file) {
                if (!in_array($file->file_type, [
                    'jpg',
                    'jpeg',
                    'png',
                    'web',
                    'svg',
                    'gif'
                ])) {
                    $files[] = $file->id;
                } else {
                    $imgs[] = $file->id;
                }
            }
        }
        $duyuru_model = new Duyuru();

        $duyurular = $duyuru_model->where('user_id', $yonetici_id)->with([
            'user',
            'duyuru_user' => function ($q) {
                $q->where('user_id', Auth()->id());
            }
        ])->get();
        return view('Acr_sfv::kurum', compact('file_sf', 'acr_fl_data', 'files', 'imgs', 'duyurular', 'acr_file_id'));
    }

    function sf_yonetici_id($role_id)
    {
        if ($this->system_type == 1) {
            $yonetici_id = $this->yonetici_ids($role_id);
            if (empty($yonetici_id)) {
                $yonetici_id = Auth::user()->id;
            } else {
                $yonetici_id = $this->yonetici_ids(5)[0];
            }
        }
        return $yonetici_id;
    }
}
