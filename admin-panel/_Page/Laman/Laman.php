<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'t15FiRT4STK6EWW8aLC');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        if(empty($_GET['Sub'])){
            include "_Page/Laman/LamanHome.php";
        }else{
            $Sub=$_GET['Sub'];
            if($Sub=="DetailLaman"){
                include "_Page/Laman/DetailLaman.php";
            }else{
                include "_Page/Laman/LamanHome.php";
            }
        }
    }
?>