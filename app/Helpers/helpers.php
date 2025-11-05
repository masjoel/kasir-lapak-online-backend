<?php

namespace App\Helpers;

use App\Models\Perusahaan;
use App\Models\ProfilUsaha;
use Illuminate\Support\Facades\DB;

class helpers
{
    public static function infoprofil($data)
    {
        $desa = DB::table("perusahaans")->select($data)->first();
        return $desa->$data;
    }
    public static function klien($data)
    {
        $siklien = DB::table("perusahaans")->select($data)->first();
        return $siklien->$data;
    }

    public static function kalender($tanggalDiDb)
    {
        $bln   = array('');
        switch (date('m', strtotime($tanggalDiDb))) {
            case 1:
                $bln = array("Januari");
                break;
            case 2:
                $bln = array("Februari");
                break;
            case 3:
                $bln = array("Maret");
                break;
            case 4:
                $bln = array("April");
                break;
            case 5:
                $bln = array("Mei");
                break;
            case 6:
                $bln = array("Juni");
                break;
            case 7:
                $bln = array("Juli");
                break;
            case 8:
                $bln = array("Agustus");
                break;
            case 9:
                $bln = array("September");
                break;
            case 10:
                $bln = array("Oktober");
                break;
            case 11:
                $bln = array("November");
                break;
            case 12:
                $bln = array("Desember");
                break;
            default:
                break;
        }
        $tanggal = date('d', strtotime($tanggalDiDb)) . " " . $bln[0] . " " . date('Y', strtotime($tanggalDiDb));
        if ($tanggalDiDb == "0000-00-00" || $tanggalDiDb == "0000-00-00 00:00:00") {
            $tanggal = '';
        }
        return $tanggal;
    }
    public static function kal($tanggalDiDb)
    {
        $bln   = array('');
        switch (date('m', strtotime($tanggalDiDb))) {
            case 1:
                $bln = array("Jan");
                break;
            case 2:
                $bln = array("Feb");
                break;
            case 3:
                $bln = array("Mar");
                break;
            case 4:
                $bln = array("Apr");
                break;
            case 5:
                $bln = array("Mei");
                break;
            case 6:
                $bln = array("Jun");
                break;
            case 7:
                $bln = array("Jul");
                break;
            case 8:
                $bln = array("Agt");
                break;
            case 9:
                $bln = array("Sep");
                break;
            case 10:
                $bln = array("Okt");
                break;
            case 11:
                $bln = array("Nov");
                break;
            case 12:
                $bln = array("Des");
                break;
            default:
                break;
        }
        $tanggal = date('d', strtotime($tanggalDiDb)) . " " . $bln[0] . " " . date('Y', strtotime($tanggalDiDb));
        if ($tanggalDiDb == "0000-00-00" || $tanggalDiDb == "0000-00-00 00:00:00") {
            $tanggal = '';
        }
        return $tanggal;
    }
    public static function tgldmY($tanggalDiDb)
    {
        $tanggal = date('d-m-Y', strtotime($tanggalDiDb));
        if ($tanggalDiDb == "0000-00-00" || $tanggalDiDb == "0000-00-00 00:00:00") {
            $tanggal = '';
        }
        return $tanggal;
    }
    public static function tgljam($tanggalDiDb)
    {
        $tanggal = date('d-m-Y H:i:s', strtotime($tanggalDiDb));
        if ($tanggalDiDb == "0000-00-00" || $tanggalDiDb == "0000-00-00 00:00:00") {
            $tanggal = '';
        }
        return $tanggal;
    }
    public static function romawi($tanggalDiDb)
    {
        $bln   = '';
        $date = explode("-", $tanggalDiDb);
        if ($date[2] == 00) {
            $tanggal = "";
        } else {
            switch ($date[1]) {
                case 1:
                    $bln = "I";
                    break;
                case 2:
                    $bln = "II";
                    break;
                case 3:
                    $bln = "III";
                    break;
                case 4:
                    $bln = "IV";
                    break;
                case 5:
                    $bln = "V";
                    break;
                case 6:
                    $bln = "VI";
                    break;
                case 7:
                    $bln = "VII";
                    break;
                case 8:
                    $bln = "VIII";
                    break;
                case 9:
                    $bln = "IX";
                    break;
                case 10:
                    $bln = "X";
                    break;
                case 11:
                    $bln = "XI";
                    break;
                case 12:
                    $bln = "XII";
                    break;
                default:
                    break;
            }
            $tanggal = $bln;
        }
        return $tanggal;
    }

