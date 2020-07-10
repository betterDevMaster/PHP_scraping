<?php
    session_start();
    header('Access-Control-Allow-Credentials: true');
?>
<!-- saved from url=(0034)http://accelerativ.technopium.com/ -->
<html lang="en" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Accelerativ</title>
<link rel="stylesheet" href="images/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" media="screen" href="images/assest-redesign.css">
<link rel="stylesheet" media="screen" href="images/custom_style.css">
<link rel="stylesheet" href="images/font-awesome.min.css">

<!--new font-->
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

</head>
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
        
        $_SESSION['ser_key1'] = $ser_key1;
        $_SESSION['ser_key2'] = $ser_key2;
        $_SESSION['ser_key3'] = $ser_key3;
        $_SESSION['ser_res1_1'] = $ser_res1_1;
        $_SESSION['ser_res1_2'] = $ser_res1_2;
        $_SESSION['ser_res1_3'] = $ser_res1_3;
        $_SESSION['ser_res2_1'] = $ser_res2_1;
        $_SESSION['ser_res2_2'] = $ser_res2_2;
        $_SESSION['ser_res2_3'] = $ser_res2_3;
        $_SESSION['ser_res3_1'] = $ser_res3_1;
        $_SESSION['ser_res3_2'] = $ser_res3_2;
        $_SESSION['ser_res3_3'] = $ser_res3_3;
        
    }
    
    if (isset ($_POST['smtPenetration']))
    {
        $ser_key4 = $_POST['ser_key4'];
        $ser_key1 = $_SESSION["ser_key1"];
        $ser_key2 = $_SESSION["ser_key2"];
        $ser_key3 = $_SESSION["ser_key3"];
        $ser_res1_1 = $_SESSION["ser_res1_1"];
        $ser_res1_2 = $_SESSION["ser_res1_2"];
        $ser_res1_3 = $_SESSION["ser_res1_3"];
        $ser_res2_1 = $_SESSION["ser_res2_1"];
        $ser_res2_2 = $_SESSION["ser_res2_2"];
        $ser_res2_3 = $_SESSION["ser_res2_3"];
        $ser_res3_1 = $_SESSION["ser_res3_1"];
        $ser_res3_2 = $_SESSION["ser_res3_2"];
        $ser_res3_3 = $_SESSION["ser_res3_3"];

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
<body id="home-story">

<section class="global-network explanatory-section">
        <h2>Brand Competitor Analysis</h2>
        <div class="container">
    <form method='post' >
	<h3 class="newtitletr">Enter 3 Competitors</h3>   
     
        <table class="table table-borderless newtable newtablebg">
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
        <input class="slim-btn2" type="submit" name="smtCompetition" value="Search Competitors"/>
    </form>

    <form method='post' style="margin-top: 3rem;">
       <h3 class="newtitletr">Find Brand Penetration</h3>
	   
	   <table class="table table-borderless newtable newtablebg">
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
        <input class="slim-btn2" name="smtPenetration" type="submit" value="Find Brand Penetration"/>
    </form>
</div>
       
</section>
</body>
<!-- <script>
    localStorage.setItem('ser_key1', '<?php echo $_SESSION['ser_key1'];?>');  
</script> -->
</html>