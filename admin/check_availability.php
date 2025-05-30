<?php
include('connection.php');

if(isset($_POST['troom']) && isset($_POST['bed']) && isset($_POST['cin']) && isset($_POST['cout'])) {
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];

    // Check room availability
    $check_availability_query = "SELECT COUNT(*) AS available_rooms 
                                FROM room 
                                WHERE type = '$troom' 
                                AND bedding = '$bed' 
                                AND place = 'Free'
                                AND NOT EXISTS (
                                    SELECT 1 
                                    FROM roombook 
                                    WHERE room.type = roombook.TRoom 
                                    AND room.bedding = roombook.Bed 
                                    AND (
                                        ('$cin' BETWEEN roombook.cin AND DATE_SUB(roombook.cout, INTERVAL 1 DAY))
                                        OR ('$cout' BETWEEN DATE_ADD(roombook.cin, INTERVAL 1 DAY) AND roombook.cout)
                                        OR (roombook.cin BETWEEN '$cin' AND DATE_SUB('$cout', INTERVAL 1 DAY))
                                    )
                                )";

    $result = mysqli_query($con, $check_availability_query);

    if(!$result) {
        echo "error";
    } else {
        $row = mysqli_fetch_assoc($result);
        $available_rooms = $row['available_rooms'];

        if($available_rooms > 0) {
            echo "available";
        } else {
            echo "not_available";
        }
    }
} else {
    echo "error";
}
?>