    public static function tglYmd($tanggalDiDb)
    {
        if ($tanggalDiDb <> '') :
            $date = explode("-", $tanggalDiDb);
            $tanggal = $date[2] . "-" . $date[1] . "-" . $date[0];
        else : $tanggal = '0000-00-00';
        endif;
        return $tanggal;
    }

    public static function hari($tanggalDiDb)
    {
        $hr   = array('');
        $date = date("N", strtotime($tanggalDiDb));
        switch ($date) {
            case 1:
                $hr = array("Senin");
                break;
            case 2:
                $hr = array("Selasa");
                break;
            case 3:
                $hr = array("Rabu");
                break;
            case 4:
                $hr = array("Kamis");
                break;
            case 5:
                $hr = array("Jum'at");
                break;
            case 6:
                $hr = array("Sabtu");
                break;
            case 7:
                $hr = array("Minggu");
                break;
            default:
                break;
        }
        $tanggal = $hr[0];
        return $tanggal;
    }
    public static function bulan($tanggalDiDb)
    {
        $bln   = '';
        $date = explode("-", $tanggalDiDb);
        if ($date[2] == 00) {
            $tanggal = "";
        } else {
            switch ($date[1]) {
                case 1:
                    $bln = "Januari";
                    break;
                case 2:
                    $bln = "Februari";
                    break;
                case 3:
                    $bln = "Maret";
                    break;
                case 4:
                    $bln = "April";
                    break;
                case 5:
                    $bln = "Mei";
                    break;
                case 6:
                    $bln = "Juni";
                    break;
                case 7:
                    $bln = "Juli";
                    break;
                case 8:
                    $bln = "Agustus";
                    break;
                case 9:
                    $bln = "September";
                    break;
                case 10:
                    $bln = "Oktober";
                    break;
                case 11:
                    $bln = "November";
                    break;
                case 12:
                    $bln = "Desember";
                    break;
                default:
                    break;
            }
            $tanggal = $bln;
        }
        return $tanggal;
    }
    public static function enumselect($table = '', $field = '')
    {
        $enums = array();
        if ($table == '' || $field == '') return $enums;
        $type = DB::select(DB::raw("SHOW COLUMNS FROM {$table} LIKE '{$field}'"))[0]->Type;
        preg_match_all("/'(.*?)'/", $type, $matches);
        foreach ($matches[1] as $value) {
            $enums[$value] = $value;
        }
        return $enums;
    }
    public static function cekAktivasi()
    {
        $env = config('app.key');
        $mac = helpers::getMacAddressLinux();
        if ($mac == null) {
            $mac = helpers::getSerialNumber();
        }
        if ($mac == null) {
            $mac = helpers::getMacAddress();
        }
        $mac = $env;
        $init = substr(strtoupper(md5($mac)), 0, 9);
        $init = strtoupper(substr(md5($init), 5, 5));
        $cek1 = Perusahaan::where('init', $init)->count();
        $cek2 = Perusahaan::where('mcad', $mac)->count();
        $mcad = $mac;
        return compact('cek1', 'cek2', 'mcad');
    }
    public static function getMacAddress()
    {
        ob_start();
        // system('ipconfig /all');
        shell_exec('ipconfig /all');
        $output = ob_get_clean();
        $findme = "Physical Address";
        $pos = strpos($output, $findme);
        if ($pos === false) {
            return null;
        }
        $macAddress = trim(substr($output, $pos + 36, 17));
        return $macAddress;
    }
    public static function getMacAddressLinux()
    {
        ob_start();
        $output = shell_exec('ip link');
        // system('ifconfig');
        // $output = ob_get_clean();
        if (preg_match('/ether\s+([0-9a-f:]+)/', $output, $matches)) {
            return $matches[1];
        } else {
            return null;
        }
    }
    public static function getSerialNumber()
    {
        $command = 'powershell "Get-WmiObject Win32_PhysicalMedia | Select-Object -ExpandProperty SerialNumber"';
        $output = shell_exec($command);
        $lines = explode("\n", trim($output));
        $serialNumbers = array_filter(array_map('trim', $lines), function ($line) {
            return !empty($line) && stripos($line, 'SerialNumber') === false;
        });
        $output = implode('', $serialNumbers);

        if (empty($output)) {
            return null;
        }
        return trim($output);
    }
}
