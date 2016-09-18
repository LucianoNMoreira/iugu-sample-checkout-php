<?php 

    function curlExec($url, $post = NULL, array $header = array()){
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        if(count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if($post !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
    
        //Ignore SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
?>

<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php 
    $API_TOKEN_TEST = "YOUR_API_TOKEN";

    $token = htmlspecialchars($_POST["token"]);

    $URL_IUGU_API = 'https://api.iugu.com/v1/charge';
    $params = array(
        'token'                 => $token, 
        'email'                 => 'test@mail.com',
        'test'                  => true,
        'items[][description]'  => 'Iugu PHP Test',  
        'items[][quantity]'     => 1,  
        'items[][price_cents]'  => '500'  
    );

    $header = array('Authorization' => 'Authorization: Basic ' . base64_encode( $API_TOKEN_TEST . ':' . "" ));

    $response = curlExec($URL_IUGU_API, $params, $header);
    
    $obj = json_decode($response);
    $errors = $obj->errors;
    $invoice_id = $obj->invoice_id;
    $invoice_pdf = $obj->pdf;
?>
<body>
    <h1>Iugu Test</h1>
    <h3>Your Invoice ID: <?php echo $invoice_id ?></h3>
    <p>Response: <?php echo $response ?></p>

    <?php if($obj->success){ ?>
        <script>
             window.open('<?php echo $invoice_pdf; ?>', 'Your Invoice'); 
        </script>
    <?php } ?>
</body>
</html>