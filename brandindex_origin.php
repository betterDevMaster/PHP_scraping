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
    include('parallelcurl.php');

    $terms_list = [];
    $checked = [];

    $_SESSION['ser_res_0_1_1'] = 0;
    $_SESSION['ser_res_0_1_2'] = 0;
    $_SESSION['ser_res_0_2_1'] = 0;
    $_SESSION['ser_res_0_2_2'] = 0;
    $_SESSION['ser_res_0_3_1'] = 0;
    $_SESSION['ser_res_0_3_2'] = 0;
    $_SESSION['ser_res_1_1_1'] = 0;
    $_SESSION['ser_res_1_1_2'] = 0;
    $_SESSION['ser_res_1_2_1'] = 0;
    $_SESSION['ser_res_1_2_2'] = 0;
    $_SESSION['ser_res_1_3_1'] = 0;
    $_SESSION['ser_res_1_3_2'] = 0;
    
    $_SESSION['ser_res_2_1_1'] = 0;
    $_SESSION['ser_res_2_1_2'] = 0;
    $_SESSION['ser_res_2_2_1'] = 0;
    $_SESSION['ser_res_2_2_2'] = 0;
    $_SESSION['ser_res_2_3_1'] = 0;
    $_SESSION['ser_res_2_3_2'] = 0;

    $_SESSION['ser_res_3_1_1'] = 0;
    $_SESSION['ser_res_3_1_2'] = 0;
    $_SESSION['ser_res_3_2_1'] = 0;
    $_SESSION['ser_res_3_2_2'] = 0;
    $_SESSION['ser_res_3_3_1'] = 0;
    $_SESSION['ser_res_3_3_2'] = 0;
    
    $json = 
        '[{"platform":"Google","link":"https://www.google.com","value":"https://www.google.com/search?q="},
        {"platform":"Yelp","link":"https://www.yelp.com","value":"https://www.yelp.com/search?find_desc="},
        {"platform":"Reddit","link":"https://www.reddit.com","value":"https://www.reddit.com/search/?q="},
        {"platform":"Ebay","link":"https://www.ebay.com","value":"https://www.ebay.com/sch/i.html?_nkw="}
        ]';
    // Decode to object
    $data = json_decode($json);
    if (isset ($_POST['smtCompetition']))
    {
        isset($_POST['ser_key1']) ? array_push($terms_list, removeSpace($_POST['ser_key1'])) : null;
        isset($_POST['ser_key2']) ? array_push($terms_list, removeSpace($_POST['ser_key2'])) : null;
        isset($_POST['ser_key3']) ? array_push($terms_list, removeSpace($_POST['ser_key3'])) : null;

        foreach ($data as $key => $check) {
            $check_val = isset($_POST['checked'.$check->platform]) ? $_POST['checked'.$check->platform] : null;
            $check_val != null ? array_push($checked, $check_val) : null;
        }
        
        if (isset($argv[1])) {
            $max_requests = $argv[1];
        } else {
            $max_requests = 10;
        }

        $curl_options = array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
        );

        $parallel_curl = new ParallelCurl($max_requests, $curl_options);

        foreach ($checked as $value) { // URL check
            foreach ($terms_list as $search) {
                if ( $search == null) continue;

                $search_url = $value.$search;
                $parallel_curl->startRequest($search_url, 'on_request_done', $search);
            }
        }
        
        $parallel_curl->finishAllRequests();
    }
  
    function removeSpace($val) {
        $key = '';
        $keys = explode(" ",$val);
        $arr_leng = count($keys);
        
        for ($i=0; $i<$arr_leng; $i++) {
            if ($i == 0 || $i == $arr_leng)
                $key .= $keys[$i];
            else {
                $key .= '+'.$keys[$i];
            }
        }
        return $key;
    }
    
    function on_request_done($content, $url, $ch, $search) {
        echo 'search----------'.$search.'<br>';
        echo 'url----------'.$url.'<br>';
        
        print_r($_POST);

        $domResult = new simple_html_dom();
        $domResult->load($content);

        if (strpos($url, 'google') !== false) {
            foreach($domResult->find('div[id=result-stats]') as $link) {
                $res = $link->plaintext; 
            }
            echo 'res---------'.$res.'<br>';
            preg_match('/\d{1,3}(,\d{3})*(\.\d+)?/', $res, $matchesRes);  //Google
            echo 'str---------'.$matchesRes[0].'<br>';

            if (isset($_POST['ser_key1'])) {
                if (strlen($_POST['ser_key1'])>0 && $_SESSION['ser_res_0_1_1']==0) {
                    $_SESSION['ser_res_0_1_1']=$matchesRes[0];
                    return;
                }
            }
            if (isset($_POST['ser_key2'])) {
                if (strlen($_POST['ser_key2'])>0 && $_SESSION['ser_res_0_2_1']==0) {
                    $_SESSION['ser_res_0_2_1']=$matchesRes[0];
                    return;
                }
            }
            if (isset($_POST['ser_key3'])) {
                if (strlen($_POST['ser_key3'])>0 && $_SESSION['ser_res_0_3_1']==0)  {
                    $_SESSION['ser_res_0_3_1']=$matchesRes[0];
                    return;
                }
            }
        }
    }
