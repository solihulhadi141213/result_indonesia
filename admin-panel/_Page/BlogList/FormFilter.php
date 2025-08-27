<?php
    // Fungsi output HTML form
    function renderInputField() {
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }

    if (empty($_POST['keyword_by'])) {
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    } else {
        $keyword_by = $_POST['keyword_by'];

        if ($keyword_by === "datetime_creat") {
            echo '<input type="date" name="keyword" id="keyword" class="form-control">';
        } else {
            if ($keyword_by === "publish") {
                echo '
                    <select class="form-control" name="keyword" id="keyword">
                        <option value="1">Publish</option>
                        <option value="0">Draft</option>
                    </select>
                ';
            } else {
                echo '<input type="text" name="keyword" id="keyword" class="form-control">';
            }
        }
    }
?>
