<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'jYG0tdHHYbQT0abffgc');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        if(empty($_GET['Sub'])){
            include "_Page/BlogList/BlogListHome.php";
        }else{
            $Sub=$_GET['Sub'];
            if($Sub=="DetailBlog"){
                include "_Page/BlogList/DetailBlog.php";
            }else{
                include "_Page/BlogList/BlogListHome.php";
            }
        }
    }
?>