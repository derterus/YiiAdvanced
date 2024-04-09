<?php
/** @var yii\web\View $this */
use yii\helpers\Html;
?>
<div id="userTable">
    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $.ajax({
        url: 'http://webapiyii:8080/users',
        type: 'GET',
        success: function(data) {
            var table = $('<table></table>');
            data.forEach(function(user) {
                var row = $('<tr></tr>');
                row.append('<td>' + user.id + '</td>');
                row.append('<td>' + user.username + '</td>');
                row.append('<td>' + user.email + '</td>');
                table.append(row);
            });
            $('#userTable').append(table);
        },
        error: function(error) {
            console.log(error);
        }
    });
});
</script>

