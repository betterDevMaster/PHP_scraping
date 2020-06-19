<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<?php

    include('simple_html_dom.php');

    $ser_key1 = "";
    $ser_key2 = "";
    $ser_key3 = "";
    $ser_key4 = "";

    $ser_res1_1 = 0;
    $ser_res1_2 = 0;
    $ser_res1_3 = 0;

    $ser_res2_1 = 0;
    $ser_res2_2 = 0;
    $ser_res2_3 = 0;

    $ser_res3_1 = 0;
    $ser_res3_2 = 0;
    $ser_res3_3 = 0;

    $ser_res4_1 = 0;
    $ser_res4_2 = 0;
    $ser_res4_3 = 0;

    if (isset ($_POST['smtCompetition']))
    {
        $ser_key1 = $_POST['ser_key1'];
        $ser_key2 = $_POST['ser_key2'];
        $ser_key3 = $_POST['ser_key3'];
        if ($ser_key1 != '') {
            $result = curl($ser_key1, 1);
            $ser_res1_1 = serResult($result);
            
            $result = curl($ser_key1, 2);
            $ser_res1_2 = serResult($result);

            $result = curl($ser_key1, 3);
            $ser_res1_3 = serResult($result);
        }
        if ($ser_key2 != '') {
            $result = curl($ser_key2, 1);
            $ser_res2_1 = serResult($result);

            $result = curl($ser_key2, 2);
            $ser_res2_2 = serResult($result);

            $result = curl($ser_key2, 3);
            $ser_res2_3 = serResult($result);
        }
        if ($ser_key3 != '') {
            $result = curl($ser_key3, 1);
            $ser_res3_1 = serResult($result);

            $result = curl($ser_key3, 2);
            $ser_res3_2 = serResult($result);

            $result = curl($ser_key3, 3);
            $ser_res3_3 = serResult($result);
        }
        setcookie("ser_key1", $ser_key1, time()+1000000, "/","", 0); 
        setcookie("ser_key2", $ser_key2, time()+1000000, "/","", 0); 
        setcookie("ser_key3", $ser_key3, time()+1000000, "/","", 0); 
        setcookie("ser_key4", $ser_key4, time()+1000000, "/","", 0); 
        
        setcookie("ser_res1_1", $ser_res1_1, time()+1000000, "/","", 0); 
        setcookie("ser_res1_2", $ser_res1_2, time()+1000000, "/","", 0); 
        setcookie("ser_res1_3", $ser_res1_3, time()+1000000, "/","", 0); 
        setcookie("ser_res2_1", $ser_res2_1, time()+1000000, "/","", 0); 
        setcookie("ser_res2_2", $ser_res2_2, time()+1000000, "/","", 0); 
        setcookie("ser_res2_3", $ser_res2_3, time()+1000000, "/","", 0); 
        setcookie("ser_res3_1", $ser_res3_1, time()+1000000, "/","", 0); 
        setcookie("ser_res3_2", $ser_res3_2, time()+1000000, "/","", 0); 
        setcookie("ser_res3_3", $ser_res3_3, time()+1000000, "/","", 0); 
    }
    
    if (isset ($_POST['smtPenetration']))
    {
        $ser_key4 = $_POST['ser_key4'];
        
        $ser_key1 = $_COOKIE["ser_key1"];
        $ser_key2 = $_COOKIE["ser_key2"];
        $ser_key3 = $_COOKIE["ser_key3"];
        $ser_res1_1 = $_COOKIE["ser_res1_1"];
        $ser_res1_2 = $_COOKIE["ser_res1_2"];
        $ser_res1_3 = $_COOKIE["ser_res1_3"];
        $ser_res2_1 = $_COOKIE["ser_res2_1"];
        $ser_res2_2 = $_COOKIE["ser_res2_2"];
        $ser_res2_3 = $_COOKIE["ser_res2_3"];
        $ser_res3_1 = $_COOKIE["ser_res3_1"];
        $ser_res3_2 = $_COOKIE["ser_res3_2"];
        $ser_res3_3 = $_COOKIE["ser_res3_3"];

        $result = curl($ser_key4, 1);
        $ser_res4_1 = serResult($result);

        $result = curl($ser_key4, 2);
        $ser_res4_2 = serResult($result);

        $result = curl($ser_key4, 3);
        $ser_res4_3 = serResult($result);
    }

    function serResult($result) {
        $domResult = new simple_html_dom();
        $domResult->load($result);
    
        foreach($domResult->find('div[id=result-stats]') as $link) {
            $res = $link->plaintext;
        }

        $str = preg_replace('/\D/', '', $res);
        $res = substr($str, 0, strlen($str)-3);

        return $res;
    }

    function curl($ser, $period) {
        $key = '';
        $keys = explode(" ",$ser);
        $arr_leng = count($keys);
        
        for ($i=0; $i<$arr_leng; $i++) {
            if ($i == 0 || $i == $arr_leng)
                $key .= $keys[$i];
            else {
                $key .= '+'.$keys[$i];
            }
        }

        if ($period == 1) //overall
            $url            = 'https://www.google.com/search?q='.$key;
        else if ($period == 2) //year
            $url            = 'https://www.google.com/search?q='.$key.'&tbs=qdr:y';
        else if ($period == 3) //month
            $url            = 'https://www.google.com/search?q='.$key.'&tbs=qdr:m';

        $curlUserAgent      = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36';
        $curlFollowLocation = true;
        $curlTimeout        = 150;
        $curlMaxRedirects   = 1;
            
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_USERAGENT      => $curlUserAgent
        ));

        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
