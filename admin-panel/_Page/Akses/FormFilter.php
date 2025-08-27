<?php
    include "../../_Config/Connection.php";

    // Fungsi output HTML form
    function renderInputField() {
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }

    if (empty($_POST['keyword_by'])) {
        renderInputField();
    } else {
        $keyword_by = $_POST['keyword_by'];

        if ($keyword_by === "akses") {
            echo '<select name="keyword" id="keyword" class="form-control">';
            echo '  <option value="">Pilih</option>';

            try {
                $stmt = $Conn->prepare("SELECT DISTINCT akses FROM akses ORDER BY akses DESC");
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($results as $row) {
                    $akses = htmlspecialchars($row['akses']);
                    echo '<option value="' . $akses . '">' . $akses . '</option>';
                }

            } catch (PDOException $e) {
                echo '<option value="">Error: ' . $e->getMessage() . '</option>';
            }

            echo '</select>';
        } else {
            renderInputField();
        }
    }
?>
