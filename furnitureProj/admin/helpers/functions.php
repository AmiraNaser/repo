<?php
function Clean($input, $flag = 0) {
    $input =  trim($input);

    if ($flag == 0) {
        $input =  filter_var($input, FILTER_SANITIZE_STRING);   
    }
    if ($flag == 1) {
        $input =  filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT);
    }
    return $input;
}

function Validate($input, $flag = 0) {
    $status = true;
    switch($flag) {
        case 0: 
            if (empty($input)) {
                $status = false;
            }
            break;
        case 1:
            if(!filter_var($input,FILTER_VALIDATE_EMAIL)){
                $status = false;
            }
            break;
        case 2:
            if(!filter_var($input,FILTER_VALIDATE_FLOAT)){
                $status = false;
            }
            break;
        case 3:
            if(strlen($input)<6){
                $status = false;
            }
            break;                                                   
        case 4:
            $nameArray =  explode('.', $input);
            $imgExtension =  strtolower(end($nameArray));
      
            $allowedExt = ['png', 'jpg', 'jpeg'];
    
            if (!in_array($imgExtension, $allowedExt)) {
                $status = false;
            }
            break;
        case 5:
            if(!filter_var($input,FILTER_VALIDATE_INT)){
                $status = false;
            }
            break;

    }
    return $status;
}
function displayMessages($text){

    if(isset($_SESSION['Message'])){
        foreach ($_SESSION['Message'] as $key => $value) {
            echo ' * '.$value.'<br>';
        }
        unset($_SESSION['Message']);

    }else{
       echo   '<li class="form-group">'.$text.'</li>';
    }
}
function uploadFile($input){

    $result = '';

    $imgName  = $input['image']['name'];
    $imgTemp  = $input['image']['tmp_name'];

    $nameArray =  explode('.', $imgName);
    $imgExtension =  strtolower(end($nameArray));
    $imgFinalName = time() . rand() . '.' . $imgExtension;

     
    $disPath = 'uploads/' . $imgFinalName;

    if (move_uploaded_file($imgTemp, $disPath)) {
      $result =  $imgFinalName ;
       }

      return $result;   
}
?>