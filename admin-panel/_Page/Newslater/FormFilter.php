<?php

    // Fungsi output HTML form
    function renderInputField() {
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }

    if (empty($_POST['keyword_by'])) {
        renderInputField();
    } else {
        $keyword_by = $_POST['keyword_by'];

        if ($keyword_by === "datetime") {
            echo '<input type="date" name="keyword" id="keyword" class="form-control">';
        } else {
            renderInputField();
        }
    }
?>
