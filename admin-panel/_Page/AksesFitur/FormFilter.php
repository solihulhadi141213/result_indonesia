<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    if(empty($_POST['KeywordBy'])){
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }else{
        $KeywordBy=$_POST['KeywordBy'];
        if($KeywordBy=="kategori"){
            echo '<select name="keyword" id="keyword" class="form-control">';
            echo '  <option value="">Pilih</option>';
             try {
                $stmt = $Conn->prepare("SELECT DISTINCT kategori FROM akses_fitur ORDER BY kategori ASC");
                $stmt->execute();
                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $kategori = htmlspecialchars($data['kategori']);
                    echo '<option value="'.$kategori.'">'.$kategori.'</option>';
                }
            } catch (PDOException $e) {
                echo '<option value="">' . $e->getMessage() . '</option>';
            }
            echo '</select>';
        }else{
            echo '<input type="text" name="keyword" id="keyword" class="form-control">';
        }
    }
?>