?>

<div class="container">        
    <form method='post' style="margin-top: 5rem;">
        <h1 style="text-align: center; color: #5cea4b; margin-bottom: 2rem">Brand Competitor Analysis</h1>   
        <h3>Enter 3 Competitors</h3>   
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Competitor 1</th>
                    <th>Competitor 2</th>
                    <th>Competitor 3</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="line-height: 35px;">Competitors</td>
                    <td></td>
                    <td>
                        <input type="text" class="form-control" name="ser_key1" id="ser_key1" value="<?php echo $ser_key1;?>" placeholder="search key1" >
                    </td>
                    <td>
                        <input type="text" class="form-control" name="ser_key2" id="ser_key2" value="<?php echo $ser_key2;?>" placeholder="search key2" >
                    </td>
                    <td>
                        <input type="text" class="form-control" name="ser_key3" id="ser_key3" value="<?php echo $ser_key3;?>" placeholder="search key3" >
                    </td>
                </tr>
                <tr>
                    <td>Brand Breadth</td>
                    <td></td>
                    <td><?php echo number_format($ser_res1_1) ?></td>
                    <td><?php echo number_format($ser_res2_1) ?></td>
                    <td><?php echo number_format($ser_res3_1) ?></td>
                </tr>
                <tr>
                    <td>Brand Velocity</td>
                    <td></td>
                    <td><?php echo number_format($ser_res1_2) ?></td>
                    <td><?php echo number_format($ser_res2_2) ?></td>
                    <td><?php echo number_format($ser_res3_2) ?></td>
                </tr>
                <tr>
                    <td>Brand Acceleration</td>
                    <td></td>
                    <td><?php echo number_format($ser_res1_3) ?></td>
                    <td><?php echo number_format($ser_res2_3) ?></td>
                    <td><?php echo number_format($ser_res3_3) ?></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" name="smtCompetition" value="Search Competitors"/>
    </form>

    <form method='post' style="margin-top: 3rem;">
        <h3>Find Brand Penetration</h3>
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td style="line-height: 35px;">Industry</td>
                    <td>
                        <input type="text" class="form-control" name="ser_key4" value="<?php echo $ser_key4;?>" placeholder="search key4" >
                    </td>
                </tr>
                <tr>
                    <td>Brand Breadth</td>
                    <td><?php echo number_format($ser_res4_1) ?></td>
                    <td><?php if ($ser_res1_1 !=0 && $ser_res4_1 != 0) echo (round($ser_res1_1/$ser_res4_1*100, 2)).'%'?></td>
                    <td><?php if ($ser_res2_1 !=0 && $ser_res4_1 != 0) echo (round($ser_res2_1/$ser_res4_1*100, 2)).'%'?></td>
                    <td><?php if ($ser_res3_1 !=0 && $ser_res4_1 != 0) echo (round($ser_res3_1/$ser_res4_1*100, 2)).'%'?></td>
                </tr>
                <tr>
                    <td>Brand Velocity</td>
                    <td><?php echo number_format($ser_res4_2) ?></td>
                    <td><?php if ($ser_res1_2 !=0 && $ser_res4_2 != 0) echo (round($ser_res1_2/$ser_res4_2*100, 2)).'%'?></td>
                    <td><?php if ($ser_res2_2 !=0 && $ser_res4_2 != 0) echo (round($ser_res2_2/$ser_res4_2*100, 2)).'%'?></td>
                    <td><?php if ($ser_res3_2 !=0 && $ser_res4_2 != 0) echo (round($ser_res3_2/$ser_res4_2*100, 2)).'%'?></td>
                </tr>
                <tr>
                    <td>Brand Acceleration</td>
                    <td><?php echo number_format($ser_res4_3) ?></td>
                    <td><?php if ($ser_res1_3 !=0 && $ser_res4_3 != 0) echo (round($ser_res1_3/$ser_res4_3*100, 2)).'%'?></td>
                    <td><?php if ($ser_res2_3 !=0 && $ser_res4_3 != 0) echo (round($ser_res2_3/$ser_res4_3*100, 2)).'%'?></td>
                    <td><?php if ($ser_res3_3 !=0 && $ser_res4_3 != 0) echo (round($ser_res3_3/$ser_res4_3*100, 2)).'%'?></td>
                </tr>
            </tbody>
        </table>
        <input name="smtPenetration" type="submit" value="Find Brand Penetration"/>
    </form>
</div>

<style>
    table thead tr th {
        width: 20%;
    }
    table tbody tr td {
        width: 20%;
    }
</style>
