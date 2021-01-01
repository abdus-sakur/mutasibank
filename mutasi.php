<?php

class Header
{
    const API_URL = "https://mutasibank.co.id/api/v1";
    const API_KEY = "WlA5V1dONHk3VVdFalFLcmNzaldJMGluWEh3NkY3SDMwaDd3dmxINUQ1bUplSVhnNk5ZYmZCNDB6Mllp5dba5f95d2748";

    public static function GetAccountStatement($id, $date1, $date2)
    {
        $header = [
            "Authorization: " . self::API_KEY,
        ];

        $data = array('date_from' => $date1, 'date_to' => $date2);

        return self::http_post(self::API_URL . "/statements/$id", $data, $header);
    }

    public static function http_post($url, $param = [], $headers = [])
    {
        $response = array();
        //set POST variables
        $fields_string = http_build_query($param);
        //open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0");
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //execute post
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        return $result;
    }
}
$id = $_POST['id_bank'];
$bank = $_POST['bank'];
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$data = Header::GetAccountStatement($id, $date1, $date2);
$result = json_decode($data);
// var_dump($result);
// die();
function rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="/restclientIP/css/style.css">
    <title>RestClient - Mutasi Bank</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/restclientIP">Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" href="/restclientIP/data.php">Data Bank </a>
                </div> -->
        </div>
        </div>
    </nav>
    <div class="container">
        <h3 class="mt-4">Mutasi Rekening <?= $result->account_number; ?> - <?= $result->account_name; ?></h3>
        <h3 class="mt-2"><?= $bank; ?></h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tanggal Transaksi</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Jumlah Transaksi</th>
                    <th scope="col">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($result->data as $r) : ?>
                    <tr>
                        <th class="no"><?= $no++; ?></th>
                        <td class="tgl"><?= $r->transaction_date; ?></td>
                        <td class="ket"><?= $r->description; ?></td>
                        <td><?= rupiah($r->amount); ?></td>
                        <td><?= rupiah($r->balance); ?></td>
                    <?php endforeach; ?>
                    </tr>
            </tbody>
        </table>
        <a href="/restclientIP" class="btn btn-danger">Kembali</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>