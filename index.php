<?php

class Header
{
    const API_URL = "https://mutasibank.co.id/api/v1";
    const API_KEY = "WlA5V1dONHk3VVdFalFLcmNzaldJMGluWEh3NkY3SDMwaDd3dmxINUQ1bUplSVhnNk5ZYmZCNDB6Mllp5dba5f95d2748";

    public static function GetAccount()
    {
        $header = [
            "Authorization: " . self::API_KEY,
        ];

        return self::http_get(self::API_URL . "/accounts", $header);
    }
    public static function http_get($url, $headers = array())
    {


        // is cURL installed yet?
        if (!function_exists('curl_init')) {
            die('Sorry cURL is not installed!');
        }

        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();

        // Now set some options (most are optional)

        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Set a referer
        curl_setopt($ch, CURLOPT_REFERER, $url);

        // User agent
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0");

        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 240);

        // Download the given URL, and return output
        $output = curl_exec($ch);

        // Close the cURL resource, and free system resources
        curl_close($ch);

        return $output;
    }
}

$data = Header::GetAccount();
$result = json_decode($data);
$date = date("Y-m-d");
// var_dump($result);
// die();
function rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}
?>


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

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
        <h1 class="mt-2">Data Mutasibank</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama Akun</th>
                    <th scope="col">Nama Bank</th>
                    <th scope="col">No. Rekening</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($result->data as $r) : ?>

                    <tr>
                        <th scope="row"><?= $no++; ?></th>
                        <td><?= $r->account_name; ?></td>
                        <td><?= $r->bank; ?></td>
                        <td><?= $r->account_no; ?></td>
                        <td>
                            <button class="btn btn-info" data-toggle="modal" data-target="#<?= $r->module; ?>">Saldo</button>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#m<?= $r->module; ?>">
                                Mutasi
                            </button>
                            <!-- <form action="/restclientIP/mutasi.php" method="post" class="d-inline">
                                <input type="hidden" name="mutasi" value="<?= $r->id; ?>">
                                <button type="submit" class="btn btn-warning">Mutasi Rekening</button>
                            </form> -->
                        </td>
                        <!-- modal saldo -->
                        <div id="<?= $r->module; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?= $r->account_name; ?> - <?= $r->bank; ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Saldo : <?= rupiah($r->balance); ?></h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- modal mutasi -->
                        <!-- Button trigger modal -->

                        <!-- Modal -->
                        <div class="modal fade" id="m<?= $r->module; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/restclientIP/mutasi.php" method="post" class="d-inline">
                                            <input type="hidden" name="id_bank" value="<?= $r->id; ?>">
                                            <input type="hidden" name="bank" value="<?= $r->bank; ?>">
                                            <div class="row mb-3">
                                                <label for="penulis" class="col-sm-2 col-form-label">From</label>
                                                <div class="col-sm-6">
                                                    <input type="date" class="form-control" id="date1" name="date1" value="<?= $date; ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="penulis" class="col-sm-2 col-form-label">To</label>
                                                <div class="col-sm-6">
                                                    <input type="date" class="form-control" id="date2" name="date2" value="<?= $date; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <button type="submit" class="btn btn-primary">Mutasi</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
    </div>
<?php endforeach; ?>
</tr>
</tbody>
</table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>