?>
<body id="home-story">

<section class="global-network explanatory-section">
    <div class="row" style="margin: 10px">
        <h2>Brand Competitor Analysis</h2>
        <div class="col-sm-12">
            <h3 class="newtitletr">Enter 3 Competitors</h3> 
            <form method='post' >  
                <table class="table table-borderless newtable newtablebg">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Competitor 1</th>
                            <th>Competitor 2</th>
                            <th>Competitor 3</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $ser1 = "'".htmlspecialchars($_POST['ser_key1'])."'"; 
                        $ser2 = "'".htmlspecialchars($_POST['ser_key2'])."'"; 
                        $ser3 = "'".htmlspecialchars($_POST['ser_key3'])."'"; 
                    ?>
                        <tr>
                            <td>Select</td>
                            <td>Platform Channel</td>
                            <td>Link</td>
                            <td style="line-height: 35px;">Competitors</td>
                            <td>
                                <input type="text" class="form-control" name="ser_key1" placeholder="search key1" value=<?php echo isset($_POST['ser_key1'])?$ser1:''; ?> >
                                <!-- <input type="hidden" name="ser_key_val1_1" value="0" >
                                <input type="hidden" name="ser_key_val1_2" value="0" > -->
                            </td>
                            <td>
                                <input type="text" class="form-control" name="ser_key2" placeholder="search key2" value=<?php echo isset($_POST['ser_key2'])?$ser2:''; ?> >
                                <!-- <input type="hidden" name="ser_key_val2_1" value="0" >
                                <input type="hidden" name="ser_key_val2_2" value="0" > -->
                            </td>
                            <td>
                                <input type="text" class="form-control" style="white-space: nowrap;" name="ser_key3" placeholder="search key3" value=<?php echo isset($_POST['ser_key3'])?$ser3:''; ?> >
                                <!-- <input type="hidden" name="ser_key_val3_1" value="0" >
                                <input type="hidden" name="ser_key_val3_2" value="0" > -->
                            </td>
                        </tr>
                        <?php 
                        foreach($data as $key=>$ele) { 
                            $flag = isset($_POST['checked'.$ele->platform])&&strcmp($_POST['checked'.$ele->platform],$ele->value)==0?true:false;
                        ?>
                        <tr>
                            <td>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input tblb2b-input" name=<?php echo 'checked'.$ele->platform?> 
                                        value=<?php echo $ele->value?> <?php echo ($flag==true ? 'checked' : '');?> >
                                    <span class="custom-control-indicator"></span>
                                </label>
                            </td>
                            <td><?php echo $ele->platform?></td>
                            <td><?php echo $ele->link?></td>
                            <td>
                                <div>
                                    <label>Penetration</label>
                                </div>
                                <div>
                                    <label>Sentiment</label>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <label><?php echo $_SESSION['ser_res_'.$key.'_1_1']?></label>
                                </div>
                                <div>
                                    <label><?php echo $_SESSION['ser_res_'.$key.'_1_2']?></label>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <label><?php echo $_SESSION['ser_res_'.$key.'_2_1']?></label>
                                </div>
                                <div>
                                    <label><?php echo $_SESSION['ser_res_'.$key.'_2_2']?></label>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <label><?php echo $_SESSION['ser_res_'.$key.'_3_1']?></label>
                                </div>
                                <div>
                                    <label><?php echo $_SESSION['ser_res_'.$key.'_3_2']?></label>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <input class="slim-btn2" type="submit" name="smtCompetition" value="Search Competitors"/>
            </form>
        </div>
    </div>
</section>

</body>

</html>